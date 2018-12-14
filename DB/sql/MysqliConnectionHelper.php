<?php

class DBConnector
{
    private $conn;
    private $db;
    private $host;
    private $user;
    private $password;
    private $database;
    private $port;

    public function __construct($host, $user, $password, $database, $port = 3306) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
        $this->connect();
    }

    public function connect() {
        print 'try to connection with ' . $this->user . '@' . $this->host . ':' . $this->port . ':' . $this->database;
        $this->conn = @mysqli_connect($this->host, $this->user, $this->password, $this->database, $this->port);
        if($this->conn) {
            print ' -> success' . PHP_EOL;
        } else {
            print ' -> ' . mysqli_connect_error() . PHP_EOL;
        }
    }

    public function disconnect() {
        mysqli_close($this->conn);
    }

    public function query($query) {
        while ($this->conn == NULL || @mysqli_ping($this->conn) === FALSE) {
            print 'try to reconnect in 5 sec' . PHP_EOL;
            sleep(5);
            $this->connect();
        }

        $result = mysqli_query($this->conn, $query);
        if (!$result) print mysqli_error($this->conn) . PHP_EOL;
        return $result;
    }

    public function queryWithCompleteArray($query) {
        $resultPointer = $this->query($query);
        $result = [];
        while($resultPointer && ($row = mysqli_fetch_assoc($resultPointer))) {
            $result[] = $row;
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getConn()
    {
        return $this->conn;
    }
    
    
}