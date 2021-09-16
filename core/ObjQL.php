<?php
namespace backint\core;
require_once("./core/DBObj.php");
require_once("./definitions/SQLFormat.php");
use backint\core\DBObj;

class ObjQL {
    /**
     * JSON structure in an array
     * 
     * @var array
     */
    private $fields;

    /**
     * Constructor
     * 
     * @param array $fields Json array
     */
    public function __construct($fields) {
        $this->fields = $fields;
    }

    /**
     * Get a JSON string from a MySQL query
     * 
     * @param string
     */
    public function getJSON($SQLSentence) {
        $dbObj = new DBObj();
        $doFetch = $dbObj->getFetchAssoc($SQLSentence);
        $json = '';
        $jsonFields = '';
        if($doFetch == null) {
            $json .= '{ "data": null }';
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
                    if($fieldName[1] != JSON)
                    {
                        if(SQL_FORMAT[$fieldName[1]])
                        {
                            $jsonFields .= '"'.$fieldName[0].'":'.'"'.$row[$fieldName[0]].'"';
                        }
                        else
                        { 
                            if($row[$fieldName[0]] == null)
                                $jsonFields .= '"'.$fieldName[0].'":'.'null';
                            else
                                $jsonFields .= '"'.$fieldName[0].'":'.''.$row[$fieldName[0]].'';
                        }
                    }
                    else
                    {
                        if($fieldName[1] == JSON)
                        {
                            if(array_key_exists(4, $fieldName))
                                $fieldName[3] .= $row[$fieldName[4]];
                            $jsonFields .=  '"'.$fieldName[0].'":'.$fieldName[2]->getJSON($fieldName[3]);
                        }
                    }
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