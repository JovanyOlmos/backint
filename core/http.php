<?php
namespace backint\core;
require_once("./definitions/HTTP.php");
require_once("./definitions/SQLFormat.php");

class Http {

    /**
     * Convert a string into a JSON with a message property
     * 
     * @param string $message
     * 
     * @return string
     */
    public static function messageToJSON($message): string {
        $json = '{"message": "'.$message.'"}';
        return $json;
    }

    /**
     * Send a HTTP response with a JSON as body and its respective code
     * 
     * @param int $code
     * 
     * @param string $json
     * 
     * @return void
     */
    public static function sendResponse($code, $json): void {
        $sapi_type = php_sapi_name();
        if (substr($sapi_type, 0, 3) == 'cgi')
            header("Status: ".$code." ".HTTP_MESSAGE[$code]."");
        else
            header("HTTP/1.1 ".$code." ".HTTP_MESSAGE[$code]."");
        echo $json;
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
                $json .= '"'.$key.'":';
                if(SQL_FORMAT[$iField->getFormat()] && $iField->value != "null")
                    $json .= '"'.$iField->value.'"';
                else
                {
                    if($iField->getFormat() == BOOLEAN)
                    {
                        if($iField->value)
                            $json .= 'true';
                        else
                            $json .= 'false';
                    }
                    else
                        $json .= $iField->value.'';
                }
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
            $json .= Http::convertObjectToJSON($objInterface);
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
}
?>