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
     *
     * todo: fix when user needs admin approval, user rebsubmitted image doesn't show up on approval page.
     * todo: enhancement-> if the user is denyied add a custom message option to tell the user why.
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
                Yii::warning(Yii::t('CertifiedModule.controllers_AdminController', 'Something is wrong with the change user groups function in certified module'));

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
            throw new NotFoundHttpException(Yii::t('CertifiedModule.controllers_AdminController', 'The requested page does not exist.'));
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
        $awaitingCertification = $this->findModel($id);
        $userProfile = Profile::find()->where(['user_id' => $awaitingCertification->user_id])->one();
        $userProfile->certified_by = yii::$app->user->id;
        $userProfile->save();

        $this->deletePictures($awaitingCertification->her_picture_guid);
        $this->deletePictures($awaitingCertification->his_picture_guid);

        $this->enableUserToRecieveMail($awaitingCertification->user_id);

        $awaitingCertification->delete();


        $model = AwaitingCertification::find()->where(['status' =>'Awaiting approval'])->all();

        return $this->render('approve', [
            'model' => $model,
        ]);

    }

    /**
     * Finds the pictures by its guid and deletes it.
     *
     * @param $guid
     */
    public function deletePictures($guid)
    {
        if ($guid !== null) {
            $file = File::find()->where(['guid' => $guid])->one();
            if ($file){
                $file->delete();
            }
        }
        return;
    }

    /**
     * Addes two permission to the contentContainerPermissions
     * for u_user and u_friend. Allowing both other users and other friends
     * to send the user mail.
     *
     * Todo: Keep an eye on Humhub to see if the contentContainer starts storing the correct user id
     *
     *
     * @param $user_id
     */
    private function enableUserToRecieveMail($user_id)
    {
        $groupIDs = ['u_user', 'u_friend'];
        foreach ($groupIDs as $groupid){
            $model = new ContentContainerPermission();
            $model->permission_id = 'humhub\modules\mail\permissions\RecieveMail';
            $model->contentcontainer_id = $user_id + 1; // The content container permissions stores the incorrect user id.
            $model->group_id = $groupid;
            $model->module_id = 'mail';
            $model->class = 'humhub\modules\mail\permissions\RecieveMail';
            $model->state = 1;
            $model->save();
        }

    }
}
