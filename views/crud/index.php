<?php

/* @var $this yii\web\View */

use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

$name = $this->params['crud']['name'];

$this->title = Inflector::camelize($name);
$this->params['breadcrumbs'][] = $this->title;

$defaultProviderConfig = [
    'query' => $entities,
    'pagination' => [
        'pageSize' => 50,
        'route' => $name,
    ],
    'sort' => [
        'route' => $name,
    ],
];

$this->params['crud']['provider'] = array_replace_recursive(
    $defaultProviderConfig,
    $this->params['crud']['provider'] ?? []
);

$provider = new ActiveDataProvider($this->params['crud']['provider']);

$defaultGridConfig = [
    'dataProvider' => $provider,
    'filterModel' => $form,
    'tableOptions' => [
        'class' => 'table table-striped table-bordered table-hover table-condensed'
    ],
];

$this->params['crud']['grid'] = array_replace_recursive(
    $defaultGridConfig,
    $this->params['crud']['grid'] ?? []
);

$grid = new GridView($this->params['crud']['grid']);

$actionColumn = new ActionColumn([
    'grid' => $grid,
    'urlCreator' => function ($action, $model, $key, $index, $column) use ($name) {
        if ($action === 'update') {
            $action = 'edit';
        }
        return Url::to(["/{$name}/{$action}", 'id' => $model->id]);
    },
    'buttons' => [
        'view' => function ($url, $model, $key) {
            return Html::a('View', $url, ['class' => 'btn btn-xs btn-primary']);
        },
        'update' => function ($url, $model, $key) {
            return Html::a('Edit', $url, ['class' => 'btn btn-xs btn-primary']);
        },
        'delete' => function ($url, $model, $key) {
            return Html::a('Delete', $url, ['class' => 'btn btn-xs btn-primary']);
        },
    ],
    'contentOptions' => ['style' => 'text-align: right'],
]);

$grid->columns[] = $actionColumn;

?>

<div class="row">
    <div class="col-sm-12">
        <a href="<?= Url::to(["/{$name}/add"]); ?>" class="btn btn-primary">Add</a>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        &nbsp;
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?= $grid->run(); ?>
    </div>
</div>
