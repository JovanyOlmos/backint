<?php

namespace backint\core;

class JsProp {

    /**
     * JSON string property format
     * 
     * @var string
     */
    public static $TYPE_JSON_STRING = "D";

    /**
     * JSON Number property format
     * 
     * @var string
     */
    public static $TYPE_NUMBER = "N";

    /**
     * JSON Bool property format
     * 
     * @var string
     */
    public static $TYPE_BOOL = "B";

    /**
     * JSON String property format
     * 
     * @var string
     */
    public static $TYPE_STRING = "S";

    /**
     * JSON format
     * 
     * @var string
     */
    public static $TYPE_JSON = "J";

    /**
     * JSON Array property format
     * 
     * @var string
     */
    public static $TYPE_ARRAY = "A";

    /**
     * JSON null property format
     * 
     * @var string
     */
    public static $TYPE_NULL = "U";

    /**
     * Property name
     * 
     * @var string
     */
    private $name;

    /**
     * Property type
     * 
     * @var string
     */
    private $type;

    /**
     * Property value
     * 
     * @var mixed
     */
    private $value;

    /**
     * Init a JsPro
     * 
     * @param string $name
     * 
     * @param string $type
     * 
     * @param mixed $value
     */
    public function __construct($name, $type, $value) {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Get property name
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set property name
     * 
     * @param string
     * 
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get property type
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set property type
     * 
     * @return string
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * Get property value
     * 
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set property value
     * 
     * @param mixed
     * 
     * @return void
     */
    public function setValue($value) {
        $this->value = $value;
    }
}
?>