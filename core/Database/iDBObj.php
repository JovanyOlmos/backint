<?php
namespace backint\core\db;

use backint\core\db\builder\DeleteBuilder;
use backint\core\db\builder\InsertBuilder;
use backint\core\db\builder\QueryBuilder;
use backint\core\db\builder\SelectBuilder;
use backint\core\db\builder\UpdateBuilder;

interface iDBObj {

    public function getFetchAssoc(SelectBuilder $query);

    public function doQueryPut(UpdateBuilder $query);

    public function doQueryPost(InsertBuilder $query);

    public function doQueryDelete(DeleteBuilder $query);

    public function getNumRecords();
    
}
?>