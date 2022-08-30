<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;

class MySQLJoinBuilder extends JoinBuilder {

    /**
     * Union type left
     * 
     * @var string
     */
    public static $LEFT = "LEFT JOIN";

    /**
     * Union type right
     * 
     * @var string
     */
    public static $RIGHT = "RIGHT JOIN";

    /**
     * Union type inner
     * 
     * @var string
     */
    public static $INNER = "INNER JOIN";

    /**
     * Union type outer
     * 
     * @var string
     */
    public static $OUTER = "FULL OUTER JOIN";

    public function __construct() {
        parent::__construct();
    }

    /**
     * Add a new data source and establish its relationship in the query
     * 
     * @param Model $firstModel
     * 
     * @param Model $secondModel
     * 
     * @param ModelField $firstFieldMatch
     * 
     * @param ModelField $secondFieldMatch
     * 
     * @param string $unionType
     * 
     * @return void
     */
    public function addJoin($firstModel, $secondModel, $firstFieldMatch, $secondFieldMatch, $unionType) {
        $this->setJoin(" ".$unionType." `".$secondModel->getModelName()
            ."` ON `".$firstModel->getModelName()."`.`"
            .$firstFieldMatch->getFieldName()."` = `".$secondModel->getModelName()."`.`".$secondFieldMatch->getFieldName()."` ");
    }
}
?>