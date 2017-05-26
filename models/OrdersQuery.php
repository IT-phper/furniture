<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Orders]].
 *
 * @see Orders
 */
class OrdersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Orders[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Orders|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function searchUsername($username)
    {
        return $this->andFilterWhere(['like', 'user.real_name', trim($username)]);
    }

    public function searchOrder_id($order_id = null)
    {
        return $this->andFilterWhere(['like', 'order_id', trim($order_id)]);
    }
}
