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

class Wysiwyg extends FormFieldBase
{
    private $name, $label, $value, $placeholder, $required, $readonly, $cols = 30, $rows = 10;

    public function __construct($name, $label)
    {
        $this->setName($name);
        $this->setLabel($label);
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
     */
    public function setValue($value): void
    {
        $this->value = $value;
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
     * @param mixed $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
        return $this;
    }

    /**
     * @param mixed $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
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

    /**
     * @return mixed
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->rows;
    }



    public function render($value = null)
    {
        $name = $this->getName();
        $label = $this->getLabel();
        $placeholder = $this->getPlaceholder();
        $required = $this->getRequired();
        $readonly = $this->getReadonly();
        $cols = $this->getCols();
        $rows = $this->getRows();
        $helpBlock = $this->getHelpBlock();
        $relatedName = $this->getRelatedName();
        $formIgnore = $this->getFormIgnore();

        return View::make('zeusAdmin::SectionBuilder/Form/Fields/wysiwyg')
            ->with(compact(
                'name',
                'label',
                'value',
                'placeholder',
                'required',
                'readonly',
                'cols',
                'rows',
                'helpBlock',
                'relatedName',
                'formIgnore'
            ));
    }
}