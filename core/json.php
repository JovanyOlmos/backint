<?php
namespace backint\core;

use backint\core\OInterface;

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
            if($this->types[$key] === self::$TYPE_NULL)
                $json .= "null";
            if($this->types[$key] === self::$TYPE_STRING)
                $json .= '"'.$value.'"';
            if($this->types[$key] === self::$TYPE_NUMBER)
                $json .= ''.$value;
            if($this->types[$key] === self::$TYPE_JSON)
                $json .= ''.$value;
            if($this->types[$key] === self::$TYPE_ARRAY)
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
     * Parse a Json into an interface object
     * 
     * @param OInterface $objInterface
     * 
     * @param array $requestBody
     * 
     * @return OInterface
     */
    public static function fillObjectFromJSON($objInterface, $requestBody): OInterface {
        foreach ($objInterface->fields as $key => $iField) {
            if(array_key_exists($key, $requestBody)) {
                $iField->value = $requestBody[$key];
            } else {
                $iField->value = "";
            }
        }
        if(array_key_exists($objInterface->getPKFieldName(), $requestBody))
            $objInterface->setPKValue($requestBody[$objInterface->getPKFieldName()]);
        return $objInterface;
    }

    /**
     * Convert an OInterface into a JSON
     * 
     * @param OInterface $objInterface
     * 
     * @return string
     */
    public static function convertObjectToJSON($objInterface): string {
        $json = '{';
        $index = 0;
        if($objInterface->getPKValue() > 0) {
            $json .= '"'.$objInterface->getPKFieldName().'": '.$objInterface->getPKValue().',';
            foreach ($objInterface->fields as $key => $iField) {
                if($index > 0)
                    $json .= ', ';
                $json .= '"'.$key.'":'.self::setJSONPropertyFormat($iField);
                $index++;
            }
        }
        $json .= '}';
        return $json;
    }

    /**
     * Convert an object's array into a JSON
     * 
     * @param OInterface $objInterfaces
     * 
     * @return string
     */
    public static function convertArrayObjectToJSON($objInterfaces): string {
        $json = '[';
        $indexObjct = 0;
        foreach ($objInterfaces as $objInterface) {
            if($indexObjct > 0)
                $json .= ',';
            $json .= self::convertObjectToJSON($objInterface);
            $indexObjct++;
        }
        $json .= ']';
        return $json;
    }

    /**
     * Validate if a JSON and an OInterface have the same fields
     * 
     * @param OInterface $objInterface
     * 
     * @param array $requestBody
     * 
     * @return bool
     */
    public static function checkIfJSONIsComplete($objInterface, $requestBody): bool {
        $exists = true;
        foreach ($objInterface->fields as $key => $iField) {
            if(!array_key_exists($key, $requestBody)) {
                $exists = false;
            }
        }
        return $exists;
    }

    /**
     * Format value to valid JSON property format
     * 
     * @param IField
     * 
     * @return string
     */
    private static function setJSONPropertyFormat($iField) {
        if(SQL_FORMAT[$iField->getFormat()] && $iField->value != "null")
            return '"'.$iField->value.'"';
        if($iField->getFormat() == BOOLEAN)
        {
            if($iField->value)
                return 'true';
            return 'false';
        }
        return $iField->value.'';
    }
}
?>