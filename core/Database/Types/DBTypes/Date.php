<?php
namespace backint\core\db\types;

class Date extends iDateTime {

    private static iDateTime $instance;

    private function __construct() {
    }

    public static function instance(): iDateTime {
        return isset(self::$instance) ? self::$instance : self::$instance = new Date();
    }

    public function format(): string
    {
        return DateTimeFormatsEnum::Date;
    }

}
?>