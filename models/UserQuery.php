<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function searchUsername($username = null)
    {
        return $this->andFilterWhere(['like', 'username', trim($username)]);
    }

    public function searchRealname($real_name = null)
    {
        return $this->andFilterWhere(['like', 'real_name', trim($real_name)]);
    }

    public function searchRole($role = null)
    {
        if (!$role) return $this;
        return $this->andWhere(['role' => trim($role)]);
    }

    public function searchStatus($status = null)
    {
        if (!$status && $status !== '0') return $this;
        return $this->andWhere(['user.status' => trim($status)]);
    }
}
