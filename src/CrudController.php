<?php

namespace mmm\yii2crud;

use Exception;
use Yii;
use mmm\yii2crud\exception\NotFound;
use yii\base\ViewNotFoundException;
use yii\web\Controller;

class CrudController extends Controller
{
    protected $name;
    protected $service;

    public function init()
    {
        $this->name = $this->id;
        $this->service = Yii::$container->get('CrudService', [$this->name]);
        $this->id = 'crud';
    }

    public function actionIndex()
    {
        return $this->renderOrFallback(
            $this->name,
            'index',
            [
                'name' => $this->name,
                'entities' => $this->service->selectAll(),
            ]
        );
    }

    public function actionView()
    {
        try {
            $id = Yii::$app->request->get('id');
            $entity = $this->service->selectOne($id);
        } catch (NotFound $ex) {
            throw new \yii\web\NotFoundHttpException();
        }

        return $this->renderOrFallback(
            $this->name,
            'view',
            [
                'name' => $this->name,
                'entity' => $entity,
            ]
        );
    }

    public function actionAdd()
    {
        $formClass = "app\\form\\{$this->name}";
        $form = new $formClass();

        if ($form->load(Yii::$app->request->post())) {
            $data = $form->getAttributes();
            $activeRecordClass = $this->service->getActiveRecordClass();
            $entity = new $activeRecordClass();
            $entity->setAttributes($data, false);
            $this->service->add($entity);
            return $this->redirect(["//{$this->name}/index"]);
        }

        return $this->renderOrFallback(
            $this->name,
            'save',
            [
                'name' => $this->name,
                'model' => $form,
            ]
        );
    }

    public function actionEdit()
    {
        $id = Yii::$app->request->get('id');
        $formClass = "app\\form\\{$this->name}";
        $form = new $formClass();

        try {
            $entity = $this->service->selectOne($id);
        } catch (NotFound $ex) {
            throw new \yii\web\NotFoundHttpException();
        }

        if (Yii::$app->request->isPost) {
            if ($form->load(Yii::$app->request->post())) {
                $data = $form->getAttributes();
                $entity->setAttributes($data, false);
                $this->service->edit($entity);
                return $this->redirect(["//{$this->name}/index"]);
            }
        } else {
            $form->load($entity->getAttributes(), '');
        }

        return $this->renderOrFallback(
            $this->name,
            'save',
            [
                'name' => $this->name,
                'model' => $form,
                'entity' => $entity,
            ]
        );
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');

        try {
            $entity = $this->service->selectOne($id);
        } catch (NotFound $ex) {
            throw new \yii\web\NotFoundHttpException();
        }

        if (Yii::$app->request->isPost) {
            $yes = Yii::$app->request->post('yes-button', 'no');

            if ($yes === 'yes') {
                $this->service->delete($entity);
            }

            return $this->redirect(["//{$this->name}/index"]);
        }

        return $this->renderOrFallback(
            $this->name,
            'delete',
            [
                'name' => $this->name,
                'entity' => $entity,
            ]
        );
    }

    private function renderOrFallback($id, $view, $params)
    {
        try {
            return $this->render("//{$id}/{$view}", $params);
        } catch (ViewNotFoundException $ex) {
            return $this->render($view, $params);
        }
    }
}
