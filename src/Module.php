<?php

namespace mmm\yii2crud;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as YiiModule;
use yii\helpers\Inflector;

class Module extends YiiModule implements BootstrapInterface
{
    private $cruds;

    public function bootstrap($app)
    {
        foreach ($this->cruds as $crud) {
            $controllerClass = Yii::$app->controllerNamespace . '\\' . Inflector::camelize($crud) . 'Controller';
            Yii::$app->controllerMap[$crud] = [
                'class' => class_exists($controllerClass) ? $controllerClass : 'mmm\\yii2crud\\CrudController',
                'module' => $this,
            ];
        }
    }

    public function init()
    {
        parent::init();

        $this->setViewPath($this->getBasePath() . '\\..\\views\\');
        Yii::$container->set('CrudService', 'mmm\\yii2crud\\CrudService');
    }

    public function setCruds($cruds)
    {
        $this->cruds = $cruds;
    }
}
