<?php

namespace humhub\modules\certified\models;

use humhub\components\ActiveRecord;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $title
 * @property string $gender
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $country
 * @property string $state
 * @property integer $birthday_hide_year
 * @property string $birthday
 * @property string $about
 * @property string $phone_private
 * @property string $phone_work
 * @property string $mobile
 * @property string $fax
 * @property string $im_skype
 * @property string $im_msn
 * @property string $im_xmpp
 * @property string $url
 * @property string $url_facebook
 * @property string $url_linkedin
 * @property string $url_xing
 * @property string $url_youtube
 * @property string $url_vimeo
 * @property string $url_flickr
 * @property string $url_myspace
 * @property string $url_googleplus
 * @property string $url_twitter
 * @property integer $needs_admin_approval
 * @property integer $certified
 * @property integer $certified_by
 *
 * @property User $user
 */
class Profile extends ActiveRecord
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
            [['user_id', 'birthday_hide_year', 'needs_admin_approval', 'certified', 'certified_by'], 'integer'],
            [['birthday'], 'safe'],
            [['about'], 'string'],
            [['firstname', 'lastname', 'title', 'gender', 'street', 'zip', 'city', 'country', 'state', 'phone_private', 'phone_work', 'mobile', 'fax', 'im_skype', 'im_msn', 'im_xmpp', 'url', 'url_facebook', 'url_linkedin', 'url_xing', 'url_youtube', 'url_vimeo', 'url_flickr', 'url_myspace', 'url_googleplus', 'url_twitter'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('CertifiedModule.models_forms_Profile', 'User ID'),
            'first name' => Yii::t('CertifiedModule.models_forms_Profile', 'First name'),
            'last name' => Yii::t('CertifiedModule.models_forms_Profile', 'Last name'),
            'title' => Yii::t('CertifiedModule.models_forms_Profile', 'Title'),
            'gender' => Yii::t('CertifiedModule.models_forms_Profile', 'Gender'),
            'street' => Yii::t('CertifiedModule.models_forms_Profile', 'Street'),
            'zip' => Yii::t('CertifiedModule.models_forms_Profile', 'Zip'),
            'city' => Yii::t('CertifiedModule.models_forms_Profile', 'City'),
            'country' => Yii::t('CertifiedModule.models_forms_Profile', 'Country'),
            'state' => Yii::t('CertifiedModule.models_forms_Profile', 'State'),
            'birthday_hide_year' => Yii::t('CertifiedModule.models_forms_Profile', 'Birthday Hide Year'),
            'birthday' => Yii::t('CertifiedModule.models_forms_Profile', 'Birthday'),
            'about' => Yii::t('CertifiedModule.models_forms_Profile', 'About'),
            'phone_private' => Yii::t('CertifiedModule.models_forms_Profile', 'Phone Private'),
            'phone_work' => Yii::t('CertifiedModule.models_forms_Profile', 'Phone Work'),
            'mobile' => Yii::t('CertifiedModule.models_forms_Profile', 'Mobile'),
            'fax' => Yii::t('CertifiedModule.models_forms_Profile', 'Fax'),
            'im_skype' => Yii::t('CertifiedModule.models_forms_Profile', 'Im Skype'),
            'im_msn' => Yii::t('CertifiedModule.models_forms_Profile', 'Im Msn'),
            'im_xmpp' => Yii::t('CertifiedModule.models_forms_Profile', 'Im Xmpp'),
            'url' => Yii::t('CertifiedModule.models_forms_Profile', 'Url'),
            'url_facebook' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Facebook'),
            'url_linkedin' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Linkedin'),
            'url_xing' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Xing'),
            'url_youtube' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Youtube'),
            'url_vimeo' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Vimeo'),
            'url_flickr' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Flickr'),
            'url_myspace' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Myspace'),
            'url_googleplus' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Googleplus'),
            'url_twitter' => Yii::t('CertifiedModule.models_forms_Profile', 'Url Twitter'),
            'needs_admin_aproval' => Yii::t('CertifiedModule.models_forms_Profile', 'Needs admin approval to be certified'),
            'certified' => Yii::t('CertifiedModule.models_forms_Profile', 'Certified'),
            'certified_by' => Yii::t('CertifiedModule.models_forms_Profile', 'Certified By'),
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
