<?php
class Database extends PDO {
    //Variable declaration

    private $host = "localhost";
    private $passwd = "";
    private $username = "root";
    private $dbname = "trpo";
/*
    private $host = "mysql1.alwaysdata.com";
    private $passwd = "trpo";
    private $username = "ophir_trpo";
    private $dbname = "ophir_trpo";
*/

    //Connect to DB when the class construct
    public function __construct($host=NULL, $dbname=NULL, $username=NULL, $passwd=NULL) {
        if(isset($host) && !empty($host)) {
            $this->host = $host;
        }

        if(isset($dbname) && !empty($dbname)) {
            $this->dbname = $dbname;
        }

        if(isset($username) && !empty($username)) {
            $this->username = $username;
        }

        if(isset($passwd) && !empty($passwd)) {
            $this->passwd = $passwd;
        }

        $opts = array(
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );

        parent::__construct("mysql:dbname=$this->dbname;host=$this->host", $this->username, $this->passwd, $opts);
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

}
?>
