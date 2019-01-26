<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 26.01.19
 * Time: 12:41
 */

namespace app\components;

use yii\base\Component;
use Yii;
use yii\helpers\Html;

class Menu extends Component
{
    public static function getMenuByRole()
    {
        if (Yii::$app->user->isGuest) {
            return [
                ['label' => Yii::t('app', 'Registration'), 'url' => ['/site/register']],
                ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']]
            ];
        }

        $result = [
            ['label' => Yii::t('app', 'Profile'), 'url' => ['/user/profile']],
            ['label' => Yii::t('app', 'Auction'), 'url' => '/auction' ],

        ];

        if (Yii::$app->user->can('buy_item')) {
            $result[] = ['label' => Yii::t('app', 'Buy History'), 'url' => '/buy' ];
            $result[] = ['label' => Yii::t('app', 'Favorites Sellers'), 'url' => '/favorites' ];
        }

        if (Yii::$app->user->can('seller')) {
            $result[] = ['label' => Yii::t('app', 'Sale History'), 'url' => '/sale' ];
        }

        if (Yii::$app->user->can('profile_edit_own')) {
            $result[] = ['label' => Yii::t('app', 'Profile'), 'url' => ['/user/profile']];
        }

        if (Yii::$app->user->can('profile_edit_foreign')) {
            $result[] = ['label' => Yii::t('app', 'Users'), 'url' => '/user' ];
        }

        $result[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';

        return $result;
    }
}