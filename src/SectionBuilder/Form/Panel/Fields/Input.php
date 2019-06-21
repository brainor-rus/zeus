<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 02.10.2018
 * Time: 13:36
 */

namespace Zeus\Admin\SectionBuilder\Form\Panel\Fields;


use Illuminate\Support\Facades\View;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormFieldBase;

class Input extends FormFieldBase
{
    private $name, $label, $value, $placeholder, $required, $readonly, $type, $pattern;

    public function __construct($name, $label)
    {
        $this->setName($name);
        $this->setLabel($label);
        $this->setFormIgnore(false);
    }

    /**
     * @return mixed
     */
    public function getPattern()
    {
        return $this->pattern;
    }
    /**
     * @param mixed $pattern
     * @return Input
     */
    public function setPattern($pattern): Input
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
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
     * @return Input
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param mixed $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param mixed $readonly
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @param mixed $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
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
    public function getPlaceholder()
    {
        return $this->placeholder;
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

    public function render($value = null)
    {
        $name = $this->getName();
        $label = $this->getLabel();
        $placeholder = $this->getPlaceholder();
        $required = $this->getRequired();
        $readonly = $this->getReadonly();
        $type = $this->getType();
        $value = $value ?? $this->getValue();
        $pattern = $this->getPattern();
        $helpBlock = $this->getHelpBlock();
        $relatedName = $this->getRelatedName();
        $formIgnore = $this->getFormIgnore();
        $dataAttributes = $this->getDataAttributes();
        $classes = $this->getClasses();

        return View::make('zeusAdmin::SectionBuilder/Form/Fields/input')
            ->with(compact(
                'name',
                'label',
                'value',
                'placeholder',
                'required',
                'readonly',
                'type',
                'pattern',
                'helpBlock',
                'relatedName',
                'formIgnore',
                'dataAttributes',
                'classes'
            ));
    }
}