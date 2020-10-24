<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use mmm\yii2crud\exception\EntityNotFound;
use yii\db\ActiveQuery;
use yii\db\ExpressionInterface;

class CrudService
{
    /** @var class-string<CrudActiveRecord> */
    private $activeRecordClass;

    /**
     * @param class-string<CrudActiveRecord> $activeRecordClass
     */
    public function __construct(string $activeRecordClass)
    {
        $this->activeRecordClass = $activeRecordClass;
    }

    public function createNewEntity(): CrudActiveRecord
    {
        return new $this->activeRecordClass();
    }

    /**
     * @param string|array|ExpressionInterface|null $where
     */
    public function select($where = null, ?QueryParams $queryParams = null): ActiveQuery
    {
        /**
         * @var ActiveQuery $query
         * @phpstan-ignore-next-line
         */
        $query = call_user_func([$this->activeRecordClass, 'find']);

        if ($where !== null) {
            $query->where($where);
        }

        if ($queryParams !== null) {
            $query = $this->assignQueryParams($query, $queryParams);
        }

        return $query;
    }

    protected function assignQueryParams(ActiveQuery $query, QueryParams $params): ActiveQuery
    {
        if ($params->orderBy !== null) {
            $query->orderBy($params->orderBy);
        }

        $query->limit($params->limit);
        $query->offset($params->offset);

        if ($params->with !== null) {
            $query->with($params->with);
        }

        return $query;
    }

    /**
     * @param string|array|ExpressionInterface|null $where
     * @return CrudActiveRecord[]
     */
    public function selectAll($where = null, ?QueryParams $params = null): array
    {
        /** @phpstan-ignore-next-line */
        return $this->select($where, $params)->all();
    }

    /**
     * @param mixed $conditions
     */
    public function selectOne($conditions): CrudActiveRecord
    {
        /**
         * @var ?CrudActiveRecord $entity
         * @phpstan-ignore-next-line
         */
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

    /**
     * @return int|false
     */
    public function delete(CrudActiveRecord $entity)
    {
        return $entity->delete();
    }
}
