<?php
/**
 * Created by PhpStorm.
 * User: jerem
 * Date: 3/8/2017
 * Time: 2:11 AM
 */

namespace humhub\modules\certified\libs;

use humhub\modules\user\models\Group;
use humhub\modules\user\models\GroupPermission;
use humhub\modules\user\models\GroupUser;
use yii;


class CertifiedHelper
{
    private static $instance;
    protected $uncertifiedUsersGroup = 'Uncertified Users';
    protected $certifiedUsersGroup = 'Users';
    protected $module;
    protected $certifiedSettingsLoaded = false;

    /**
     *Checks to see if the groups permission are set.
     */
    public function init()
    {
        $this->checkGroupPermissions();
        $this->module = yii::$app->getModule('certified');
    }


    /**
     * Looks to if itself is set with an instance and if it isn't
     * then it sets one and returns that instance. To stop the
     * helper from making more than one instance.
     *
     * @return CertifiedHelper
     */
    public static function singleton()
    {

        if (!isset(self::$instance)) {
            self::$instance = new CertifiedHelper();
        }
        return self::$instance;
    }

    /**
     * Checks the settings to see if the module should certify the user
     * after they submit a picture.
     *
     * @return bool
     */
    public function checkAfterSubmit()
    {
        $module = yii::$app->getModule('certified');
        $module->settings->get('Certify After Submit');
        if ($certify = $module->settings->get('Certify After Submit'))
        {
            return false;
        }
        return true;
    }

    /**
     *  Checks the groups for group permission to make sure that the groups
     * have the correct mail permission.
     */
    public function checkGroupPermissions()
    {
        if (($this->certifiedSettingsLoaded) == false ) {
            $permissions = GroupPermission::find()->where(['module_id' => 'mail'])->all();
            foreach ($permissions as $permission) {
                $groupId = $permission->group_id;
                $group = $this->compareGroupId($groupId);
                if ($group == 'Certified') {
                    $permission->permission_id = 'humhub\modules\mail\permissions\SendMail';
                    $permission->state = 1;
                } elseif ($group == 'Uncertified') {
                    $permission->permission_id = 'humhub\modules\mail\permissions\SendMail';
                    $permission->state = 0;
                }
            }
        }
        $this->doNotShowGroupsAtRegistration($this->certifiedUsersGroup);
        $this->doNotShowGroupsAtRegistration($this->uncertifiedUsersGroup);
    }

    protected function doNotShowGroupsAtRegistration($groupName)
    {
        $group = Group::find()->where(['name' => $groupName])->one();
        $group->show_at_registration = 0;
        $group->show_at_directory = 0;
    }

    /**
     * Compares the group_id that is passed in and sees if its apart of
     * the uncertified or certified group. Returns a string for group representation.
     *
     * @param $group_id
     * @return string (Certified or Uncertified)
     */
    protected function compareGroupId($group_id)
    {
        $certifiedUsers = $this->findGroup($this->certifiedUsersGroup);
        $uncertifiedUsers = $this->findGroup($this->uncertifiedUsersGroup);
        if ($certifiedUsers == $group_id){
            return 'Certified';
        } elseif ($uncertifiedUsers == $group_id) {
            return 'Uncertified';
        } else {
            return 'unknown';
        }
    }

    /**
     * Makes sure that the name of this module is set to certified.
     *
     */
    protected function getModule()
    {
        $this->module = yii::$app->getModule('certified');
    }

    /**
     *Checks the module settings to see if the default uncertified user groups or if
     * the certified user group has been changed.
     */
    public function checkGroups()
    {
        $this->getModule();
        $this->uncertifiedUsersGroup = $this->module->settings->get('Uncertified Users Group');
        $this->certifiedUsersGroup = $this->module->settings->get('Certified Users Group');
        $this->groupExists($this->uncertifiedUsersGroup, 'Uncertified Group');
        $this->groupExists($this->certifiedUsersGroup, 'Certified Users Group');

    }

    /**
     * Checks to see if the group exists and if it doesn't then it creates the
     * group.
     *
     * @param $groupName
     * @param $groupDescription
     * @return bool
     */
    protected function groupExists ($groupName, $groupDescription)
    {
        $group = $this->findGroup($groupName);
        if (!$group){
            $this->addGroup($groupName, $groupDescription);
        }
        return true;
    }

    /**
     * Stores the group into the database.
     *
     * @param $groupName
     * @param $groupDiscription
     */
    protected function addGroup($groupName, $groupDiscription)
    {
        $newGroup = new Group();
        $newGroup->created_by = yii::$app->user->id;
        $newGroup->name = $groupName;
        $newGroup->description = $groupDiscription;
        $newGroup->save();
    }

    /**
     * Checks if the user is apart of the uncertified or certified group
     * and then switches him from one to the other.
     *
     * @param $userId
     * @return string
     */
    public function changeGroups($userId)
    {
        $certifiedGroupId = $this->findGroup($this->certifiedUsersGroup);
        $isInCertifiedGroup = $this->findGroupUser($certifiedGroupId, $userId);
        if ($isInCertifiedGroup == false) {
            $this->addToGroup($userId,$this->certifiedUsersGroup);
            $this->removeFromGroup($userId, $this->uncertifiedUsersGroup);
            return 'Moved from Uncertified Group';
        }
        $this->addToGroup($userId, $this->uncertifiedUsersGroup);
        $this->removeFromGroup($userId, $this->certifiedUsersGroup);
        return 'Moved from Certified Group';
    }

    /**
     * Adds the user to the group.
     *
     * @param $userId
     * @param $groupName
     * @return bool
     */
    protected function addToGroup($userId, $groupName)
    {
        $groupId = $this->findGroup($groupName);
        $newRecord = new GroupUser();
        $newRecord->created_by = yii::$app->user->id;
        $newRecord->group_id = $groupId;
        $newRecord->user_id = $userId;
        if($newRecord->save()){
            return true;
        }
        return false;
    }

    /**
     * removes the user from the group.
     *
     * @param $userId
     * @param $groupName
     * @return bool
     */
    protected function removeFromGroup($userId, $groupName)
    {
        $groupId = $this->findGroup($groupName);
        $record = $this->findGroupUser($groupId, $userId);
        if ($record == false){
            return false;
        }
        $record->delete();
        return true;
    }

    /**
     * checks if the user is apart of the group.
     *
     * @param $groupId
     * @param $userId
     * @return array|bool|null|yii\db\ActiveRecord
     */
    protected function findGroupUser($groupId, $userId)
    {
        $record = GroupUser::find()->where(['user_id' => $userId])->andWhere(['group_id' => $groupId])->one();

        if($record !== null) {
            return $record;
        }
         return false;
    }

    /**
     * finds the group id by searching for the groups name.
     *
     * @param $groupName
     * @return bool
     */
    protected function findGroup($groupName)
    {
        $group = Group::find()->where(['name' => $groupName])->one();
        if ($group !== null) {
            $groupId = $group->getGroupId();
            return $groupId;
        }
        return false;
    }
}