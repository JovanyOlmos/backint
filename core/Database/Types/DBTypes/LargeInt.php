<?php
namespace backint\core\db\types;

class LargeInt extends iNumber {

    private static iNumber $instance;

    private function __construct() {
    }

    public static function instance(): iNumber {
        return isset(self::$instance) ? self::$instance : self::$instance = new LargeInt();
    }

    public function size(): string {
        return NumberSizeEnum::LARGE;
    }

    public function unsigned(): bool {
        return false;
    }
}
?>