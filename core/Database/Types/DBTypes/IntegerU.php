<?php
namespace backint\core\db\types;

class IntegerU extends iNumber {

    private static iNumber $instance;

    private function __construct() {
    }

    public static function instance(): iNumber {
        return isset(self::$instance) ? self::$instance : self::$instance = new IntegerU();
    }

    public function size(): string {
        return NumberSizeEnum::MEDIUM;
    }

    public function unsigned(): bool {
        return true;
    }
}
?>