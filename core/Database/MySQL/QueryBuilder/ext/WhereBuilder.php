<?php
namespace backint\core\db\builder;

use backint\core\db\types\iDateTime;
use backint\core\Model;
use backint\core\ModelField;

class MySQLWhereBuilder extends WhereBuilder {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->concatFilter(" WHERE 1 ");
    }

    /**
     * Add a filter to Model Controller
     * 
     * @param Model $model
     * 
     * @param ModelField $field
     * 
     * @param string $operator
     * 
     * @param mixed $value
     * 
     * @return void
     */
    public function addFilter($model, $field, $operator, $value) {
        $this->concatFilter(" AND `".$model->getModelName()."`.`".$field->getFieldName()."` ".$operator." ");
        if($field->getFormat()->hasQuotes()) {
            if(get_class($field->getFormat()) == iDateTime::class)
                $this->concatFilter(" DATE('".$value."')");
            else {
                if($operator == WhereBuilder::$LIKE) {
                    $this->concatFilter(" '%".$value."%' ");
                }
                else {
                    $this->concatFilter(" '".$value."' ");
                }
            }
        } else {
            $this->concatFilter($value);
        }
    }

    /**
     * Add a primary key filter to OController
     * 
     * @param Model
     * 
     * @param string $value
     * 
     * @return void
     */
    public function addPKFilter($model, $value) {
        $this->concatFilter(" AND `".$model->getModelName()."`.`".$model->getIdField()->getFieldName()."` = ".$value." ");
    }
}
?>