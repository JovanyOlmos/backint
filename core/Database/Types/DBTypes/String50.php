<?php
namespace backint\core\db\types;

class String50 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new String50();
    }

    public function length(): int {
        return 50;
    }

    public function autosize(): bool {
        return true;
    }

}
?>