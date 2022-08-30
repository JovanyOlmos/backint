<?php
namespace backint\migrator;

use backint\core\Model;

interface iMigrator {

    public static function createTable(Model $model);

    public static function alterTable(Model $model);

}
?>