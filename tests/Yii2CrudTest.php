<?php

declare(strict_types=1);

namespace mmmtest\yii2crud;

use mmm\yii2crud\CrudActiveRecord;
use mmm\yii2crud\CrudService;
use PHPUnit\Framework\TestCase;

class Yii2CrudTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateNewEntity()
    {
        $class = CrudActiveRecord::class;

        $service = new CrudService($class);

        $this->assertInstanceOf($class, $service->createNewEntity());
    }
}
