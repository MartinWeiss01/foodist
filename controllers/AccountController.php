<?php
    require_once('../config/.config.inc.php');
    session_start();

    class UserAccountHandler {
        private $authorized = false;
        public $UID;
        public $UFirstName;
        public $ULastName;
        public $UProfilePicture;

        public function __construct($arr) {
            if(isset($arr["FoodistID"])) {
                $this->authorized = true;
                $this->UID = $arr["FoodistID"];
                $this->UFirstName = $arr["FoodistFirstName"];
                $this->ULastName = $arr["FoodistLastName"];
                $this->UProfilePicture = $arr["FoodistImage"];
            }
            return 1;
        }

        public function redirectUnauthorized() {
            if(!($this->authorized)) return die(header("Location: ".URL_HOMEPAGE));
        }
        public function disableUnauthorized() {
            if(!($this->authorized)) return die('{"error_code":-666,"error_message":"Access Denied"}');
        }
    }
?>