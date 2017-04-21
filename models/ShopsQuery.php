<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Shops]].
 *
 * @see Shops
 */
class ShopsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Shops[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Shops|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function searchId($id = null)
    {
        return $this->andFilterWhere(['id' => trim($id)]);
    }

    public function searchName($name = null)
    {
        return $this->andFilterWhere(['like', 'name', trim($name)]);
    }

    public function searchAddr($addr = null)
    {
        return $this->andFilterWhere(['like', 'addr', trim($addr)]);
    }

    public function searchStatus($status = null)
    {
        if (!$status && $status !== '0') return $this;
        return $this->andWhere(['shops.status' => trim($status)]);
    }
}
