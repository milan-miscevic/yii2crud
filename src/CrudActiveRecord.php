<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use yii\db\ActiveRecord;

class CrudActiveRecord extends ActiveRecord
{
    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->name ?? $this->getPrimaryKey();
    }
}
