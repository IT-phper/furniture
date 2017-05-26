<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[OperateLog]].
 *
 * @see OperateLog
 */
class OperateLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OperateLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OperateLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function searchDoUser($doUser = null)
    {
        if(!$doUser) return $this;
        return $this->andFilterWhere(['like', 'real_name', $doUser]);
    }
    
    public function searchDoShop($doShop = null)
    {
        if(!$doShop) return $this;
        return $this->andWhere(['doShop' => $doShop]);
    }

    public function searchIp($ip = null)
    {
        if(!$ip) return $this;
        return $this->andFilterWhere(['like', 'ip', $ip]);
    }

    public function searchLog($log = null)
    {
        if(!$log) return $this;
        return $this->andFilterWhere(['like','log', $log]);
    }

    public function searchReason($reason = null)
    {
        if(!$reason) return $this;
        return $this->andFilterWhere(['like','reason', $reason]);
    }
}
