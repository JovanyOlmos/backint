<?php
namespace backint\core\db\types;

use backint\core\db\types\iType;

abstract class iDateTime implements iType {

    public function hasQuotes(): bool {
        return true;
    }

    public abstract function format(): string;

}
?>