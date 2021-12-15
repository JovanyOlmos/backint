<?php
namespace backint\core;

interface iDBObj {

    public function getFetchAssoc($query);

    public function doQuery($query);

    public function getNumRecords();
}
?>