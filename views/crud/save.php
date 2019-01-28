<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;

$this->params['breadcrumbs'][] = ['label' => Inflector::camelize($name), 'url' => ["//{$name}"]];
$this->params['breadcrumbs'][] = isset($entity) ? $entity->identifier : 'New';

?>

<div>
    <?php $form = ActiveForm::begin([
        'id' => 'save-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?php
            foreach($model->getAttributes() as $key => $attribute) {
                echo $form->field($model, $key)->textInput(['autofocus' => true]);
            }
        ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
