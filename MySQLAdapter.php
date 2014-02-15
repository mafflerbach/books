<?php
// TODO Change to PDO implementation

class MySQLAdapter {

    private $_config = array();
    private static $_instance = NULL;
    private static $_connected = FALSE;
    private $_link = NULL;
    private $_result = NULL;


    public static function getInstance(array $config = array())
    {
        if (self::$_instance === NULL) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }


    private function __construct(array $config)
    {

        if (count($config) < 4) {
            throw new MySQLAdapterException('Invalid number of connection parameters');
        }
        $this->_config = $config;
    }

    private function __clone(){}


    private function connect() {
        if (self::$_connected === FALSE) {
            list($host, $user, $password, $database) = $this->_config;
            if ((!$this->_link = mysqli_connect($host, $user, $password, $database))) {
                throw new MySQLAdapterException('Error connecting to MySQL : ' . mysqli_connect_error());
            }
            self::$_connected = TRUE;
            unset($host, $user, $password, $database);
        }
    }

    public function query($query) {
        if (is_string($query) and !empty($query)) {
            $this->connect();
            if ((!$this->_result = mysqli_query($this->_link, $query))) {
                throw new MySQLAdapterException('Error performing query ' . $query . ' Error : ' . mysqli_error($this->_link));
            }
        }
    }


    public function fetch() {
        if ((!$row = mysqli_fetch_object($this->_result))) {
            mysqli_free_result($this->_result);
            return FALSE;
        }
        return $row;
    }


    public function getInsertID() {
        if ($this->_link !== NUlL) {
            return mysqli_insert_id($this->_link);
        }
        return NULL;
    }

    public function countRows() {
        if ($this->_result !== NULL) {
            return mysqli_num_rows($this->_result);
        }
        return 0;
    }

    function __destruct() {
        is_resource($this->_link) AND mysqli_close($this->_link);
    }


}

