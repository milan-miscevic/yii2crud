<?php

namespace mmm\yii2crud;

use yii\db\ActiveRecord;

class CrudActiveRecord extends ActiveRecord
{
    public function getIdentifier()
    {
        return $this->name ?? $this->getPrimaryKey();
    }
}
