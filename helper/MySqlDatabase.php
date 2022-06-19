<?php

class MySqlDatabase {

    private $host;
    private $user;
    private $pass;
    private $database;

    private $conn;

    public function __construct($host, $user, $pass, $database) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;

        $this->connect();
    }

    public function __destruct(){
        $this->disconnect();
    }

    public function query($sql) {
        $sql = trim($sql);
        $result = mysqli_query($this->conn, $sql);
        if (substr($sql, 0, 6) == "SELECT"){
            return mysqli_fetch_all($result , MYSQLI_ASSOC);
        }
    }

    private function connect() {
        $conn = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        if (!$conn) {
            die('Connection failed: ' . mysqli_connect_error());
        }
        $this->conn = $conn;
    }

    private function disconnect() {
        mysqli_close($this->conn);
    }
}