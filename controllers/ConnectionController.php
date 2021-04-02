<?php
    define('TRUSTED', true);
    require_once(dirname(__DIR__).'/config/.config.inc.php');

    class ConnectionHandler {
        public $connection;
        private $statement;

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

        public function escape($var) {
            return $this->connection->real_escape_string($var);
        }

        public function prepare($stmnt, $bind, ...$vars) {
            $this->statement = $this->connection->prepare($stmnt);
            $this->statement->bind_param($bind, ...$vars);
        }

        public function execute() {
            $this->statement->execute() or $this->finishConnection('{"error_code":-311,"error_message":"Invalid Statement"}');
            return $this->statement->get_result();
        }

        public function getAffectedRows() {
            return $this->connection->affected_rows;
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