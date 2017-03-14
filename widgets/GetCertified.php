<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

namespace humhub\modules\certified\widgets;

use humhub\components\Widget;
use humhub\modules\certified\models\AwaitingCertification;
use humhub\modules\certified\models\Profile;

/**
 * @author andystrobel
 */
class GetCertified extends Widget
{


    public function run()
    {
        $model = new AwaitingCertification();
        $profileType = Profile::find()->where(['user_id' => yii::$app->user->id ])->one();
        $profileType = $profileType->gender;
        return $this->render('certifiedPanel', [
                'model' => $model,
                'profileType' => $profileType,
            ]);

    }



}
