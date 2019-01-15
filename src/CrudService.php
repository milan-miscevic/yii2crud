<?php

namespace mmm\yii2crud;

use mmm\yii2crud\exception\NotFound;
use yii\helpers\Inflector;

class CrudService
{
    private $activeRecordClass;

    public function __construct($name)
    {
        $this->activeRecordClass = 'app\\models\\' . Inflector::camelize($name);
    }

    public function getActiveRecordClass()
    {
        return $this->activeRecordClass;
    }

    public function selectAll()
    {
        return call_user_func([$this->activeRecordClass, 'find'])->all();
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

    public function select($conditions)
    {
        return call_user_func([$this->activeRecordClass, 'findAll'], $conditions);
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
