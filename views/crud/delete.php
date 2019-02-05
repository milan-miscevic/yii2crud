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

<div>
    <?php $form = ActiveForm::begin(); ?>

        Delete <?= $entity->identifier ?>?

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Yes', ['class' => 'btn btn-danger', 'name' => 'yes-button', 'value' => 'yes']) ?>
                <?= Html::submitButton('No', ['class' => 'btn btn-primary', 'name' => 'no-button', 'value' => 'no']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
