<?php
namespace backint\core;
require_once("./definitions/HTTP.php");
require_once("./definitions/SQLFormat.php");
class http {

    public function __construct() {

    }

    public function messageJSON($message) {
        $json = '{"message": "'.$message.'"}';
        return $json;
    }

    public function sendResponse($code, $json) {
        $sapi_type = php_sapi_name();
        if (substr($sapi_type, 0, 3) == 'cgi')
            header("Status: ".$code." ".HTTP_MESSAGE[$code]."");
        else
            header("HTTP/1.1 ".$code." ".HTTP_MESSAGE[$code]."");
        echo $json;
    }

    public function fillObjectFromJSON(OInterface $objInterface, $requestBody) {
        foreach ($objInterface->objectFields as $key => $iField) {
            if(array_key_exists($iField->getColumnName(), $requestBody)) {
                $iField->fieldValue = $requestBody[$iField->getColumnName()];
            } else {
                $iField->fieldValue = "";
            }
        }
        return $objInterface;
    }

    public function convertObjectToJSON(OInterface $objInterface) {
        $json = '{';
        $index = 0;
        if($objInterface->getIdObject() > 0)
            $json .= '"id": '.$objInterface->getIdObject().',';
        foreach ($objInterface->objectFields as $key => $iField) {
            if($index > 0)
                $json .= ', ';
            $json .= '"'.$iField->getColumnName().'":';
            if(SQL_FORMAT[$iField->getDBFormat()] && $iField->fieldValue != "null")
                $json .= '"'.$iField->fieldValue.'"';
            else
                $json .= $iField->fieldValue.'';
            $index++;
        }
        $json .= '}';
        return $json;
    }

    public function convertArrayObjectToJSON(array $objInterfaces) {
        $json = '[';
        $indexObjct = 0;
        foreach ($objInterfaces as $key => $objInterface) {
            if($indexObjct > 0)
                $json .= ',';
            $json .= $this->convertObjectToJSON($objInterface);
            $indexObjct++;
        }
        $json .= ']';
        return $json;
    }

    public function checkIfJSONIsComplete(OInterface $objInterface, $requestBody) {
        $exists = true;
        foreach ($objInterface->objectFields as $key => $iField) {
            if(!array_key_exists($iField->getColumnName(), $requestBody)) {
                $exists = false;
            }
        }
        return $exists;
    }
}
?>