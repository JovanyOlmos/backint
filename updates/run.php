<?php
require_once(__DIR__."/config.php");
require_once(__DIR__."/Update-Engine.php");

use backint\update\UpdateEngine;

UpdateEngine::runUpdates();
?>