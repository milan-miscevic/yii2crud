<?php

declare(strict_types=1);

namespace mmmtest\yii2crud;

use PHPUnit\Framework\TestCase;
use mmm\yii2crud\CrudActiveRecord;
use mmm\yii2crud\CrudService;

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
