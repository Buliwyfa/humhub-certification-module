<?php

namespace humhub\modules\certified\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Profile]].
 *
 * @see Profile
 */
class ProfileQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Profile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Profile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
