<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;

$name = $this->params['crud']['name'];

$this->title = Inflector::camel2words($name);
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ["//{$name}"]];
$this->params['breadcrumbs'][] = isset($entity) ? $entity->identifier : 'New';

?>

<div>
    <?php $form = ActiveForm::begin([
        'id' => 'save-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?php
            foreach($model->getAttributes() as $key => $attribute) {
                echo $form->field($model, $key)->textInput(['autofocus' => true]);
            }
        ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
