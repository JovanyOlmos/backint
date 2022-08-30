<?php
namespace backint\core\db\types;

use backint\core\db\types\iType;

abstract class iString implements iType {
    
    public function hasQuotes(): bool {
        return true;
    }

    public abstract function length(): int;

    public abstract function autosize(): bool;
}

?>