<?php
namespace backint\core\db\types;

interface iType {
    
    public static function instance();

    public function hasQuotes(): bool;
}

?>