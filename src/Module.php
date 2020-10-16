<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as YiiModule;
use yii\helpers\Inflector;

class Module extends YiiModule implements BootstrapInterface
{
    /** @var string[] */
    private $cruds;

    /** @var array<string, string> */
    private $namespaces;

    public function bootstrap($app): void
    {
        foreach ($this->cruds as $crud) {
            $controllerClass = Yii::$app->controllerNamespace . '\\' . Inflector::camelize($crud) . 'Controller';
            Yii::$app->controllerMap[$crud] = [
                'class' => class_exists($controllerClass) ? $controllerClass : CrudController::class,
                'module' => $this,
            ];
        }
    }

    public function init(): void
    {
        parent::init();

        $this->setViewPath($this->getBasePath() . '//..//views//');

        Yii::$container->set(CrudService::class, function ($container, $params, $config) {
            $name = Inflector::camelize($params['0']);
            $serviceClass = "{$this->namespaces['service']}\\{$name}Service";
            $activeRecordClass = "{$this->namespaces['models']}\\{$name}";
            if (class_exists($serviceClass)) {
                return new $serviceClass($activeRecordClass);
            } else {
                return new CrudService($activeRecordClass);
            }
        });

        Yii::$container->set(CrudForm::class, function ($container, $params, $config) {
            $formClass = $this->namespaces['form'] . '\\' . Inflector::camelize($params['0']) . 'Form';
            return new $formClass();
        });

        Yii::$container->set(CrudFormSearch::class, function ($container, $params, $config) {
            return new CrudFormSearch($container->get(CrudForm::class, $params));
        });
    }

    /**
     * @param string[] $cruds
     */
    public function setCruds(array $cruds): void
    {
        $this->cruds = $cruds;
    }

    /**
     * @param array<string, string> $namespaces
     */
    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;

        foreach (['form', 'models', 'service'] as $name) {
            if (!isset($this->namespaces[$name])) {
                $this->namespaces[$name] = "app\\{$name}";
            }
        }
    }
}
