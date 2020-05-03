# yii2crud

[![Build Status](https://travis-ci.com/milan-miscevic/yii2crud.svg?branch=master)](https://travis-ci.com/milan-miscevic/yii2crud)

Basic CRUD operations in the Yii2 framework.

## Installation

1. Install the module via Composor:

```bash
composer require milan-miscevic/yii2crud
```

2. Enable the module in the web.php config file:

```php
'bootstrap' => [
    // ...
    'yii2crud',
],
```

3. In the same file as above, define entities and namespaces for CRUD:

```php
'modules' => [
    // ...
    'yii2crud' => [
        'class' => 'mmm\yii2crud\Module',
        'cruds' => [
            'article',
        ],
        'namespaces' => [
            'form' => 'app\form',
            'models' => 'app\models',
            'service' => 'app\service',
        ],
    ],
],
```

4. Create form and active record files:

```php
// form/ArticleForm.php

namespace app\form;

use mmm\yii2crud\CrudForm;

class ArticleForm extends CrudForm
{
    public $title;
    public $content;

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
        ];
    }
}
```

```php
// models/Article.php

namespace app\models;

use mmm\yii2crud\CrudActiveRecord;

class Article extends CrudActiveRecord
{
}
```
