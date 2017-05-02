<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SGoods]].
 *
 * @see SGoods
 */
class SGoodsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SGoods[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SGoods|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function searchName($name = null)
    {
        return $this->andFilterWhere(['like', 'goods.name', trim($name)]);
    }
}
