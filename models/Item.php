<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property int $seller_id
 * @property int $buyer_id
 * @property string $name
 * @property string $desc
 * @property string $start_price
 * @property string $end_price
 * @property string $step_price
 * @property int $step_time
 * @property int $status
 *
 * @property User $buyer
 * @property User $seller
 */
class Item extends \yii\db\ActiveRecord
{
    public $current_price;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seller_id', 'name', 'start_price', 'end_price', 'step_price', 'step_time', 'status'], 'required'],
            [['seller_id', 'buyer_id', 'step_time', 'sell_price', 'status'], 'integer'],
            [['desc'], 'string'],
            [['start_price', 'end_price', 'step_price'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['buyer_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['seller_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'seller_id' => Yii::t('app', 'Seller'),
            'buyer_id' => Yii::t('app', 'Buyer'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'start_price' => Yii::t('app', 'Start Price'),
            'end_price' => Yii::t('app', 'End Price'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'step_price' => Yii::t('app', 'Step Price'),
            'step_time' => Yii::t('app', 'Step Time'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(User::class, ['id' => 'buyer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(User::class, ['id' => 'seller_id']);
    }
}
