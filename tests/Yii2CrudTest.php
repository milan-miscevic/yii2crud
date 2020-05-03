<?php

declare(strict_types = 1);

namespace mmmtest\yii2crud;

use PHPUnit\Framework\TestCase;
use mmm\yii2crud\CrudActiveRecord;

class Yii2CrudTest extends TestCase
{
    public function testCreateNewEntity()
    {
        $class = CrudActiveRecord::class;

        $service = new \mmm\yii2crud\CrudService($class);

        $this->assertInstanceOf($class, $service->createNewEntity());
    }
}
