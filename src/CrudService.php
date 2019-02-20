<?php

namespace mmm\yii2crud;

use mmm\yii2crud\exception\NotFound;
use yii\helpers\Inflector;

class CrudService
{
    private $activeRecordClass;

    public function __construct($activeRecordClass)
    {
        $this->activeRecordClass = $activeRecordClass;
    }

    public function createNewEntity()
    {
        return new $this->activeRecordClass();
    }

    public function select($where = null, $queryParams = null)
    {
        $query = call_user_func([$this->activeRecordClass, 'find']);

        if ($where !== null) {
            $query->where($where);
        }

        if ($queryParams !== null) {
            $query = $this->assignQueryParams($query, $queryParams);
        }

        return $query;
    }

    protected function assignQueryParams($query, $params)
    {
        if ($params->orderBy !== null) {
            $query->orderBy($params->orderBy);
        }
        if ($params->limit !== null) {
            $query->limit($params->limit);
        }
        if ($params->offset !== null) {
            $query->offset($params->offset);
        }
        if ($params->with !== null) {
            $query->with($params->with);
        }

        return $query;
    }

    public function selectAll($where = null, $params = null)
    {
        return $this->select($where, $params)->all();
    }

    public function selectOne($conditions)
    {
        $entity = call_user_func([$this->activeRecordClass, 'findOne'], $conditions);

        if ($entity === null) {
            throw new NotFound();
        } else {
            return $entity;
        }
    }

    public function add($entity)
    {
        return $entity->save();
    }

    public function edit($entity)
    {
        return $entity->save();
    }

    public function delete($entity)
    {
        return $entity->delete();
    }
}
