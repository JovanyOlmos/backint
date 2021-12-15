# Backint

Backint es un Framework para construir servicios RESTFull con PHP de una manera sencilla y a su vez con la posibilidad de crear servicios complejos.

## Requisitos

PHP 7 o superior.
MySQL (MariaDB) 10.4.14 o superior

## Configuración

Backint tiene distintas configuraciones rápidas donde se pueden definir desde la ruta del servidor hasta la autenticación. Estas configuraciones se encuentran en la clase Configuration dentro del archivo 'config.php'.

## JWT Auth

Backint tiene de manera nativa la autenticación por JWT, para utilizarla solo es necesario activarla desde la clase de configuración. También es posible definir una llave y un algoritmo de encriptación, los cuales serán usados para hacer dicha encriptación. Por último, también en la clase Configuration se puede establecer el tiempo en que este token será valido.

Una vez activada la autenticación con JWT se necesitará proporcionar el token mediante el header 'token'. Ojo la autenticación es activada en cada ruta mediante los ajustes de ruta en cada controlador. Es decir, cada ruta puede o no funcionar con la autenticación por JWT.

La función para crear los token es la función estatica `generateJWT` parte de la clase `Auth`.

## Modelos

Un modelo es una representación de una tabla en la base de datos, este modelo define cada uno de los campos de la tabla. El modelo es utilizado para ser el enlace entre el controlador y la estructura de la base de datos.

Ejemplo de un modelo.

```
class ModelTest extends Model {
	/*Dentro de este bloque debes definir tus variables para que el sistema CMD del Framework pueda hacer el autocompletado de las mismas*/
	//itk autocomplete start
	private $name, $default_date;
	//itk autocomplete end

	/*En el constuctor se asignan el nombre de la tabla, el nombre de la llave primaria y se asignan cada uno de los campos dentro de la tabla.
	Para agregar un nuevo campo se debe de escribir el nombre de la tabla, el tipo de dato y si existe algún valor como default para ese campo.*/
	public function __construct() {
		parent::__construct();
		$this->setTableName("test");
		$this->setPKFieldName("id");
		$this->name = $this->addField("name", SQL::VARCHAR);
		$this->default_date = $this->addField("default_date", SQL::DATE, "NOW()");
	}

	/*Getters y setters creados por el CMD del Framework*/
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
```

## Controladores

En los controladores se definen las funciones y rutas de dicho controlador. Cada controlador es representado por su nombre al hacer una petición Http. Por ejemplo `ControllerTest` corresponde a la ruta `/test/`.

El siguiente parámetro de la ruta es el nombre de la función dentro del controlador al cual hacemos una petición. Ejemplo `/get_by_id/`.

El controlador también es el encargado de ajustar los parametros de las rutas, como el JWT Auth. Estos ajustes se hacen en el constructor y es necesario definir un ajuste por cada ruta creada del controlador. Por ejemplo:

```
$this->setRouteSettings("GET", "get_by_id", false);
```

Para crear un controlador desde el CMD se hace uso del siguiente comando:
`itk -g -c arg`

## QuickQuery

La herramienta `QuickQuery` nos permite crear consultas básicas a partir de un modelo. Existen cinco tipos de consultas `insert, delete, update, selectSimple, selectMultiple` las cuales realizan consultas tipo CRUD en la base de datos usando como referencia un modelo.

Para el caso de las consultas tipo `SELECT`, se necesita proporcionar un `QueryBuilder` el cual indicará los filtros que llevará la consulta. Es importante mencionar que este tipo de consultas no aplicarán filtros tipo 'JOIN' aunque estos esten declarados en el `QueryBuilder`.

## QueryBuilder

Esta herramienta permite crear consultas más complejas incluyendo filtros, ordenamientos, limites y uniones entre tablas.

El funcionamiento es parecido a crear una consulta donde seleccionaremos la función que necesitemos y llenaremos los parametros si es que estos son necesarios.

Vease la carpeta `Extensions` para estudiar el funcionamiento completo de esta herramienta.

## ObjQL

Cuando se desarrollo el `QuickQuery` se encontro con la deficiencia de no poder crear respuestas complejas como podría ser un JSON que hace uso de información de diferentes tablas. Por ese motivo se creo ObjQL.

ObjQL es una herramienta que permite definir un JSON a partir de un arreglo, este arreglo contiene un alias y un tipo, el cual será asignado al JSON. 

Ejemplo.

```
$objQL = new ObjQL(array(
			array("id", INT),
			array("name", VARCHAR),
			array("supplier_id", INT)
		));
```

Para hacer uso de la herramienta, es necesario definir el ObjQL con el arreglo en el constructor. También es necesario agregar los campos del modelo al ObjQL.

Ejemplo.

```
$objQL->addPKField($this->oiSuppliers);
$objQL->addField($this->oiSuppliers, $this->oiSuppliers->getCompany_name(), "company_name");
```

Finalmente para obtener el JSON debemos de pasar un `QueryBuilder` como parametro de la función `buildJsonFromQuery($queryBuilder);` del ObjQL. Nota. También debe asignarse una tabla pivote en caso de que la consulta del ObjQL contenga varias tablas `$objQL->setDataSource($oiBrands);`.

Puede existir el caso donde tengamos que relacionar un filtro a una variable desconocida en un principio y la cual cambie. Por ejemplo, cuando necesitamos un JSON con información de otra tabla, pero esta información depende de algun campo de la primer tabla que desconocemos hasta que la primer consulta sea ejecutada. En ese caso es necesario usar un filtro dinamico en el `QueryBuilder` de la siguiente manera `$queryBuilder->where()->setDynamicFilter($oiBrands->getSupplier_id(), "id");` donde el primer campo es el campo del modelo a tomar como filtro y el segundo es el nombre con el cual se relacionará.