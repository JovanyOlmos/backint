# Backint
PHP Framework. Easier way to do an API.

## Get started
### Configuration
Inside `config` folder you can find a configuration php file. All framework's settings can be adjusted in this file.

#### URL
Standard route on Backint is `backint\` that means you can access to the server using the route: `https://my-domain.com/backint/some-route`. If you want to change this route you should edit `ROUTE_INDEX` with your new route.

#### DATABASE CONNECTION
You must define all SQL params on this section. Params name:
```
DATABASE_NAME
DATABASE_HOST
DATABASE_USER
DATABASE_PASSWORD
```

#### SECURITY
On this section you can configurate who can in and what they can do.

#### AUTH
Backint has a basic auth system. You can enable or disable this auth functionality. In case you want to use this auth method, you must to define your users on auth-credentials.

NOTE. This auth functionality is a server authentication and is not an user authentication.

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
backint-cmd is a tool developed on Python. This app will help you to create some objects in a easier and faster way. 
Commands are next:
```
Create a new api model (controller) object using an argument
> itk -g -m arg
Create a new inferface (model) object using an argument
> itk -g -i arg
Create an api model (controller) and an interface (model) using an argument
> itk -g -a arg
Create a new update object
> itk -g -u
Create getters and setters using an Interface Object by a name pased by argument
> itk -a arg
Show all options on the current level
> itk ?
```

### API Models (Controllers)
An API Model is the way that you can define the Backend Logic. Maybe you've heard about 'Controllers'. Well in Backint a Controller is called API Model. These API Models contain all CRUD functionality, and you can define any functions as you need, just be sure to define the route.

#### Here an example about API Model's structure
```
<?php
namespace backint\server\api;
require_once("./core/OController.php");
require_once("./core/ControllerHelper/SQLControllerHelper.php");
require_once("./core/http.php");
require_once("./core/ObjQL.php");
require_once("./interfaces/OIExample.php");
require_once("./core/APIModel.php");
require_once("./definitions/HTTP.php");

use backint\core\OController;
use backint\core\ControllerHelper\SQLControllerHelper;
use backint\interfaces\OIExample;
use backint\core\http;
use backint\core\ObjQL;
use backint\core\ControllerHelper\ControllerFilter;

class APIModelExample extends APIModel {

	private $oiExample;
	private $oController;
	private $http;

	public function __construct() {
		$this->oController = new OController();
		$this->oiExample = new OIExample("example", "id");
		$this->http = new http();
	}

	public function getById($params) {
		$helper = new SQLControllerHelper();
		$helper->getControllerFilter()->addPKFilter($this->oiExample->getPKFieldName(), $params[0]);
		$this->oiExample = $this->oController->selectSimple($this->oiExample, $helper);
		if($this->oiExample->getPKValue() > 0)
		{
			$json = $this->http->convertObjectToJSON($this->oiExample);
			$this->http->sendResponse(OK, $json);
		}
		else
		{
			$this->http->sendResponse(NO_CONTENT, $this->http->messageToJSON("Resource does not exist"));
		}
	}

	public function create($params, $requestBody) {
		$this->oiExample = $this->http->fillObjectFromJSON($this->oiExample, $requestBody);
		$err = $this->oController->insert($this->oiExample);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Created correctly"));
	}

	public function update($params, $requestBody) {
		$this->oiExample = $this->http->fillObjectFromJSON($this->oiExample, $requestBody);
		$err = $this->oController->update($this->oiExample);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Updated correctly"));
	}

	public function deleteById($params) {
		$this->oiExample->setPKValue($params[0]);
		$err = $this->oController->delete($this->oiExample);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Deleted correctly"));
	}
}
?>
```

### Interfaces
Did you heard about a Model?. In Backint a Model is called Interface. Here you should define your data table. This object will be the reference between your table in database and your controller.
An interface have some private vars (each per no id field in your database table). After that each var have to be initialized using addField method.
Finaly, you should build 'getter' and 'setter' functions.
IMPORTANT. Each var must to be named like its reference in the table from database.

#### Example
```
<?php
namespace backint\interfaces;
require_once("./core/OInterface.php");
require_once("./definitions/SQLFormat.php");
use backint\core\OInterface;

class OIExample extends OInterface {
    //itk autocomplete start
    private $fieldName, $fieldName2;
    //itk autocomplete end

    public function __construct(string $DBTableName, string $columnNameFromIdTable) {
        parent::__construct($DBTableName, $columnNameFromIdTable);
        $this->fieldName = $this->addField("fieldName", VARCHAR);
        $this->fieldName2 = $this->addField("fieldName2", DATETIME);
    }

    public function getFieldName() {
        return $this->fieldName;
    }

    public function getFieldName2() {
        return $this->fieldName2;
    }

    public function setFieldName($iField) {
        $this->fieldName = $iField;
    }

    public function setFieldName2($iField) {
        $this->fieldName2 = $iField;
    }
}
?>
```

### Routing
To add a new route you should added in its own path-definitions file. Routes in Backint are composed by a class name, a function name and a jwt field to able this feature.
NOTE. Routes from a same method cannot share a name, but two routes from diferent method can share the name.

#### Example (Adding a route)
```
"categories" => array(
    "class" => "APIModelCategories",
    "function" => "deleteById",
    "jwt" => true
),
```

### ObjQL
Did you hear about GraphQL?
Backint contains an option where you can define an array with all fields you need, after that write an SQL Sentence and get info on JSON ready to send.

To use an ObjQL you should define a JSON structure using PHP arrays.

#### Example
```
$this->objQL = new ObjQL(array(
            array("id", INT), 
            array("folio", VARCHAR)
        ));

$json = $this->objQL->getJSON("SELECT id, folio FROM fichas WHERE ".$params[0]." = ".$params[1].";");
```

Notice this technology is on beta version yet. You can find some errors and some limitations. We are working on it!.

### UPDATES
"Updates" is a way to keep the model updated. It works using an "update engine" who checks each Update and
try to apply in the database. This means you have not worry about miss a new change.
Just create a new Update object for each change or changes group. This Update object has to implement abstract class IUpdate. Each update object have always diferent name... Obvious reasons. We're recommending name them
like "UpdateYYYYMMDDHHmm" this way to name them could be useful.
An Update object has two methods and we have an example for you... Watch below.

```
\\You have to write your MySQL script right here
public function script() {
    return "CREATE TABLE products (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(20)
    );";
}

\\This means the update version. You have to init it always at 1
\\If you make a change and you want to run it again, just increase by 1.
public function version() {
    return 1;
}
```

How to run this?
First you have to declare your computer path on config.php file (inside updates folder)
Something like:

```
define("UPDATE_DB_NAME", "yourdatabase");
define("UPDATE_DB_HOST", "localhost");
define("UPDATE_DB_USER", "root");
define("UPDATE_DB_PASSWORD", "");
```

Once you have declared your values, you need to modify line 2 on run.php file. This time you need to specify where config.php is.

```
require_once("C:/xampp/htdocs/backint/updates/config.php");
```

Finally, just go to your php installation path and run `php C:\xampp\htdocs\backint\updates\run.php`. Or you can declare the path of php like global enviroment and run the code in any location.
Note: Change the path according to your own path.

Created by Interik Team!