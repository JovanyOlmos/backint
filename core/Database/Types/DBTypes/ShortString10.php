<?php
namespace backint\core\db\types;

class ShortString10 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new ShortString10();
    }

    public function length(): int {
        return 10;
    }

    public function autosize(): bool {
        return true;
    }

}
?>