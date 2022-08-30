<?php

namespace backint\core\db\types;

use backint\core\db\types\iType;

abstract class iBoolean implements iType {

    public function hasQuotes(): bool {
        return false;
    }

}
?>