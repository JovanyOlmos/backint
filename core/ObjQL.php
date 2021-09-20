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
     * MySQL query
     * 
     * @var string
     */
    private $query;

    /**
     * Constructor
     * 
     * @param array $fields Json array
     */
    public function __construct($fields) {
        $this->fields = $fields;
        $this->query = "";
    }

    /**
     * Create a MySQL query
     * 
     * @param OInterface $oInterface
     * 
     * @param IField $iField
     * 
     * @return void
     */
    public function addField($oInterface, $iField) {
        if(strlen($this->query) > 0)
            $this->query .= ", ".$oInterface->getTableName().".".$iField->getColumnName();
        else
            $this->query .= "SELECT ".$oInterface->getTableName().".".$iField->getColumnName();
    }

    /**
     * Get a JSON string from the internal query
     * 
     * @param SQLControllerHelper
     * 
     * @return string
     */
    public function buildJsonFromQuery($helper) {
        $this->query .= $helper->getControllerUnion()->getJoin()
            .$helper->getControllerFilter()->getFilter()
            .$helper->getControllerOrder()->getSort();
        $dbObj = new DBObj();
        $doFetch = $dbObj->getFetchAssoc($this->query);
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

    /**
     * Get a JSON string from a MySQL query
     * 
     * @deprecated
     * 
     * @param string
     * 
     * @return string
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