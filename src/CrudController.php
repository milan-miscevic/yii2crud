<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use Yii;
use mmm\yii2crud\exception\EntityNotFound;
use yii\base\ViewNotFoundException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CrudController extends Controller
{
    /** @var string */
    protected $name;

    /** @var CrudService */
    protected $service;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->name = $this->id;
        $this->getView()->params['crud']['name'] = $this->id;
        $this->id = 'crud';
        $this->service = Yii::$container->get('CrudService', [$this->name]);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $form = Yii::$container->get('CrudFormSearch', [$this->name]);
        $entities = $this->service->select();

        if ($form->load(Yii::$app->request->getQueryParams())) {
            foreach ($form->getAttributes() as $field => $value) {
                if (is_numeric($value)) {
                    $entities->andWhere([$field => $value]);
                } else {
                    $entities->andFilterWhere(['like', $field, $value]);
                }
            }
        }

        return $this->renderOrFallback(
            'index',
            [
                'entities' => $entities,
                'form' => $form,
            ]
        );
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        try {
            $id = Yii::$app->request->get('id');
            $entity = $this->service->selectOne($id);
        } catch (EntityNotFound $ex) {
            throw new NotFoundHttpException();
        }

        return $this->renderOrFallback(
            'view',
            [
                'entity' => $entity,
            ]
        );
    }

    /**
     * @return Response|string
     */
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

    /**
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id');
        $form = Yii::$container->get('CrudForm', [$this->name]);

        try {
            $entity = $this->service->selectOne($id);
        } catch (EntityNotFound $ex) {
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

    /**
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');

        try {
            $entity = $this->service->selectOne($id);
        } catch (EntityNotFound $ex) {
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

    /**
     * @param string $view
     * @param array<string, mixed> $params
     * @return string
     */
    protected function renderOrFallback($view, $params)
    {
        try {
            return $this->render("//{$this->name}/{$view}", $params);
        } catch (ViewNotFoundException $ex) {
            return $this->render($view, $params);
        }
    }
}
