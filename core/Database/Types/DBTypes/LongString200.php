<?php
namespace backint\core\db\types;

class LongString200 extends iString {

    private static iString $instance;

    private function __construct() {
    }

    public static function instance(): iString {
        return isset(self::$instance) ? self::$instance : self::$instance = new LongString200();
    }

    public function length(): int {
        return 200;
    }

    public function autosize(): bool {
        return true;
    }

}
?>