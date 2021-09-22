<?php
namespace backint\update;

require_once("./updates/IUpdate.php");

class Update202109202245 extends IUpdate {
    public function script() {
        return "ALTER TABLE x ADD COLUMN y INT;";
    }

    public function version() {
        return 1;
    }
}
?>