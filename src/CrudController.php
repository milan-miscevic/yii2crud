<?php

namespace mmm\yii2crud;

use Yii;
use mmm\yii2crud\exception\NotFound;
use yii\base\ViewNotFoundException;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CrudController extends Controller
{
    protected $name;
    protected $service;

    public function init()
    {
        parent::init();

        $this->name = $this->id;
        $this->getView()->params['crud']['name'] = $this->id;
        $this->id = 'crud';
        $this->service = Yii::$container->get('CrudService', [$this->name]);
    }

    public function actionIndex()
    {
        $form = Yii::$container->get('CrudForm', [$this->name]);
        $entities = $this->service->select();

        if ($form->load(Yii::$app->request->getQueryParams())) {
            $entities->andFilterWhere($form->getAttributes());
        }

        return $this->renderOrFallback(
            'index',
            [
                'entities' => $entities,
                'form' => $form,
            ]
        );
    }

    public function actionView()
    {
        try {
            $id = Yii::$app->request->get('id');
            $entity = $this->service->selectOne($id);
        } catch (NotFound $ex) {
            throw new NotFoundHttpException();
        }

        return $this->renderOrFallback(
            'view',
            [
                'entity' => $entity,
            ]
        );
    }

    public function actionAdd()
    {
        $form = Yii::$container->get('CrudForm', [$this->name]);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $data = $form->getAttributes();
            $entity = $this->service->createNewEntity();
            $entity->setAttributes($data, false);
            $this->service->add($entity);
            return $this->redirect(["//{$this->name}/index"]);
        }

        return $this->renderOrFallback(
            'save',
            [
                'model' => $form,
            ]
        );
    }

    public function actionEdit()
    {
        $id = Yii::$app->request->get('id');
        $form = Yii::$container->get('CrudForm', [$this->name]);

        try {
            $entity = $this->service->selectOne($id);
        } catch (NotFound $ex) {
            throw new NotFoundHttpException();
        }

        if (Yii::$app->request->isPost) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $data = $form->getAttributes();
                $entity->setAttributes($data, false);
                $this->service->edit($entity);
                return $this->redirect(["//{$this->name}/index"]);
            }
        } else {
            $form->load($entity->getAttributes(), '');
        }

        return $this->renderOrFallback(
            'save',
            [
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
            throw new NotFoundHttpException();
        }

        if (Yii::$app->request->isPost) {
            $yes = Yii::$app->request->post('yes-button', 'no');

            if ($yes === 'yes') {
                $this->service->delete($entity);
            }

            return $this->redirect(["//{$this->name}/index"]);
        }

        return $this->renderOrFallback(
            'delete',
            [
                'entity' => $entity,
            ]
        );
    }

    protected function renderOrFallback($view, $params)
    {
        try {
            return $this->render("//{$this->name}/{$view}", $params);
        } catch (ViewNotFoundException $ex) {
            return $this->render($view, $params);
        }
    }
}
