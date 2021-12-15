# Backint
PHP Framework. Easier way to do an API.

## Get started
### Configuration
First step is configurate your server. You can make all settings on 'Configuration' class.


### USING backint.py
backint.py is a tool developed on Python. This app will help you to create some objects in a easier and faster way. 
Commands are next:
```
Create a new controller using an argument
> itk -g -c arg
Create a new model using an argument
> itk -g -m arg
Create a controller and a model using an argument
> itk -g -a arg
Create a new update object
> itk -g -u
Create getters and setters using a model by a name pased by argument
> itk -a arg
Show all options on the current level
> itk ?
```

### Controllers
A controller is the way that you can define the Backend Logic. These controllers contain all CRUD functionality, and you can define any functions as you need, just be sure to define the route.

#### Here an example about Controller's structure
```
<?php
namespace backint\app\controllers;

use backint\core\QuickQuery;
use backint\core\QueryBuilder;
use backint\models\ModelTest;
use backint\core\Http;
use backint\core\Json;
use backint\core\ControllerBase;
use backint\core\ObjQL;

class ControllerTest extends ControllerBase {

	private $modelTest;

	private QuickQuery $quickQuery;

	public function __construct(QuickQuery $_quickQuery) {
		$this->modelTest = new ModelTest();
		$this->quickQuery = $_quickQuery;
		$this->setRouteSettings("GET", "get_by_id", false);
		$this->setRouteSettings("POST", "create", false);
		$this->setRouteSettings("PUT", "update", false);
		$this->setRouteSettings("DELETE", "delete_by_id", false);
	}

	/**
	 * Get a record passing by param an id
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function get_by_id($params) {
		$builder = new QueryBuilder();
		$builder->where()->addPKFilter($this->modelTest->getPKFieldName(), $params[0]);
		$this->modelTest = $this->quickQuery->selectSimple($this->modelTest, $builder);
		if(!is_null($this->modelTest) && $this->modelTest->getPKValue() > 0)
		{
			$json = Json::convertObjectToJSON($this->modelTest);
			Http::sendResponse(Http::OK, $json);
		}
		else
		{
			Http::sendResponse(Http::NO_CONTENT);
		}
	}

	/**
	 * Insert a new record into database passing by body the info
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function create($params, $requestBody) {
		$this->modelTest = Json::fillObjectFromJSON($this->modelTest, $requestBody);
		$err = $this->quickQuery->insert($this->modelTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Created correctly"));
	}

	/**
	 * Update an existing record passing by body all information
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function update($params, $requestBody) {
		$this->modelTest = Json::fillObjectFromJSON($this->modelTest, $requestBody);
		$err = $this->quickQuery->update($this->modelTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Updated correctly"));
	}

	/**
	 * Delete a record passing by param an id
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function delete_by_id($params) {
		$this->modelTest->setPKValue($params[0]);
		$err = $this->quickQuery->delete($this->modelTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Deleted correctly"));
	}
}
?>
```

### Models
Did you heard about a Model?. Here you should define your structure data table. This object will be the reference between your table in database and your controller.
A model have some private vars (each per no id field in your database table). After that each var have to be initialized using addField method.
Finaly, you should build 'getter' and 'setter' functions.
IMPORTANT. Each var must to be named like its reference in the table from database.

#### Model example
```
<?php
namespace backint\models;

use backint\core\Model;
use SQL;

class ModelTest extends Model {
	//itk autocomplete start
	private $name, $default_date;
	//itk autocomplete end

	public function __construct() {
		parent::__construct();
		$this->setTableName("test");
		$this->setPKFieldName("id");
		$this->name = $this->addField("name", SQL::VARCHAR);
		$this->default_date = $this->addField("default_date", SQL::DATE, "NOW()");
	}

	public function getName(){
		return $this->name;
	}

	public function setName($field){
		$this->name = $field;
	}

	public function getDefault_date(){
		return $this->default_date;
	}

	public function setDefault_date($field){
		$this->default_date = $field;
	}

}
?>
```

### Routing
To add a new route you should added in its own path-definitions file. Routes in Backint are composed by a class name, a function name and a jwt field to able this feature.
NOTE. Routes from a same method cannot share a name, but two routes from diferent method can share the name.

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