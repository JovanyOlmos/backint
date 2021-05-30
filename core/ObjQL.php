<?php
namespace backint\core;
require_once("./core/DBObj.php");
use backint\core\DBObj;
class ObjQL {
    private $fields;//Array

    public function __construct($fields) {
        $this->fields = $fields;
    }

    public function getJSON($SQLSentence) {
        $dbObj = new DBObj();
        $doFetch = $dbObj->getFetchAssoc($SQLSentence);
        $json = '';
        $jsonFields = '';
        if($doFetch == null) {
            $json .= '{}';
        } else {
            $json .= '{ "data": ';
            $firstJson = 0;
            $counterJsons = 0;
            while($row = $doFetch->fetch_assoc())
            {
                $counterJsons++;
                if($firstJson > 0)
                    $jsonFields .= ',';
                $firstField = 0;
                $jsonFields .= '{';
                foreach ($this->fields as $field => $fieldName) {
                    if($firstField > 0)
                        $jsonFields .= ',';
                    $jsonFields .= '"'.$fieldName.'":'.'"'.$row[$fieldName].'"';   
                    $firstField++;
                }
                $jsonFields .= '}';
                $firstJson++;
            }
            if($counterJsons > 1)
                $json .= '['.$jsonFields.']';
            else {
                if($jsonFields == "")
                    $json .= 'null';
                else
                    $json .= $jsonFields;
            }
            $json .= '}';
        }
        return $json;
    }
}
?>