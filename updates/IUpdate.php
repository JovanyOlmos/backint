<?php
namespace backint\update;

abstract class IUpdate {
    abstract public function script();

    abstract public function version();
}
?>