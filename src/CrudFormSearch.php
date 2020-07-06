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

    /**
     * @param CrudForm $form
     */
    public function __construct(CrudForm $form)
    {
        $this->form = $form;
    }

    /**
     * @return void
     */
    public function __set($name, $value)
    {
        $this->form->$name = $value;
    }

    public function __get($name)
    {
        return $this->form->$name;
    }

    /**
     * @return String[]
     */
    public function attributes()
    {
        $attributes = $this->form->attributes();
        $attributes[] = 'id';

        return $attributes;
    }

    public function formName()
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
