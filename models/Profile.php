<?php

namespace humhub\modules\certified\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property integer $awaiting_certification
 * @property integer $certified
 * @property integer $certified_by
 */
class Profile extends \humhub\components\ActiveRecord
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
            [['user_id'], 'required'],
            [['awaiting_certification', 'certified', 'certified_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_Profile', 'Awaiting Certification',
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_Profile', 'Certified',
        $this->title = $settings->get('title', Yii::t('CertificationModule.models_forms_Profile', 'Certified By',
    }
    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
     public function attributeLabels()
     {
     return array(
        'awaiting certification' => Yii::t('CertificationModule.models_forms_Profile', 'Awaiting Certification'),
        'certified' => Yii::t('CertificationModule.models_forms_Profile', 'Certified'),
        'certified by' => Yii::t('CertificationModule.models_forms_Profile', 'Certified By'),
         ];
     }

}
