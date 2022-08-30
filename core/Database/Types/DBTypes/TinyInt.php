<?php
namespace backint\core\db\types;

class TinyInt extends iNumber {

    private static iNumber $instance;

    private function __construct() {
    }

    public static function instance(): iNumber {
        return isset(self::$instance) ? self::$instance : self::$instance = new TinyInt();
    }

    public function size(): string {
        return NumberSizeEnum::TINY;
    }

    public function unsigned(): bool {
        return false;
    }
}
?>