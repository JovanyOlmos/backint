<?php
namespace backint\update;

require_once(INSTALATION_PATH."backint/updates/IUpdate.php");

class Update202109202245 extends IUpdate {
    public function script() {
        return "CREATE TABLE products (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(20)
        );";
    }

    public function version() {
        return 1;
    }
}
?>