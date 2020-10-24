<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use yii\db\ExpressionInterface;

class QueryParams
{
    /** @var string|array|ExpressionInterface|null */
    public $orderBy;

    /** @var int|ExpressionInterface|null */
    public $limit;

    /** @var int|ExpressionInterface|null */
    public $offset;

    /** @var mixed */
    public $with;
}
