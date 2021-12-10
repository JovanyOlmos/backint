<?php
require("./config/config.php");
require("./server/server.php");
require("./definitions/HTTP.php");
require("./definitions/policies.php");
require("./definitions/SQLFormat.php");
require("./config/auth-credentials.php");

use backint\server\server;

new server();
?>