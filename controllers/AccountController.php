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
        public $UTelephone;
        public $UBirth;
        public $UCity;
        public $UAddress;
        public $UPostalCode;
        public $UProfilePicture = "default.svg";
        private $UAdmin;

        public function __construct($arr) {
            if(isset($arr["FoodistID"])) {
                $this->UID = $arr["FoodistID"];
                $this->UEmail = $arr["FoodistEmail"];
                $this->UFirstName = $arr["FoodistFirstName"];
                $this->ULastName = $arr["FoodistLastName"];
                $this->UTelephone = $arr["FoodistTelephone"];
                $this->UBirth = $arr["FoodistBirth"];
                $this->UCity = $arr["FoodistCity"];
                $this->UAddress = $arr["FoodistAddress"];
                $this->UPostalCode = $arr["FoodistPostalCode"];
                $this->UProfilePicture = $arr["FoodistImage"];
                $this->UAdmin = $arr["FoodistAdmin"];

                $this->authenticated = true;
                $this->DisplayName = "$this->UFirstName $this->ULastName";

                if($this->UPostalCode == 0) $this->UPostalCode = null;
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
            $_SESSION["FoodistTelephone"] = $arr["Telephone"];
            $_SESSION["FoodistBirth"] = $arr["Birth"];
            $_SESSION["FoodistCity"] = $arr["City"];
            $_SESSION["FoodistAddress"] = $arr["Address"];
            $_SESSION["FoodistPostalCode"] = $arr["Postal_Code"];
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

    class CompanyAccountHandler {
        public $authenticated = false;

        public $CID;
        public $CName;
        public $CIN;
        public $CEmail;

        public function __construct($arr) {
            if(isset($arr["CompanyID"])) {
                $this->CID = $arr["CompanyID"];
                $this->CName = $arr["CompanyName"];
                $this->CIN = $arr["CompanyIN"];
                $this->CEmail = $arr["CompanyEmail"];

                $this->authenticated = true;
            }
            return 1;
        }

        public function logout(string $urlRedirect) {
            session_destroy();
            die(header("Location: $urlRedirect"));
        }

        public function fetchLogin($arr) {
            if(!is_array($arr)) return 0;
            $this->authenticated = true;
            $this->CID = $arr["ID"];
            $_SESSION["CompanyID"] = $this->CID;
            $_SESSION["CompanyName"] = $arr["Name"];
            $_SESSION["CompanyIN"] = $arr["IdentificationNumber"];
            $_SESSION["CompanyEmail"] = $arr["Email"];
            return 1;
        }

        public function isLoggedIn() {
            return $this->authenticated;
        }
        public function redirectAuthenticated() {
            if($this->isLoggedIn()) return die(header("Location: ".SSL_COMPANY_PATH));
        }
        public function redirectUnauthenticated() {
            if(!$this->isLoggedIn()) return die(header("Location: ".SSL_APP_PATH));
        }
        public function disableUnauthenticated() {
            if(!$this->isLoggedIn()) return die('{"error_code":-664,"error_message":"Access Denied"}');
        }
        public function disableDirect($serverHeaders) {
            if(!$serverHeaders["HTTP_ORIGIN"]) return die(header("Location: ".SSL_APP_PATH));
            else if($serverHeaders["HTTP_ORIGIN"] != APP_ORIGIN) return die('{"error_code":-666,"error_message":"Access Denied"}');
        }
    }
?>