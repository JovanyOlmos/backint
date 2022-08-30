<?php
namespace backint\core\db\types;

class String25 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new String25();
    }

    public function length(): int {
        return 25;
    }

    public function autosize(): bool {
        return true;
    }

}
?>