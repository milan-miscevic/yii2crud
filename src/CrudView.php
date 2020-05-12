<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use Yii;
use yii\web\View;

class CrudView extends View
{
    public function init()
    {
        $this->title = Yii::$app->name;
    }
}
