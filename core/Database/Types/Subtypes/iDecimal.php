<?php
namespace backint\core\db\types;

use backint\core\db\types\iType;

abstract class iDecimal implements iType {

    public function hasQuotes(): bool
    {
        return false;
    }

    public abstract function simple(): bool;

    public abstract function size(): int;

    public abstract function decimalLength(): int;
    
}
?>