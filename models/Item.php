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
 * @property int $start_time unix timestamp
 * @property int $end_time unix timestamp
 * @property string $start_price
 * @property string $end_price
 * @property string $sell_price
 * @property string $step_price
 * @property int $step_time in seconds
 * @property int $status
 *
 * @property User $buyer
 * @property User $seller
 */
class Item extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_TEMPLATE = 2;
    const STATUS_SELLING = 3;
    const STATUS_SOLD = 4;
    const STATUS_CLOSE = 5;

    /**
     * @var string
     */
    private $_currentPrice;

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
            'start_time' => Yii::t('app', 'Start Time Sell'),
            'start_price' => Yii::t('app', 'Start Price'),
            'end_price' => Yii::t('app', 'End Price'),
            'currentPrice' => Yii::t('app', 'Current Price'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'step_price' => Yii::t('app', 'Step Price'),
            'step_time' => Yii::t('app', 'Step Time'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getCurrentPrice($recalc = false)
    {
        if ($this->_currentPrice == false || $recalc) {
            switch ($this->status) {
                case static::STATUS_SOLD:
                    $this->_currentPrice = $this->end_price;
                    break;
                case static::STATUS_SELLING:
                    $now = time();
                    $diff = $now - $this->start_time;
                    $steps = round($diff / ($this->step_time));
                    $this->_currentPrice = round($this->start_price - ($steps * $this->step_price), 2);
                    if ($this->_currentPrice < $this->end_price) {
                        $this->_currentPrice = $this->end_price;
                        $this->close();
                    }
                    break;
                default:
                    $this->_currentPrice = 0;
            }
        }

        return $this->_currentPrice;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(User::class, ['id' => 'buyer_id']);
    }

    public static function getStatusLabels()
    {
        return [
            self::STATUS_DRAFT => Yii::t('app', 'Status Draft'),
            self::STATUS_TEMPLATE => Yii::t('app', 'Status Template'),
            self::STATUS_SELLING => Yii::t('app', 'Status Selling'),
            self::STATUS_SOLD => Yii::t('app', 'Status Sold'),
            self::STATUS_CLOSE => Yii::t('app', 'Status Close'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(User::class, ['id' => 'seller_id']);
    }

    public function setSelling()
    {
        if ($this->status == self::STATUS_DRAFT) {
            $this->status = self::STATUS_SELLING;
            $this->start_time = time();
            $diffPrice = $this->start_price - $this->end_price;
            $countStep = round($diffPrice / $this->step_price);
            $this->end_time = $this->start_time + ($countStep * $this->step_time);
            $this->save(false);
            return true;
        }

        $this->addError('status', Yii::t('app', 'You can Sell item only in status is draft'));
        return false;
    }

    public function isSelling()
    {
        if ($this->status == self::STATUS_SELLING) {
            if ($this->end_time < time()) {
                $this->close();
                return false;
            }

            return true;
        }

        return false;
    }

    public function close()
    {
        if ($this->status == self::STATUS_SELLING) {
            $this->status = self::STATUS_CLOSE;
            $this->save(false);
            return true;
        }

        $this->addError('status', Yii::t('app', 'You can close item only in status is sell'));
        return false;
    }

    public function buy()
    {
        if ($this->status == self::STATUS_SELLING) {
            if ($this->seller_id == \Yii::$app->user->getId()) {
                $this->addError('status', Yii::t('app', 'You can not buy your item'));
                return false;
            }
            $this->status = self::STATUS_SOLD;
            $this->sell_price = $this->getCurrentPrice(true);
            $this->buyer_id = \Yii::$app->user->getId();
            $this->save(false);
            return true;
        } else {
            if (in_array($this->status, [self::STATUS_SOLD, self::STATUS_CLOSE])) {
                $this->addError('status', Yii::t('app', 'You can not buy unsold item'));
                return false;
            }
            $this->addError('status', Yii::t('app', 'You can not buy unsold item'));
            return false;
        }
    }
}
