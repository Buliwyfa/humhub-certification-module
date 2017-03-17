<?php

namespace humhub\modules\certified\models;

use humhub\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $certified
 * @property integer $certified_by
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['certified', 'certified_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'certified' => Yii::t('CertifiedModule.models_Profile', 'Certified'),
            'certified_by' => Yii::t('CertifiedModule.models_Profile', 'Certified By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }

    public function needsAdminApproval()
    {
        if ($this->needs_admin_approval === 1) {
            return true;
        }
        return true;
    }
}
