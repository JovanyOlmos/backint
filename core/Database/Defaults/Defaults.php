<?php
namespace backint\core\db;

class Defaults {

    public $value;

    public $useQuotes;

    public function __construct($value, $useQuotes) {
        $this->value = $value;
        $this->useQuotes = $useQuotes;
    }

    public static function set($value, $useQuotes): Defaults {
        return new Defaults($value, $useQuotes);
    }

}
?>