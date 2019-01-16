<?php

/* @var $this yii\web\View */

use yii\helpers\Inflector;
use yii\helpers\Url;

$this->title = Inflector::camelize($name);
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ["//{$name}"]];
$this->params['breadcrumbs'][] = $entity->identifier;

?>

<div>
    <table class="table table-striped table-hover table-condensed">
        <?php foreach($entity->getAttributes() as $key => $attribute) { ?>
            <tr>
                <td><?= $entity->getAttributeLabel($key) ?></td>
                <td><?= $entity->getAttribute($key) ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="<?= Url::to(["//{$name}/edit", 'id' => $entity->id]); ?>" class="btn btn-primary">Edit</a>
    <a href="<?= Url::to(["//{$name}/delete", 'id' => $entity->id]); ?>" class="btn btn-primary">Delete</a>
</div>
