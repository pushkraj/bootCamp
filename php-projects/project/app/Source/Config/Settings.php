<?php
namespace Source\Config;
class Settings {
    private $environment;
    private $settings;
    public function __construct( $environment = "development" ) {
        $this->environment = $environment;
        $this->setEnvSettings(); 
    }

    public function setEnvSettings() {
        try {
            $this->settings = json_decode(file_get_contents(__DIR__."/".$this->environment.".json"));
            if (!($this->settings instanceof \stdClass)) {
                throw new \Exception($this->environment.' Setting file not found');
            }
        } catch( Exception $e) {
            error_log('Caught exception: '.$e->getMessage());
        }
    }

    public function getDatabaseSettings() {
        try {
            if (!($this->settings->database instanceof \stdClass)) {
                throw new \Exception($this->environment.' Database credentials missing');
            }
        } catch( Exception $e) {
            error_log('Caught exception: '.$e->getMessage());
        }
        return $this->settings->database;
    }

    public function getEmailSettings() {
        if ($this->settings->email instanceof \stdClass) {
            return $this->settings->email; 
        }
    }
}