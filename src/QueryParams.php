<?php

declare(strict_types=1);

namespace mmm\yii2crud;

class QueryParams
{
    /** @var string|array|null */
    public $orderBy;

    /** @var int|null */
    public $limit;

    /** @var int|null */
    public $offset;

    /** @var mixed */
    public $with;

    /**
     * @param string|array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param mixed $with
     */
    public function __construct($orderBy = null, $limit = null, $offset = null, $with = null)
    {
        $this->orderBy = $orderBy;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->with = $with;
    }
}
