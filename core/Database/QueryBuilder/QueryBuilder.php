<?php
namespace backint\core\db\builder;

abstract class QueryBuilder {


    private $select;

    private $update;

    private $delete;

    private $insert;

    /**
     * Class constructor
     */
    public function __construct() {
        
    }

    /**
     * Get select builder
     * 
     * @return SelectBuilder
     */
    public function select() {
        return $this->select;
    }

    /**
     * Get insert builder
     * 
     * @return InsertBuilder
     */
    public function insert() {
        return $this->insert;
    }

    /**
     * Get update builder
     * 
     * @return UpdateBuilder
     */
    public function update() {
        return $this->update;
    }

    /**
     * Get delete builder
     * 
     * @return DeleteBuilder
     */
    public function delete() {
        return $this->delete;
    }

    /**
     * Set select builder
     * 
     * @param SelectBuilder
     * 
     * @return void
     */
    public function setSelect(SelectBuilder $select) {
        $this->select = $select;
    }

    /**
     * Set insert builder
     * 
     * @param InsertBuilder
     * 
     * @return void
     */
    public function setInsert(InsertBuilder $insert) {
        $this->insert = $insert;
    }

    /**
     * Set update builder
     * 
     * @param UpdateBuilder
     * 
     * @return void
     */
    public function setUpdate(UpdateBuilder $update) {
        $this->update = $update;
    }

    /**
     * Set delete builder
     * 
     * @param DeleteBuilder
     * 
     * @return void
     */
    public function setDelete(DeleteBuilder $delete) {
        $this->delete = $delete;
    }

    /**
     * Restart filters
     * 
     * @return void
     */
    public abstract function restart();
    
}
?>