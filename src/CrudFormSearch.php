<?php

declare(strict_types=1);

namespace mmm\yii2crud;

use yii\base\Model;

class CrudFormSearch extends Model
{
    /** @var CrudForm */
    private $form;

    /** @var string */
    public $id;

    public function __construct(CrudForm $form)
    {
        $this->form = $form;
    }

    public function __set($name, $value): void
    {
        $this->form->$name = $value;
    }

    public function __get($name)
    {
        return $this->form->$name;
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        $attributes = $this->form->attributes();
        $attributes[] = 'id';

        return $attributes;
    }

    public function formName(): string
    {
        $reflector = new \ReflectionClass($this->form);

        return $reflector->getShortName();
    }

    /**
     * @return array[]
     */
    public function rules()
    {
        $rules = $this->form->rules();
        $rules[] = [['id'], 'safe'];

        return $rules;
    }
}
