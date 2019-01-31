<?php

namespace mmm\yii2crud;

use Yii;
use mmm\yii2crud\exception\NotFound;
use yii\base\ViewNotFoundException;
use yii\helpers\Inflector;
use yii\web\Controller;

class CrudController extends Controller
{
    protected $name;
    protected $service;

    public function init()
    {
        parent::init();

        $this->name = $this->id;
        $this->id = 'crud';
        $this->view = Yii::$container->get('CrudView', [$this->name]);
        $this->service = Yii::$container->get('CrudService', [$this->name]);
    }

    public function actionIndex()
    {
        $form = $this->createNewForm();
        $entities = $this->service->select();

        if ($form->load(Yii::$app->request->getQueryParams())) {
            $entities->andFilterWhere($form->getAttributes());
        }

        return $this->renderOrFallback(
            'index',
            [
                'name' => $this->name,
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
            throw new \yii\web\NotFoundHttpException();
        }

        return $this->renderOrFallback(
            'view',
            [
                'name' => $this->name,
                'entity' => $entity,
            ]
        );
    }

    public function actionAdd()
    {
        $form = $this->createNewForm();

        if ($form->load(Yii::$app->request->post())) {
            $data = $form->getAttributes();
            $entity = $this->service->createNewEntity();
            $entity->setAttributes($data, false);
            $this->service->add($entity);
            return $this->redirect(["//{$this->name}/index"]);
        }

        return $this->renderOrFallback(
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
        $form = $this->createNewForm();

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
            'delete',
            [
                'name' => $this->name,
                'entity' => $entity,
            ]
        );
    }

    private function createNewForm()
    {
        $formClass = 'app\\form\\' . Inflector::camelize($this->name);

        return new $formClass();
    }

    private function renderOrFallback($view, $params)
    {
        try {
            return $this->render("//{$this->name}/{$view}", $params);
        } catch (ViewNotFoundException $ex) {
            return $this->render($view, $params);
        }
    }
}
