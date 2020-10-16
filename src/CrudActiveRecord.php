<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use yii\db\ActiveRecord;

/**
 * @property int $id
 */
class CrudActiveRecord extends ActiveRecord
{
    public function getIdentifier(): string
    {
        /**
         * @var mixed
         * @phpstan-ignore-next-line
         */
        $identifier = $this->name ?? $this->id;

        return (string) $identifier;
    }
}
