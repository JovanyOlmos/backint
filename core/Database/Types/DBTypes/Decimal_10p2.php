<?php
namespace backint\core\db\types;

class Decimal_10p2 extends iDecimal {

    private static iDecimal $instance;

    private function __construct() {
    }

    public static function instance(): iDecimal {
        return isset(self::$instance) ? self::$instance : self::$instance = new Decimal_10p2();
    }

    public function simple(): bool
    {
        return false;
    }

    public function size(): int {
        return 10;
    }
    public function decimalLength(): int
    {
        return 2;
    }
}
?>