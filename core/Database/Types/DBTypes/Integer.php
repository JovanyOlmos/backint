<?php
namespace backint\core\db\types;

class Integer extends iNumber {

    private static iNumber $instance;

    private function __construct() {
    }

    public static function instance(): iNumber {
        return isset(self::$instance) ? self::$instance : self::$instance = new Integer();
    }

    public function size(): string {
        return NumberSizeEnum::MEDIUM;
    }

    public function unsigned(): bool {
        return false;
    }
}
?>