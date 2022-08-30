<?php
namespace backint\core\db\types;

class Double extends iDecimal {

    private static iDecimal $instance;

    private function __construct() {
    }

    public static function instance(): iDecimal {
        return isset(self::$instance) ? self::$instance : self::$instance = new Double();
    }

    public function simple(): bool
    {
        return true;
    }

    public function size(): int {
        return 0;
    }
    public function decimalLength(): int
    {
        return 0;
    }

}
?>