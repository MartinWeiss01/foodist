<?php
    require_once(dirname(__DIR__).'/config/defines.inc.php');
    session_start();

    class UserAccountHandler {
        public $authenticated = false;
        public $DisplayName = "Přihlásit se";
        public $UCart = array();

        public $UID;
        public $UEmail;
        public $UFirstName;
        public $ULastName;
        public $UProfilePicture = "default.svg";
        private $UAdmin;

        public function __construct($arr) {
            if(isset($arr["FoodistID"])) {
                $this->UID = $arr["FoodistID"];
                $this->UEmail = $arr["FoodistEmail"];
                $this->UFirstName = $arr["FoodistFirstName"];
                $this->ULastName = $arr["FoodistLastName"];
                $this->UProfilePicture = $arr["FoodistImage"];
                $this->UAdmin = $arr["FoodistAdmin"];

                $this->authenticated = true;
                $this->DisplayName = "$this->UFirstName $this->ULastName";
            }

            if(isset($arr["FoodistCart"])) $this->UCart = $arr["FoodistCart"];
            return 1;
        }

        public function logout(string $urlRedirect) {
            session_destroy();
            die(header("Location: $urlRedirect"));
        }

        public function fetchLogin($arr) {
            if(!is_array($arr)) return 0;
            $this->authenticated = true;
            $this->UID = $arr["ID"];
            $_SESSION["FoodistID"] = $this->UID;
            $_SESSION["FoodistEmail"] = $arr["Email"];
            $_SESSION["FoodistFirstName"] = $arr["First_Name"];
            $_SESSION["FoodistLastName"] = $arr["Last_Name"];
            $_SESSION["FoodistImage"] = $arr["Image"];
            $_SESSION["FoodistAdmin"] = $arr["Admin"];
            return 1;
        }

        public function updateUserCart() {
            $_SESSION["FoodistCart"] = $this->UCart;
        }

        public function isLoggedIn() {
            return $this->authenticated;
        }
        public function isAdmin() {
            return ($this->UAdmin > 0);
        }
        public function redirectAuthenticated() {
            if($this->isLoggedIn()) return die(header("Location: ".SSL_APP_PATH));
        }
        public function redirectUnauthenticated() {
            if(!$this->isLoggedIn()) return die(header("Location: ".SSL_APP_PATH));
        }
        public function redirectUnauthorized() {
            if(!$this->isAdmin()) return die(header("Location: ".SSL_APP_PATH));
        }
        public function disableUnauthorized() {
            if(!$this->isAdmin()) return die('{"error_code":-664,"error_message":"Access Denied"}');
        }
        public function disableDirect($serverHeaders) {
            if(!$serverHeaders["HTTP_ORIGIN"]) return die(header("Location: ".SSL_APP_PATH));
            else if($serverHeaders["HTTP_ORIGIN"] != APP_ORIGIN) return die('{"error_code":-666,"error_message":"Access Denied"}');
        }
    }
?>