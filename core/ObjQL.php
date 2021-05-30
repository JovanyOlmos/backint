<?php
namespace backint\core;
require_once("./core/DBObj.php");
require_once("./definitions/SQLFormat.php");
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
                    if(SQL_FORMAT[$fieldName[1]])
                        $jsonFields .= '"'.$fieldName[0].'":'.'"'.$row[$fieldName[0]].'"';
                    else 
                        $jsonFields .= '"'.$fieldName[0].'":'.''.$row[$fieldName[0]].'';
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