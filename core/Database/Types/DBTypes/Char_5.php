<?php
namespace backint\core\db\types;

class Char5 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new Char5();
    }

    public function length(): int {
        return 5;
    }

    public function autosize(): bool {
        return false;
    }

}
?>