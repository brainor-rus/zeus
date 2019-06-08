<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 02.10.2018
 * Time: 13:36
 */

namespace Zeus\Admin\SectionBuilder\Form\Panel\Fields;


use Illuminate\Support\Facades\View;

class Select
{
    private $name, $field, $label, $value, $required, $readonly, $options, $modelForOptions, $queryFunctionForModel, $display, $defaultSelected;

    public function __construct($name, $label)
    {
        $this->setName($name);
        $this->setLabel($label);
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }
    /**
     * @param mixed $field
     * @return Select
     */
    public function setField($field): Select
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @param mixed $name
     */
    private function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $label
     */
    private function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @param mixed $value
     * @return Select
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param mixed $readonly
     * @return Select
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @param mixed $required
     * @return Select
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param mixed $options
     * @return Select
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param mixed $modelForOptions
     * @return Select
     */
    public function setModelForOptions($modelForOptions)
    {
        $this->modelForOptions = $modelForOptions;
        return $this;
    }


    /**
     * @param mixed $queryFunctionForModel
     * @return Select
     */
    public function setQueryFunctionForModel($queryFunctionForModel)
    {
        $this->queryFunctionForModel = $queryFunctionForModel;
        return $this;
    }

    /**
     * @param mixed $display
     * @return Select
     */
    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return mixed
     */
    public function getReadonly()
    {
        return $this->readonly;
    }

    /**
     * @return mixed
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        $field = $this->getField();

        if(isset($this->options))
        {
            return $this->options;
        } else
        {
            if($this->getModelForOptions() !== null)
            {
                foreach ($this->getModelForOptions()::when(
                    !empty($this->getQueryFunctionForModel()),
                    $this->getQueryFunctionForModel()
                )->get() as $row) {
                    $this->options[$row->{$field}] = $row->{$this->getDisplay()};
                }
            }

            return $this->options;
        }
    }

    /**
     * @return mixed
     */
    public function getModelForOptions()
    {
        return $this->modelForOptions;
    }

    /**
     * @return mixed
     */
    public function getQueryFunctionForModel()
    {
        return $this->queryFunctionForModel;
    }

    /**
     * @return mixed
     */
    public function getDefaultSelected()
    {
        return $this->defaultSelected;
    }

    /**
     * @param mixed $defaultSelected
     * @return Select
     */
    public function setDefaultSelected($defaultSelected)
    {
        $this->defaultSelected = $defaultSelected;
        return $this;
    }


    public function render($value = null)
    {
        $name = $this->getName();
        $label = $this->getLabel();
        $required = $this->getRequired();
        $readonly = $this->getReadonly();
        $options = $this->getOptions();
        $defaultSelected = $this->getDefaultSelected();

        return View::make('zeusAdmin::SectionBuilder/Form/Fields/select')
            ->with(compact('name', 'label', 'value', 'required', 'readonly', 'options', 'defaultSelected'));
    }
}