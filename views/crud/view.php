<?php

/* @var $this yii\web\View */

use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\widgets\DetailView;

$name = $this->params['crud']['name'];

$this->title = Inflector::camel2words($name);
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ["//{$name}"]];
$this->params['breadcrumbs'][] = $entity->identifier;

$defaultDetailConfig = [
    'model' => $entity,
    'attributes' => $entity->attributes(),
];

$this->params['crud']['detail'] = array_replace_recursive(
    $defaultDetailConfig,
    $this->params['crud']['detail'] ?? []
);

?>

<div>
    <?= DetailView::widget($this->params['crud']['detail']); ?>

    <a href="<?= Url::to(["//{$name}/edit", 'id' => $entity->id]); ?>" class="btn btn-primary">Edit</a>
    <a href="<?= Url::to(["//{$name}/delete", 'id' => $entity->id]); ?>" class="btn btn-primary">Delete</a>
</div>
