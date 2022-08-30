<?php
namespace backint\core\db\types;

class LongString1000 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new LongString1000();
    }

    public function length(): int {
        return 1000;
    }

    public function autosize(): bool {
        return true;
    }

}
?>