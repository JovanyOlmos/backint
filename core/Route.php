<?php
namespace backint\core;
require_once("./core/ErrObj.php");
require_once("./definitions/HTTP.php");

class Route {
    private int $num_params;
    private string $URL;
    private string $type;
    private $functionName;
    private $apiModel;

    public function __construct(string $type, string $URL, $apiModel, string $functionName) {
        $num_params = explode("/", $URL);
        $this->num_params = count($num_params);
        $this->type = $type;
        $this->URL = $URL;
        $this->apiModel = $apiModel;
        $this->functionName = $functionName;
    }

    public function executeProcess($params, $requestBody) {
        $fun = $this->functionName;
        try {
            if($requestBody != null)
                $this->apiModel->$fun($params, $requestBody);
            else
                $this->apiModel->$fun($params);
        } catch (\Throwable $th) {
            $err = new ErrObj("Undefined method for this route on server", INTERNAL_SERVER_ERROR);
            $err->sendError();
        }
    }

    public function getNumParams() {
        return $this->num_params;
    }

    public function getURL() {
        return $this->URL;
    }

    public function getType() {
        return $this->type;
    }

    public function setNumParams(int $num_params) {
        $this->num_params = $num_params;
    }

    public function setURL(string $URL) {
        $this->URL = $URL;
    }

    public function setType(string $type) {
        $this->type = $type;
    }
}
?>