<?php
namespace backint\core\db\types;

class LongString500 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new LongString500();
    }

    public function length(): int {
        return 500;
    }

    public function autosize(): bool {
        return true;
    }

}
?>