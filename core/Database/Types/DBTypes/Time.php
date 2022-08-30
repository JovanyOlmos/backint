<?php
namespace backint\core\db\types;

class Time extends iDateTime {

    private static iDateTime $instance;

    private function __construct() {
    }

    public static function instance(): iDateTime {
        return isset(self::$instance) ? self::$instance : self::$instance = new Time();
    }

    public function format(): string
    {
        return DateTimeFormatsEnum::Time;
    }

}
?>