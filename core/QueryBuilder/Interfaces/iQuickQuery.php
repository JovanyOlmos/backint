<?php
namespace backint\core;

interface iQuickQuery {

    public function insert($model);

    public function delete($model);

    public function update($model);

    public function selectSimple($model, $queryBuilder);

    public function selectMultiple($model, $queryBuilder);
}
?>