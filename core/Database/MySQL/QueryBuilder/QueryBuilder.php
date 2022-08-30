<?php
namespace backint\core\db\builder;

class MySQLQueryBuilder extends QueryBuilder {

    public function __construct() {
        parent::__construct();
        $this->setSelect(new MySQLSelectBuilder());
        $this->setUpdate(new MySQLUpdateBuilder());
        $this->setDelete(new MySQLDeleteBuilder());
        $this->setInsert(new MySQLInsertBuilder());
    }

    public function restart() {
        $this->setSelect(new MySQLSelectBuilder());
        $this->setUpdate(new MySQLUpdateBuilder());
        $this->setDelete(new MySQLDeleteBuilder());
        $this->setInsert(new MySQLInsertBuilder());
    }
    
}
?>