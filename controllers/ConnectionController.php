<?php
    define('TRUSTED', true);
    require_once(dirname(__DIR__).'/config/.config.inc.php');

    class ConnectionHandler {
        public $connection;
        public function __construct() {
            $this->connection = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
            if($this->connection->connect_error) $this->exitScript('{"error_code":-308,"error_message":"Connection Error"}');
            $this->connection->set_charset("utf8");
            return $this->connection;
        }

        public function callQuery($query) {
            $result = $this->connection->query($query) or $this->finishConnection('{"error_code":-309,"error_message":"Invalid Query"}');
            return $result;
        }

        public function callMultiQuery($query) {
            $result = $this->connection->multi_query($query) or $this->finishConnection('{"error_code":-310,"error_message":"Invalid Query"}');
            return $result;
        }

        public function closeConnection() {
            $this->connection->close();
        }

        public function finishConnection($response_message) {
            $this->closeConnection();
            $this->exitScript($response_message);
        }

        private function exitScript($response_message) {
            die($response_message);
        }
    }
?>