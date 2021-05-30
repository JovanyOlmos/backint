# Backint
PHP Framework. Easier way to do an API.

## Get started
### Configuration
Inside `config` folder you can find a configuration php file. All settings framework can adjusted by this file.

#### Folder structure
Once downloaded please add a folder called `api-models` inside `server` folder. Add another folder called `interfaces` on root. Create your APIModel and Interface Objects on their respective folders.

#### URL
Standard route on Backint is `backint\` that means you can access to the server using the route: `https://my-domain.com/backint/some-route`. If you want to change this route you must edit `ROUTE_INDEX` with your new route.

#### CONFIGURATION TABLES
Backint was design thinking about building a webpage using backend data. You can configurate all params about the configuration tables.

`TABLE_CONFIG_PREFIX` define a prefix por configuration tables, for example `config` means you will have tables whose name will be 
> config_example

`ROUTE_CONFIG` define the route where you are going to get configuration tables from database. For example, if you define `ROUTE_CONFIG` as `config` your route will be
> https://my-domain.com/backint/config/tableName

`TABLE_CONFIG_STRUCTURE` define whole table's structure. This config params is an array with name, type and size. You should define here how you can create your configuration tables.


#### DATABASE CONNECTION
You must define all SQL params on this section. Params name:
```
DATABASE
HOST
DATABASE_USER
DATABASE_PASSWORD
```

#### SECURITY
On this section you can configurate who can in and what they can do.

#### AUTH
Backint has a basic auth system. You can enable or disable this auth. In case you want to use this auth method you must to define your users on auth-credentials.

### Example
```
define("AUTH", array(
    array(
        "username" => "lord",
        "password" => "123",
        "level" => READ
    )
));
```

### USING backint-cmd.py
backint-cmd is a tool developed on Python. This app will help you to create some objects in a easier and faster way. Some commands are:
```
Create a new api model object using an argument
> itk -g -m arg
Create a new inferface object using an argument
> itk -g -i arg
Create a MySQL sentence to create a configuration table using an argument and `TABLE_CONFIG_STRUCTURE` configuration
> itk -g -c arg
Create a api model and a interface model using an argument
> itk -g -a arg
Create getters and setters using an Interface Object by a name pased by argument
> itk -a arg
Show all options on level command
> itk ?
```

### API Models
An API Model is the way that you can define the Backend Logic. Inside this file you must define your functions like a info save or get.

#### Example
```
<?php
//Dependencies
namespace backint\server\api;
use backint\core\OController;
use backint\interfaces\OIFichas;
use backint\core\http;
require_once("./core/OController.php");
require_once("./core/http.php");
require_once("./interfaces/OIFichas.php");
require_once("./definitions/HTTP.php");

class APIModelFichas {
    //Declarations
    private OIFichas $oiFichas; //Interface object
    private OController $oController; //Controller between Interface Object and MySQL process
    private http $http; //Helper to JSON and REST comunications

    public function __construct() {
        $this->oController = new OController();
        $this->oiFichas = new OIFichas("fichas", "id"); /*First arg (database tables name), Second arg (Tables id field)*/
        $this->http = new http();
    }

    //Function get (Notice $params is an array, it contains all params passed by URI)
    public function getFichaById($params) {
        $this->oiFichas = $this->oController->fillObjInterfaceById($params[0], $this->oiFichas); /*Arg 1 (Id), Arg 2 (Interface Object)*/
        if($this->oiFichas->getIdObject() > 0)
        {
            $json = $this->http->convertObjectToJSON($this->oiFichas);
            $this->http->sendResponse(OK, $json);
        }
        else
        {
            $this->http->sendResponse(NO_CONTENT, $this->http->messageJSON("Resource does not exist"));
        }
    }

    public function getFichasByIdPersona($params) {
        $arrayFichas = $this->oController->getObjInterfaceArrayByForeignId($params[1], $params[0], $this->oiFichas);
        $json = $this->http->convertArrayObjectToJSON($arrayFichas);
        $this->http->sendResponse(OK, $json);
    }

    /*Follow API REST sctructure just post and put functions can have a request body. This request body must be a JSON*/
    public function postFicha($params, $requestBody) {
        $this->oiFichas = $this->http->fillObjectFromJSON($this->oiFichas, $requestBody);
        $err = $this->oController->register($this->oiFichas);
        if($err->hasErrors())
            $err->sendError();
        else 
            $this->http->sendResponse(CREATED, $this->http->messageJSON("Ficha created correctly"));
    }

    public function putFicha($params, $requestBody) {
        $this->oiFichas = $this->http->fillObjectFromJSON($this->oiFichas, $requestBody);
        $this->oiFichas->setIdObject($requestBody[$this->oiFichas->getColumnNameFromIdTable()]);
        $err = $this->oController->put($this->oiFichas);
        if($err->hasErrors())
            $err->sendError();
        else 
            $this->http->sendResponse(CREATED, $this->http->messageJSON("Ficha created correctly"));
    }

    public function deleteFicha($params) {
        $this->oiFichas->setIdObject($params[0]);
        $err = $this->oController->delete($this->oiFichas);
        if($err->hasErrors())
            $err->sendError();
        else 
            $this->http->sendResponse(CREATED, $this->http->messageJSON("Ficha deleted correctly"));
    }
}
?>
```

### Interfaces
On this object is where we should declare a model to interact with a controller object.

#### Example
```
<?php
namespace backint\interfaces;
require_once("./core/OInterface.php");
require_once("./definitions/SQLFormat.php");
use backint\core\OInterface;
class OIFichas extends OInterface {
    private $folio, $fecha, $idpersona, $idusuario, $idsucursal, $idpoliza, $idoperacionfuente, 
        $efectivo, $cheques, $transferencia, $cancelada, $referencia, $idfactura, $pagada, $comentario,
        $nuevo, $masnuevo; //Declare here all table's fields

    public function __construct(string $DBTableName, string $columnNameFromIdTable) {
        parent::__construct($DBTableName, $columnNameFromIdTable);
        //Init fields (Notice these fields are IFields objects)
        $this->folio = $this->addField("folio", VARCHAR); //(Database field name, SQL Type)
        $this->fecha = $this->addField("fecha", DATETIME);
        $this->idpersona = $this->addField("idpersona", INT);
        $this->idusuario = $this->addField("idusuario", INT);
        $this->idsucursal = $this->addField("idsucursal", INT);
        $this->idpoliza = $this->addField("idpoliza", INT);
        $this->idoperacionfuente = $this->addField("idoperacionfuente", INT);
        $this->efectivo = $this->addField("efectivo", DECIMAL);
        $this->cheques = $this->addField("cheques", DECIMAL);
        $this->transferencia = $this->addField("transferencia", DECIMAL);
        $this->cancelada = $this->addField("cancelada", BOOLEAN);
        $this->referencia = $this->addField("referencia", VARCHAR);
        $this->idfactura = $this->addField("idfactura", INT);
        $this->pagada = $this->addField("pagada", BOOLEAN);
        $this->comentario = $this->addField("comentario", VARCHAR);
        $this->nuevo = $this->addField("nuevo", VARCHAR);
        $this->masnuevo = $this->addField("masnuevo", VARCHAR);
    }

    public function getFolio() {
        return $this->folio;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getIdPersona() {
        return $this->idpersona;
    }

    public function getIdUsuario() {
        return $this->idusuario;
    }

    public function getIdSucursal() {
        return $this->idsucursal;
    }

    public function getIdPoliza() {
        return $this->idpoliza;
    }

    public function getIdOperacionFuente() {
        return $this->idoperacionfuente;
    }

    public function getEfectivo() {
        return $this->efectivo;
    }

    public function getCheques() {
        return $this->cheques;
    }

    public function getTransferencia() {
        return $this->transferencia;
    }

    public function getCancelada() {
        return $this->cancelada;
    }

    public function getReferencia() {
        return $this->referencia;
    }

    public function getIdFactura() {
        return $this->idfactura;
    }

    public function getPagada() {
        return $this->pagada;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function getNuevo() {
        return $this->nuevo;
    }

    public function getMasNuevo() {
        return $this->masnuevo;
    }
}
?>
```

### Routing
Routing is the link where we can provide services.
To create a new route you only have to add your new route definition into an array on routes.php file.

#### Example
```
<?php
//Define your routes right here
define("ROUTES", array(
    array(
        "route" => "my-route",
        "type" => "GET",
        "class" => "APIModelSpecial",
        "function" => "getSentence"
    ),
));
?>
```

In addition, you must declare your API Model route like:
```
require_once("./server/api-models/APIModelSpecial.php");
```
On model-declarations.php file.

### ObjQL
Did you hear about GraphQL?
Backint contains an option where you can define an array with all fields you need, after that write an SQL Sentence and get info on JSON ready to send.

#### Example
```
$this->objQL = new ObjQL(array(
            array("id", INT), 
            array("folio", VARCHAR)
        ));

$json = $this->objQL->getJSON("SELECT id, folio FROM fichas WHERE ".$params[0]." = ".$params[1].";");
```

Notice this technology is on beta version yet. You can find some errors and some limitations. We are working on it!.

Created by Interik Team!