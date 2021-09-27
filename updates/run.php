<?php
require_once("C:/xampp/htdocs/backint/updates/config.php");
require_once(INSTALATION_PATH."backint/updates/Update-Engine.php");
use backint\update\UpdateEngine;
$initUpdate = new UpdateEngine();
$initUpdate->runUpdates();
?>