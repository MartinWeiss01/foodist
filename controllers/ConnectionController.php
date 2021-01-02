<?php
require_once('../config/.config.inc.php');

class ConnectionHandler {
    public $connection;
    public function __construct() {
        $this->connection = new mysqli(SQL_SERVER, SQL_USER, SQL_PASS, SQL_DB);
        if($this->connection->connect_error) $this->exitScript('{"error_code":-1,"error_message":"Connection Error"}');
        $this->connection->set_charset("utf8");
        return $this->connection;
    }

    public function callQuery($query) {
        $result = $this->connection->query($query) or $this->exitScript('{"error_code":-2,"error_message":"Invalid Query","mysql_error":"'.$this->connection->error.'"}');
        return $result;
    }

    public function callMultiQuery($query) {
        $result = $this->connection->multi_query($query) or $this->exitScript('{"error_code":-2,"error_message":"Invalid Query","mysql_error":"'.$this->connection->error.'"}');
        return $result;
    }

    public function finishConnection($response_message) {
        $this->connection->close();
        $this->exitScript($response_message);
    }

    private function exitScript($response_message) {
        die($response_message);
    }
}
?>