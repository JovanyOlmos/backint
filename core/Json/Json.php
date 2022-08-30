<?php
namespace backint\core;

use backint\core\db\Defaults;
use backint\core\db\types\iBoolean;
use backint\core\Model;
use backint\core\JsProp;

class Json {

    /**
     * JSON
     * 
     * @var array
     */
    private $json;

    /**
     * Init a new JSON
     */
    public function __construct() {
        $this->json = array();
    }

    /**
     * Add or set a JSON property
     * 
     * @param string
     * 
     * @param string
     * 
     * @param mixed
     * 
     * @return void
     */
    public function addProperty($name, $type, $value) {
        $this->json[$name] = new JsProp($name, $type, $value);
    }

    /**
     * Get Json data into the object
     * 
     * @return array
     */
    public function getJsonData() {
        return $this->json;
    }

    /**
     * Get the builded JSON. Recieve a Json or an array of Json.
     * 
     * @param mixed
     * 
     * @return string
     */
    public static function deserializeJson($json) {
        $jsonString = "";
        if(is_array($json))
        {
            $jsonString .= "[";
            $index = 0;
            foreach ($json as $item) {
                if($index > 0)
                {
                    $jsonString .= ",";
                }
                $jsonString .= self::deserializeJson($item);
                $index++;
            }
            $jsonString .= "]";
            return $jsonString;
        }
        if(get_class($json) == Json::class)
        {
            $jsonString .= "{";
            $index = 0;
            foreach ($json->getJsonData() as $jsProp) {
                if($index > 0)
                {
                    $jsonString .= ",";
                }
                $jsonString .= self::deserializeJson($jsProp);
                $index++;
            }
            $jsonString .= "}";
            return $jsonString;
        }
        if(get_class($json) == JsProp::class)
        {
            $jsonString .= '"'.$json->getName().'":';
            if($json->getType() == JsProp::$TYPE_ARRAY)
            {
                $jsonString .= self::deserializeJson($json->getValue());
            }
            if($json->getType() == JsProp::$TYPE_JSON)
            {
                $jsonString .= self::deserializeJson($json->getValue());
            }
            if($json->getType() == JsProp::$TYPE_NUMBER)
            {
                $jsonString .= $json->getValue();
            }
            if($json->getType() == JsProp::$TYPE_JSON_STRING)
            {
                $jsonString .= $json->getValue();
            }
            if($json->getType() == JsProp::$TYPE_BOOL)
            {
                $jsonString .= $json->getValue() ? "true" : "false";
            }
            if($json->getType() == JsProp::$TYPE_STRING)
            {
                $jsonString .= '"'.$json->getValue().'"';
            }
            if($json->getType() == JsProp::$TYPE_NULL)
            {
                $jsonString .= 'null';
            }
        }
        return $jsonString;
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
     * @param Model
     * 
     * @param array
     * 
     * @param bool
     * 
     * @return Model
     */
    public static function fillObjectFromJSON($model, $requestBody, $useDefaults = false) {
        foreach ($model->fields as $key => $field) {
            $model->fields[$key]->value = array_key_exists($key, $requestBody) ? $requestBody[$key] : ($useDefaults ? $field->value : null);
        }
        if(!is_null($model->getIdField()) && array_key_exists($model->getIdField()->getFieldName(), $requestBody))
        {
            $model->getIdField()->value = $requestBody[$model->getIdField()->getFieldName()];
        }
        return $model;
    }

    /**
     * Convert a Model into a JSON
     * 
     * @param Model $objModel
     * 
     * @param bool $useDefaults
     * 
     * @return string
     */
    public static function convertModelToJSON($objModel, $useDefaults = false) {
        $json = new Json();
        if(!is_null($objModel->getIdField())) {
            $json->addProperty($objModel->getIdField()->getFieldName(), JsProp::$TYPE_NUMBER, $objModel->getIdField()->value);
        }
        foreach ($objModel->fields as $key => $field) {
            $json->addProperty($key, 
            ($field->getFormat()->hasQuotes()) ? JsProp::$TYPE_STRING 
            : (get_class($field->getFormat()) == iBoolean::class ? JsProp::$TYPE_BOOL 
            : JsProp::$TYPE_NUMBER), 
            $useDefaults && is_null($field->value) ? 
                (!is_null($field->getDefault()) && is_object($field->getDefault()) && get_class($field->getDefault()) == Defaults::class ?
                $field->getDefault()->value : $field->getDefault()) 
                : $field->value
            );
        }
        return Json::deserializeJson($json);
    }

    /**
     * Convert an model's array into a JSON
     * 
     * @param array $objModels
     * 
     * @return string
     */
    public static function convertArrayModelToJSON($objModels, $useDefaults = false) {
        $arrayJson = array();
        if(is_null($objModels))
        {
            return "[]";
        }
        foreach ($objModels as $key => $model) {
            $json = new Json();
            if(!is_null($model->getIdField())) {
                $json->addProperty($model->getIdField()->getFieldName(), JsProp::$TYPE_NUMBER, $model->getIdField()->value);
            }
            foreach ($model->fields as $key => $field) {
                $json->addProperty($key, 
                (($field->getFormat()->hasQuotes()) ? JsProp::$TYPE_STRING 
                : (get_class($field->getFormat()) == iBoolean::class ? JsProp::$TYPE_BOOL 
                : JsProp::$TYPE_NUMBER)), 
                $useDefaults && is_null($field->value) ? 
                (!is_null($field->getDefault()) && is_object($field->getDefault()) && get_class($field->getDefault()) == Defaults::class ?
                $field->getDefault()->value : $field->getDefault()) 
                : $field->value);
            }
            array_push($arrayJson, $json);
        }
        return Json::deserializeJson($arrayJson);
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
    public static function checkIfJSONIsComplete($objModel, $requestBody) {
        foreach ($objModel->fields as $key => $field) {
            if(!array_key_exists($key, $requestBody)) {
                return false;
            }
        }
        if(!is_null($objModel->getIdField()->value) && !array_key_exists($objModel->getIdField()->getFieldName(), $requestBody))
        {
            return false;
        }
        return true;
    }
}
?>