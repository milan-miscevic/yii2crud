<?php

namespace mmm\yii2crud;

class QueryParams
{
    public $orderBy;
    public $limit;
    public $offset;
    public $with;

    public function __construct($orderBy = null, $limit = null, $offset = null, $with = null)
    {
        $this->orderBy = $orderBy;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->with = $with;
    }
}
