<?php

/* @var $this yii\web\View */

use yii\helpers\Inflector;
use yii\helpers\Url;

$this->title = Inflector::camelize($name);
$this->params['breadcrumbs'][] = $this->title;

?>

<div>
    <table class="table table-striped table-hover table-condensed">
        <?php foreach ($entities as $entity) { ?>
            <tr>
                <td><?= $entity->identifier ?></td>
                <td>
                    <span class="pull-right">
                        <a href="<?= Url::to(["//{$name}/view", 'id' => $entity->id]); ?>">View</a>
                        -
                        <a href="<?= Url::to(["//{$name}/edit", 'id' => $entity->id]); ?>">Edit</a>
                        -
                        <a href="<?= Url::to(["//{$name}/delete", 'id' => $entity->id]); ?>">Delete</a>
                    </span>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="<?= Url::to(["//{$name}/add"]); ?>" class="btn btn-primary">Add</a>
</div>
