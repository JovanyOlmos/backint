<?php
namespace backint\core\db\types;

class Boolean extends iBoolean {

    private static iBoolean $instance;

    private function __construct() {
    }

    public static function instance(): iBoolean {
        return isset(self::$instance) ? self::$instance : self::$instance = new Boolean();
    }

}
?>