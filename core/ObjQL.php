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
     * Main data source
     * 
     * @var string
     */
    private $source;

    /**
     * Number of records
     * 
     * @var int
     */
    public $num_records = 0;

    /**
     * Assoc array
     * 
     * @var array
     */
    public $result = array();

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
     * Add a field
     * 
     * @param OInterface $oInterface
     * 
     * @param IField $iField
     * 
     * @param string $alias
     * 
     * @return void
     */
    public function addField($oInterface, $iField, $alias = "") {
        if(strlen($this->query) > 0)
            $this->query .= ", ".$oInterface->getTableName().".".$iField->getColumnName();
        else
            $this->query .= "SELECT ".$oInterface->getTableName().".".$iField->getColumnName();
        if(strlen($alias) > 0)
            $this->query .= " AS '".$alias."'";
    }

    /**
     * Set main data source
     * 
     * @param OInterface $oInterface
     * 
     * @return void
     */
    public function setDataSource($oInterface) {
        $this->source = $oInterface->getTableName();
    }

    /**
     * Create a MySQL query
     * 
     * @param OInterface $oInterface
     * 
     * @param string $alias
     * 
     * @return void
     */
    public function addPKField($oInterface, $alias = "") {
        if(strlen($this->query) > 0)
            $this->query .= ", ".$oInterface->getTableName().".".$oInterface->getPKFieldName();
        else
            $this->query .= "SELECT ".$oInterface->getTableName().".".$oInterface->getPKFieldName();
        if(strlen($alias) > 0)
            $this->query .= " AS '".$alias."'";
    }

    /**
     * Get a JSON string from the internal query
     * 
     * @param SQLControllerHelper
     * 
     * @return string
     */
    public function buildJsonFromQuery($helper) {
        $this->query .= " FROM ".$this->source." "
            .$helper->getControllerUnion()->getJoin()
            .$helper->getControllerFilter()->getFilter()
            .$helper->getControllerOrder()->getSort();
        $dbObj = new DBObj();
        $doFetch = $dbObj->getFetchAssoc($this->query);
        $this->num_records = $dbObj->getNumRecords();
        $json = '';
        $jsonFields = '';
        if($this->num_records == 0) {
            $json .= '{}';
        } else {
            $firstJson = 0;
            while($row = $doFetch->fetch_assoc())
            {
                array_push($this->result, $row);
                if($firstJson > 0)
                    $jsonFields .= ',';
                $firstField = 0;
                $jsonFields .= '{';
                foreach ($this->fields as $fieldName) {
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
                            //SQLhelper $fieldName[2]
                            $fieldName[2]->getControllerFilter()->setDynamicValue($row[$fieldName[2]->getControllerFilter()->getDynamicFieldName()]);
                            $fieldName[2]->getControllerFilter()->buildDynamicFilter();
                            //ObjQL $fieldName[3]
                            $jsonFields .=  '"'.$fieldName[0].'":'.$fieldName[3]->buildJsonFromQuery($fieldName[2]);
                        }
                    }
                    $firstField++;
                }
                $jsonFields .= '}';
                $firstJson++;
            }
            if($this->num_records > 1)
                $json .= '['.$jsonFields.']';
            else {
                if($jsonFields == "")
                    $json .= '{}';
                else
                    $json .= $jsonFields;
            }
        }
        return $json;
    }

    /**
     * Get query
     * 
     * @return string
     */
    public function getQuery() {
        return $this->query;
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
                foreach ($this->fields as $fieldName) {
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