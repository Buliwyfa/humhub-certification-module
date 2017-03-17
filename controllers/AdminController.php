<?php

namespace humhub\modules\certified\controllers;

use humhub\components\behaviors\AccessControl;
use humhub\components\Controller;
use humhub\modules\certified\libs\CertifiedHelper;
use humhub\modules\certified\models\AwaitingCertification;
use humhub\modules\certified\models\Profile;
use humhub\modules\certified\permissions\CertifiedAdmin;
use humhub\modules\certified\permissions\ManageCertifications;
use humhub\modules\content\models\ContentContainerPermission;
use humhub\modules\file\models\File;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * AdminController implements the CRUD actions for AwaitingCertification model.
 */
class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'acl' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['permissions' => ManageCertifications::className()],
                    ['permissions' => CertifiedAdmin::className(), 'actions' => ['config']],
                ],
            ],
        ];
    }

    /**
     * Lists all AwaitingCertification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = AwaitingCertification::find()->where(['status' => 'Awaiting approval'])->all();


        return $this->render('approve', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AwaitingCertification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDenyCertification ($id)
    {
        $record = $this->findModel($id);
        $user = Profile::find()->where(['user_id' => $record->user_id])->one();
        $user->certified = 0;
        $record->status = 'Needs Admin Approval';
        $record->save();
        $helper = CertifiedHelper::singleton();
        $certifyAfterSubmit = $helper->checkAfterSubmit();
        if ($certifyAfterSubmit == true) {
            $changeUserGroup = $helper->changeGroups($record->user_id);
            if (!($changeUserGroup == 'Moved from Certified Group')) {
                Yii::warning('Something is wrong with the change user groups function in certified module');

            }
        }

        $model = AwaitingCertification::find()->where(['status' => 'Awaiting approval'])->all();

        return $this->render('approve', [
            'model' => $model,
        ]);
    }



    /**
     * Finds the AwaitingCertification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AwaitingCertification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AwaitingCertification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Renders the config page in the admin section.
     *
     * Todo: Needs to add in the custom options
     * Todo: Add custom message
     * Todo: Add custom certified group name
     * Todo: Add custom uncertified group name
     * Todo: Add custom option to automatically certify after upload or not
     * Todo: Add custom option to mark users as able to email each other after certification
     *
     * @return string
     */
    public function actionConfig()
    {
        $this->subLayout = '/layouts/config';
       return $this->render('config');
    }

    /**
     * Changes profile attribute certified to 1, deletes the picture by its guid,
     * delets AwaitingCertification record, and changes user permissions to allow
     * others to mail the user.
     *
     * @param $id
     * @return string
     */
    public function actionApproveCertification($id)
    {
        $awaitingCertification = AwaitingCertification::find()->where(['user_id' => $id])->one();
        $userProfile = Profile::find()->where(['user_id' => $awaitingCertification->user_id])->one();
        $userProfile->certified_by = yii::$app->user->id;
        $userProfile->save();
        $pictureGuid = [];
        if (($awaitingCertification->her_picture_guid) != null ) {
            $pictureGuid = $awaitingCertification->her_picture_guid;
        }
        if (($awaitingCertification->his_picture_guid) != null) {
            $pictureGuid = $awaitingCertification->his_picture_guid;
        }
        foreach ($pictureGuid as $picture) {
            File::find()->where(['guid' => $picture])->one()->delete();
        }
        $model = new ContentContainerPermission();
        $model->permission_id = 'humhub\modules\mail\permissions\RecieveMail';
        $model->contentcontainer_id = $awaitingCertification->user_id;
        $model->group_id = 'u_user';
        $model->module_id = 'mail';
        $model->class = 'humhub\modules\mail\permissions\RecieveMail';
        $model->state = 1;

        $awaitingCertification->delete();


        $model = AwaitingCertification::find()->where(['status' => 'Awaiting approval'])->all();

        return $this->render('approve', [
            'model' => $model,
        ]);

    }
}
