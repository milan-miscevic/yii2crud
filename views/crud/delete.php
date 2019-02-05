<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;

$name = $this->params['crud']['name'];

$this->title = Inflector::camel2words($name);
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ["//{$name}"]];
$this->params['breadcrumbs'][] = $entity->identifier;

?>

<?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-12">
            Delete <?= $entity->identifier ?>?
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            &nbsp;
        </div>
    </div>

    <div class="row form-group">
        <div class="col-lg-12">
            <?= Html::submitButton('Yes', ['class' => 'btn btn-danger', 'name' => 'yes-button', 'value' => 'yes']) ?>
            <?= Html::submitButton('No', ['class' => 'btn btn-primary', 'name' => 'no-button', 'value' => 'no']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>

