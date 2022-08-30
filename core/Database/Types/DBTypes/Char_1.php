<?php
namespace backint\core\db\types;

class Char1 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new Char1();
    }

    public function length(): int {
        return 1;
    }

    public function autosize(): bool {
        return false;
    }

}
?>