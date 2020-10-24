<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use mmm\yii2crud\exception\EntityNotFound;
use Yii;
use yii\base\ViewNotFoundException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class CrudController extends Controller
{
    /** @var string */
    protected $name;

    /** @var CrudService */
    protected $service;

    public function init(): void
    {
        parent::init();

        $this->name = $this->id;
        $this->getView()->params['crud']['name'] = $this->id;
        $this->id = 'crud';

        /** @var CrudService $service */
        $service = Yii::$container->get(CrudService::class, [$this->name]);
        $this->service = $service;
    }

    public function actionIndex(): string
    {
        /** @var Request $request */
        $request = Yii::$app->request;
        /** @var CrudFormSearch $form */
        $form = Yii::$container->get(CrudFormSearch::class, [$this->name]);
        $entities = $this->service->select();

        if ($form->load($request->getQueryParams())) {
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

    public function actionView(): string
    {
        /** @var Request $request */
        $request = Yii::$app->request;

        try {
            $id = $request->get('id');
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
        /** @var Request $request */
        $request = Yii::$app->request;
        /** @var CrudForm $form */
        $form = Yii::$container->get(CrudForm::class, [$this->name]);

        if ($form->load($request->post()) && $form->validate()) {
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
     */
    public function actionEdit()
    {
        /** @var Request $request */
        $request = Yii::$app->request;
        $id = $request->get('id');
        /** @var CrudForm $form */
        $form = Yii::$container->get(CrudForm::class, [$this->name]);

        try {
            $entity = $this->service->selectOne($id);
        } catch (EntityNotFound $ex) {
            throw new NotFoundHttpException();
        }

        if ($request->isPost) {
            if ($form->load($request->post()) && $form->validate()) {
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
     */
    public function actionDelete()
    {
        /** @var Request $request */
        $request = Yii::$app->request;
        $id = $request->get('id');

        try {
            $entity = $this->service->selectOne($id);
        } catch (EntityNotFound $ex) {
            throw new NotFoundHttpException();
        }

        if ($request->isPost) {
            $yes = $request->post('yes-button', 'no');

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
     * @param array<string, mixed> $params
     */
    protected function renderOrFallback(string $view, array $params): string
    {
        try {
            return $this->render("//{$this->name}/{$view}", $params);
        } catch (ViewNotFoundException $ex) {
            return $this->render($view, $params);
        }
    }
}
