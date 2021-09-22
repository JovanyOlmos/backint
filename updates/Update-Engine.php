<?php
namespace backint\update;

use mysqli;
foreach (glob("./updates/updates/*.php") as $filename)
{
    require_once($filename);
}

class UpdateEngine {
    public function runUpdates() {
        $var = "";
        foreach(get_declared_classes() as $class)
        {
            if(strpos($class, "backint\\update\\") !== false && strpos($class, "backint\\update\\IUpdate") === false) {
                $object = new $class();
                if(is_subclass_of($object, "backint\\update\\IUpdate")) {
                    //$object->;
                    $this->checkLogs($object);
                }
            }
        }
        echo $var;
    }

    private function checkLogs($object) {
        echo get_class($object);
        $connection = new mysqli(HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE);
        $connection->query("SELECT * FROM update_logs WHERE update_name = 'Update';");
        if($result = mysqli_query($connection, "SELECT * FROM update_logs WHERE update_name = '".get_class($object)."';")) {
            $record = mysqli_fetch_assoc($result);
            echo $record["version"];
        } else {
            echo "No existe";
        }
    }
}
?>