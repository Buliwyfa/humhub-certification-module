<?php

namespace humhub\modules\certified\models;

use humhub\components\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Profile;

/**
 * This is the model class for table "awaiting_certification".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $his_picture_url
 * @property string $her_picture_url
 * @property integer $user_id
 */
class AwaitingCertification extends \humhub\components\ActiveRecord
{
    public $file_her;
    public $file_him;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'awaiting_certification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['user_id'], 'integer'],
            [['his_picture_url', 'her_picture_url'], 'string', 'max' => 255],
            [['file_her', 'file_him'],'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_AwaitingCertification', 'Created At',
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_AwaitingCertification', 'His Picture Url',
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_AwaitingCertification', 'Her Picture Url',
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_AwaitingCertification', 'User ID',
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_AwaitingCertification', 'Her Picture',
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_AwaitingCertification', 'His Picture',
    }
    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
     public function attributeLabels()
     {
     return array(
        'created at' => Yii::t('CertificationModule.models_forms_AwaitingCertification', 'Created At'),
        'his picture url' => Yii::t('CertificationModule.models_forms_AwaitingCertification', 'His Picture Url'),
        'her picture irl' => Yii::t('CertificationModule.models_forms_AwaitingCertification', 'Her Picture Url'),
        'user id' => Yii::t('CertificationModule.models_forms_AwaitingCertification', 'User ID'),
        'her picture' => Yii::t('CertificationModule.models_forms_AwaitingCertification', 'Her Picture'),
        'her picture' => Yii::t('CertificationModule.models_forms_AwaitingCertification', 'His Picture'),
         ];
     }
         
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        $this->hasOne(Profile::className(), ['user_id' => 'user_id']);

    }

}
