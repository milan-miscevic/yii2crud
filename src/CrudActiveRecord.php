<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use yii\db\ActiveRecord;

class CrudActiveRecord extends ActiveRecord
{
    public function getIdentifier(): string
    {
        return $this->name ?? $this->id;
    }
}
