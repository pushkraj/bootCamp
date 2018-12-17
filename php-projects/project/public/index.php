<?php 
use Source\Config\Connection;
require(__DIR__."/../app/init.php");

class Test extends Connection {
    public function mytest() {
        $result = $this->query("SELECT * FROM tasks");
        print_r($result);
    }
}

$testObj = new Test();
$testObj->mytest();
