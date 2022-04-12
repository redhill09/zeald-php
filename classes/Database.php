<?php
use Illuminate\Support;
class Database
{
    private $database = "nba2019";
    public $mysqli_db;
    
    function __construct() {
        $this->connect();
    }

    protected function connect() {
        $this->mysqli_db = new mysqli('localhost', 'root', '', $this->database ?: 'employees');
    }

    

    

    
}
