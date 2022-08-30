<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;

abstract class GroupBuilder {

    /**
     * @var Model
     */
    private $model = null;

    /**
     * @var ModelField
     */
    private $modelField = null;

    /**
     * Constructor
     */
    public function __construct() {
        
    }

    /**
     * Set group by sentence
     * 
     * @param Model
     * 
     * @param ModelField
     * 
     * @return void
     */
    public function setGroupBy($model, $modelField) {
        $this->model = $model;
        $this->modelField = $modelField;
    }

    /**
     * Get model of group by
     * 
     * @return Model
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * Get field of group by
     * 
     * @return ModelField
     */
    public function getField() {
        return $this->modelField;
    }

    /**
     * Get group by sentence
     * 
     * @return string
     */
    public abstract function getGroupBy(): string;

}
?>