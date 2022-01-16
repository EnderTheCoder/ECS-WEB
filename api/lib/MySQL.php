<?php

class MySQL
{
    const DB_HOST = '192.168.10.120';

    const DB_USERNAME = 'ECS-Server';

    const DB_PASSWORD = 'Feng,HK,4778!';

    const DB_NAME = 'ecs-server';

    private $con;
    private $isError = false;
    private $ErrorMsg;
    private $result;
    private $row;

    private PDOStatement $stmt;

    //在实例化对象时连接数据库
    public function __construct()
    {
        $this->connect();
    }

    //检测是否已经连接数据库。如果已经连接就返回连接，如果没有就进行连接。
    private function connect(): pdo
    {
        if ($this->con != null) return $this->con;
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME;
        $con = new pdo($dsn, self::DB_USERNAME, self::DB_PASSWORD);
        $this->con = $con;
        $con->query('set names utf8');
        return $this->con;
    }

    //改变使用的数据库
    public function changeDB($dbName)
    {
        $sql = 'use ' . $dbName;
        $conn = $this->connect();
        $conn->query($sql);
    }

    //执行不带有用户输入的sql语句
    public function query($sql): bool
    {
        $conn = $this->connect();
        $conn->query($sql);
        return true;
    }

    //使用绑定参数执行带有输入的sql语句,$sql是sql语句,$params是参数数组
    public function bind_query($sql, $params = null, $debug = false): bool|array|string
    {
        if ($params != null && !is_array($params)) $params = array(0 => $params);
        try {
            $conn = $this->connect();
            $stmt = $conn->prepare($sql);
            if ($params)
                for ($i = 1; $i <= count($params); $i++)
                    $stmt->bindValue($i, $params[$i], PDO::PARAM_STR);
            $stmt->execute();
            $this->result = $stmt->fetchAll();
            $this->row = Tools::countX($this->result);
            return $this->result;
        } catch (PDOException $exception) {
            die($exception->getTrace());
        }
    }

    public function prepareSQL($SQL) {
        $conn = $this->connect();
        $this->stmt = $conn->prepare($SQL);
    }

    public function bindValue($number, $value) {
        $this->stmt->bindValue($number, $value);
    }

    public function execute() {
        $this->stmt->execute();
    }

    public function result(): bool|array
    {
        return $this->stmt->fetchAll();
    }


//    public function insert($table, $data)
//    {
//        $fields = "";
//        $values = "";
//        foreach ($data as $field => $value) {
//            $fields .= $field . ", ";
////            $values .= $value . ", ";
//        }
//        $fields = substr($fields, 0, strlen($fields) - 3);
////        $values = substr($values, 0, strlen($values) - 3);
//
//        $sql = 'INSERT INTO ' . $table . ' (' . $fields . ') VALUES ( ' . ' )';
//    }

    function update($table, $key, $value, $c_key, $c_value, $c_function = '=')
    {
        $sql = 'UPDATE `' . $table . '` SET `' . $key . '` = ? WHERE `' . $c_key . '` ' . $c_function . ' ?';
        $params = array(
            1 => $value,
            2 => $c_value
        );
        $this->bind_query($sql, $params);
    }

    function change($table, $key, $function, $value, $c_key, $c_value, $c_function = '=')
    {
        $sql = 'UPDATE `' . $table . '` SET `' . $key . '` = ? ' . $function . ' `' . $key . '` WHERE `' . $c_key . '` ' . $c_function . ' ?';
        $params = array(
            1 => $value,
            2 => $c_value
        );
        $this->bind_query($sql, $params);
    }

    public function fetch($enableRowNums = false)
    {
        if ($enableRowNums) $this->result['row'] = Tools::countX($this->result);
        return $this->result;
    }

    public function fetchLine($key = null, $line = 0)
    {
        if ($key === null) return $this->result[$line];
        return $this->result[$line][$key];
    }

    public function getRowNum()
    {
        return $this->row;
    }

    //debug函数，判断是否发生错误
    public function isError(): bool
    {
        return $this->isError;
    }

    //debug函数，获取错误信息
    public function getError()
    {
        return $this->ErrorMsg;
    }

    //获取上一次插入所产生的自增id
    public function getId(): bool|string
    {
        $conn = $this->connect();
        return $conn->lastInsertId();
    }

    //关闭数据库连接
    public function close()
    {
        $this->con = null;
    }

}