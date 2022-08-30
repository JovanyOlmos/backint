<?php
namespace backint\core\db\types;

use backint\core\db\types\iType;

abstract class iNumber implements iType {
    
    public function hasQuotes(): bool {
        return false;    
    }

    public abstract function size(): string;

    public abstract function unsigned(): bool;

}
?>