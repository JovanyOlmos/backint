<?php
namespace backint\core;

use backint\core\db\types\iType;

class ModelField {

    /**
     * Model field format
     * 
     * @var iType
     */
    private $format;

    /**
     * Field model name
     * 
     * @var string
     */
    private $fieldName;

    /**
     * Model linked
     * 
     * @var Model
     */
    private $modelLinked = null;

    /**
     * Field model linked
     * 
     * @var ModelField
     */
    private $modelFieldLinked = null;

    /**
     * Indicates if a field is mandatory or not
     * 
     * @var bool
     */
    private $mandatory = false;

    /**
     * Field model default value
     * 
     * @var mixed
     */
    private $default = null;

    /**
     * Value from field
     * 
     * @var mixed
     */
    public $value = null;

    /**
     * Constructor
     * 
     * @param string
     * 
     * @param string
     */
    public function __construct($fieldName, $format){
        $this->fieldName = $fieldName;
        $this->format = $format;
    }

    /**
     * Get type from field name
     * 
     * @return iType
     */
    public function getFormat(): iType {
        return $this->format;
    }

    /**
     * Get field name
     * 
     * @return string
     */
    public function getFieldName() {
        return $this->fieldName;
    }

    /**
     * Get default
     * 
     * @return mixed
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * Get model linked
     * 
     * @return Model
     */
    public function getModelLinked() {
        return $this->modelLinked;
    }

    /**
     * Get field model linked
     * 
     * @return ModelField
     */
    public function getModelFieldLinked() {
        return $this->modelFieldLinked;
    }

    /**
     * Set field type
     * 
     * @param iType
     */
    public function setFormat(iType $type) {
        $this->format = $type;
    }

    /**
     * Set field name
     * 
     * @param string
     */
    public function setFieldName($name) {
        $this->fieldName = $name;
    }

    /**
     * Set default value
     * 
     * @param mixed
     */
    public function setDefault($default) {
        $this->default = $default;
    }

    /**
     * Set model linked
     * 
     * @param Model
     */
    public function setModelLinked(Model $model) {
        $this->modelLinked = $model;
    }

    /**
     * Set model field linked
     * 
     * @param ModelField
     */
    public function setModelFieldLinked(ModelField $modelField) {
        $this->modelFieldLinked = $modelField;
    }

    /**
     * Get mandatory
     * 
     * @return bool
     */
    public function getMandatory() {
        return $this->mandatory;
    }

    /**
     * Set mandatory
     * 
     * @param bool
     */
    public function setMandatory($mandatory) {
        $this->mandatory = $mandatory;
    }
}
?>