<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use mmm\yii2crud\exception\EntityNotFound;
use yii\db\ActiveQueryInterface;

class CrudService
{
    private $activeRecordClass;

    public function __construct(string $activeRecordClass)
    {
        $this->activeRecordClass = $activeRecordClass;
    }

    public function createNewEntity(): CrudActiveRecord
    {
        return new $this->activeRecordClass();
    }

    public function select($where = null, ?QueryParams $queryParams = null): ActiveQueryInterface
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

    protected function assignQueryParams(ActiveQueryInterface $query, QueryParams $params): ActiveQueryInterface
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

    public function selectAll($where = null, ?QueryParams $params = null): array
    {
        return $this->select($where, $params)->all();
    }

    public function selectOne($conditions): CrudActiveRecord
    {
        $entity = call_user_func([$this->activeRecordClass, 'findOne'], $conditions);

        if ($entity === null) {
            throw new EntityNotFound();
        } else {
            return $entity;
        }
    }

    public function add(CrudActiveRecord $entity): bool
    {
        return $entity->save();
    }

    public function edit(CrudActiveRecord $entity): bool
    {
        return $entity->save();
    }

    public function delete(CrudActiveRecord $entity)
    {
        return $entity->delete();
    }
}
