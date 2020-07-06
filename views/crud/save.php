<?php

declare(strict_types=1);

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\View;

/** @var View $this */

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
            foreach ($model->getAttributes() as $field => $value) {
                $input = $this->params['crud']['form'][$field]['input'] ?? null;
                $config = $this->params['crud']['form'][$field]['config'] ?? null;

                if (isset($input, $config)) {
                    echo call_user_func_array([$form->field($model, $field), $input], $config);
                } else {
                    echo $form->field($model, $field)->textInput(['autofocus' => true]);
                }
            }
        ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
