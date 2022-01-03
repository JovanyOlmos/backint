<?php
namespace backint\core;

use backint\core\Model;
use SQL;

class Json {

    /**
     * JSON Number property format
     * 
     * @var string "N"
     */
    public static $TYPE_NUMBER = "N";

    /**
     * JSON String property format
     * 
     * @var string "S"
     */
    public static $TYPE_STRING = "S";

    /**
     * JSON format
     * 
     * @var string "J"
     */
    public static $TYPE_JSON = "J";

    /**
     * JSON Array property format
     * 
     * @var string "A"
     */
    public static $TYPE_ARRAY = "A";

    /**
     * JSON null property format
     * 
     * @var string "U"
     */
    public static $TYPE_NULL = "U";

    /**
     * JSON
     * 
     * @var array
     */
    private $json;

    /**
     * JSON property types
     * 
     * @var array
     */
    private $structure;

    /**
     * Init a new JSON
     */
    public function __construct() {
        $json = array();
        $structure = array();
    }

    /**
     * Add or set a JSON property
     * 
     * @param string $propertyName
     * 
     * @param string $propertyType
     * 
     * @param any $propertyValue
     * 
     * @return void
     */
    public function setProperty($propertyName, $propertyType, $propertyValue = null) {
        $this->json[$propertyName] = $propertyValue;
        $this->structure[$propertyName] = $propertyType;
    }

    /**
     * Get the builded JSON
     * 
     * @return string
     */
    public function getJsonString() {
        return $this->deserializeArray($this->json);
    }

    /**
     * Convert an assoc array in a JSON string
     * 
     * @param array
     * 
     * @return string
     */
    private function deserializeArray($array) {
        $json = "{";
        foreach ($array as $key => $value) {
            if(strlen($json) > 2)
                $json .= ",";
            $json .= '"'.$key.'":';
            if($this->structure[$key] === self::$TYPE_NULL)
                $json .= "null";
            if($this->structure[$key] === self::$TYPE_STRING)
                $json .= '"'.$value.'"';
            if($this->structure[$key] === self::$TYPE_NUMBER)
                $json .= ''.$value;
            if($this->structure[$key] === self::$TYPE_JSON)
                $json .= ''.$value;
            if($this->structure[$key] === self::$TYPE_ARRAY)
                $json .= '['.$this->deserializeArray($value).']';
        }
        $json .= "}";
        return $json;
    }
    
    /**
     * Convert a string into a JSON with a message property
     * 
     * @param string $message
     * 
     * @return string
     */
    public static function messageToJSON($message) {
        return '{"message": "'.$message.'"}';
    }

    /**
     * Parse a Json into a model object
     * 
     * @param Model $objModel
     * 
     * @param array $requestBody
     * 
     * @return Model
     */
    public static function fillObjectFromJSON($objModel, $requestBody): Model {
        foreach ($objModel->fields as $key => $field) {
            if(array_key_exists($key, $requestBody)) {
                $field->value = $requestBody[$key];
            } else {
                $field->value = "";
            }
        }
        if(array_key_exists($objModel->getPKFieldName(), $requestBody))
            $objModel->setPKValue($requestBody[$objModel->getPKFieldName()]);
        return $objModel;
    }

    /**
     * Convert a Model into a JSON
     * 
     * @param Model $objModel
     * 
     * @return string
     */
    public static function convertObjectToJSON($objModel): string {
        $json = '{';
        $index = 0;
        if($objModel->getPKValue() > 0) {
            $json .= '"'.$objModel->getPKFieldName().'": '.$objModel->getPKValue().',';
            foreach ($objModel->fields as $key => $field) {
                if($index > 0)
                    $json .= ', ';
                $json .= '"'.$key.'":'.self::setJSONPropertyFormat($field);
                $index++;
            }
        }
        $json .= '}';
        return $json;
    }

    /**
     * Convert an object's array into a JSON
     * 
     * @param Model $objModel
     * 
     * @return string
     */
    public static function convertArrayObjectToJSON($objModels): string {
        $json = '[';
        $indexObjct = 0;
        foreach ($objModels as $objModel) {
            if($indexObjct > 0)
                $json .= ',';
            $json .= self::convertObjectToJSON($objModel);
            $indexObjct++;
        }
        $json .= ']';
        return $json;
    }

    /**
     * Validate if a JSON and a Model Object have the same fields
     * 
     * @param Model $objModel
     * 
     * @param array $requestBody
     * 
     * @return bool
     */
    public static function checkIfJSONIsComplete($objModel, $requestBody): bool {
        $exists = true;
        foreach ($objModel->fields as $key => $field) {
            if(!array_key_exists($key, $requestBody)) {
                $exists = false;
            }
        }
        return $exists;
    }

    /**
     * Format value to valid JSON property format
     * 
     * @param ModelField
     * 
     * @return string
     */
    private static function setJSONPropertyFormat($modelField) {
        if(SQL::SQL_FORMAT[$modelField->getFormat()] && $modelField->value != "null")
            return '"'.$modelField->value.'"';
        if($modelField->getFormat() == SQL::BOOLEAN)
        {
            if($modelField->value)
                return 'true';
            return 'false';
        }
        return $modelField->value.'';
    }
}
?>