<?php
namespace Source\Config;
use Source\Config\Settings as Settings;

class Connection {
    private $_connection;
    private static $_instance;
    
    public function __construct() {
        try {
            $settingObj = new Settings();
            $dbCredObj = $settingObj->getDatabaseSettings();
            $this->_connection = new \mysqli( $dbCredObj->server, $dbCredObj->user, $dbCredObj->password, $dbCredObj->database);
            if (mysqli_connect_errno()) {
                throw new \RuntimeException("Connect failed: %s\n", mysqli_connect_error());
            }
        } 
        catch (Exception $e) {
            error_log('Caught exception: '.$e->getMessage());
        }
    }

    /**
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }

	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
    }

    public static function query($query) {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $db = self::getInstance();
            $mysqli = $db->getConnection();
            $result = $mysqli->query($query);
            if (mysqli_error($mysqli)) {
                throw new \RuntimeException("SQL query failed\n");
            } 
            else {
                return $result;
            }
        }
        catch (Exception $e) {
            error_log('SQL query exception: '.$e->getMessage());
        }
    }
}