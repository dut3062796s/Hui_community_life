<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/9
 * Time: 11:52
 * CURD 操作
 * 下面是ezSQL中一些主要的函数：
 * $db->get_results -- 从数据库中读取数据集。$db->get_results("SELECT name, email FROM users");
 * $db->get_row -- 从数据库中读取一行数据。$db->get_row("SELECT name,email FROM users WHERE id = 2");
 * $db->get_col -- 从数据库中读取一列指定的数据集。$names = $db->get_col("SELECT name,email FROM users",0)
 * $db->get_var -- 从数据库的数据集中读取一个值。$db->get_var("SELECT count(*) FROM users");
 * $db->query -- 执行一条SQL语句。$db->query("INSERT INTO users (id, name, email) VALUES (NULL,'justin','jv@foo.com')");|||||||$db->query("UPDATE users SET name = 'Justin' WHERE id = 2)");
 * $db->debug -- 打印最后执行的SQL语句及其返回的结果。$db->debug();
 * $db->vardump -- 打印变量的结构及其内容。$db->vardump($results);
 * $db->select -- 选择一个新数据库。$db->select("my_database");
 * $db->get_col_info -- 获取列的信息。
 * $db->hide_errors -- 隐藏错误。
 * $db->show_errors -- 显示错误。
 */
class Test1Model
{
    public function __construct() {
        $this->_config = Yaf_Registry::get("config");
        //$this->_db = new DbMysql ($this->_config->mysql->toArray());
        $mysqlcon = $this->_config->mysql->toArray();
        Yaf_loader::import("ezSQL/ez_sql_core.php");
        Yaf_loader::import("ezSQL/ez_sql_mysql.php");
        $this->_db = new ezSQL_mysql($mysqlcon['username'],$mysqlcon['password'],$mysqlcon['dbname'],$mysqlcon['host']);
        //$this->_redis = new Redis();
        //$this->_redis->connect($this->_config->redis->host);
    }
    protected $_table = "test";//表名
    protected $_parkey = "test_id";//主键id

    /**
     * 创建
     * @param array $params 数组参数
     * @return bool 返回类型
     * @throws Exception
     */
    public function Create($params = array())
    {
        try{
            // first we build the sql query string
            $columns_str = '(';
            $values_str = 'VALUES (';
            $add_comma = false;

            // add each parameter into the query string
            foreach ($params as $key => $val) {
                // only add comma after the first parameter has been appended
                if ($add_comma) {
                    $columns_str .= ', ';
                    $values_str .= ', ';
                } else {
                    $add_comma = true;
                }

                // now append the parameter
                $columns_str .= "$key";
                $values_str .= "'".$val."'";
            }
            $columns_str .= ') ';
            $values_str .= ')';

            // build final insert string
            $sql_str = "INSERT INTO $this->_table $columns_str $values_str";
            if($my_tables = $this->_db->query($sql_str)){
                return true;
            }else{
                return false;
            }
        }catch (Exception $e)
        {
            if (self::$SHOW_ERR == true) {
                throw new Exception ($e);
            }
            return false;
        }
    }
    /**/

    public function BatchCreate($columns,$rows)
    {
        try{
            if($this->_db->insertMultiple($this->_table,$columns,$rows)){
                return true;
            }else{
                return false;
            }
        }catch (Exception $e)
        {
            if (self::$SHOW_ERR == true) {
                throw new Exception ($e);
            }
            return false;
        }
    }

    /**
     * 修改
     * @param $params  set参数
     * @param array $wheres 修改条件
     * @return bool 返回类型
     * @throws Exception
     */
    public function Update($params, $wheres = array())
    {
        //$wheres = array("test_id" => $id);
        try{
            $add_comma = false;
            $set_string = '';
            foreach ($params as $key => $val) {
                // only add comma after the first parameter has been appended
                if ($add_comma) {
                    $set_string .= ', ';
                } else {
                    $add_comma = true;
                }

                // now append the parameter
                $set_string .= "$key= '$val'";
            }
            $where_string = '';
            if (!empty($wheres)) {
                // load each key value pair, and implode them with an AND
                $where_array = array();
                foreach ($wheres as $key => $val) {
                    $where_array[] = "$key='$val'";
                }
                // build the final where string
                $where_string = 'WHERE ' . implode(' AND ', $where_array);
            }

            // build final update string
            $sql_str = "UPDATE $this->_table SET $set_string $where_string";
            if($my_tables = $this->_db->query($sql_str)){
                return true;
            }else{
                return false;
            }
        }catch(Exception $e){
            if (self::$SHOW_ERR == true) {
                throw new Exception ($e);
            }
            return false;
        }
    }

    /**
     * 获取所有数据
     * @param null $params 参数
     * @param null $order_by 排序条件
     * @return array 返回类型
     */
    public function  GetAll($params = null,$order_by = null)
    {
        $sql_str = "SELECT * FROM $this->_table";
        $sql_str .= ( count($params) > 0 ? ' WHERE ' : '' );
        $add_and = false;
        if (empty($params)) {
            $params = array();
        }
        foreach ($params as $key => $val) {
            if ($add_and) {
                $sql_str .= ' AND ';
            } else {
                $add_and = true;
            }
            $sql_str .= "$key = '$val'";
        }

        if (!empty($order_by)) {
            $sql_str .= ' ORDER BY';
            $add_comma = false;
            foreach ($order_by as $column => $order) {
                if ($add_comma) {
                    $sql_str .= ', ';
                } else {
                    $add_comma = true;
                }
                $sql_str .= " $column $order";
            }
        }
        // 从数据库中获得单变量的演示
        // (and using abstracted function sysdate)
        //$current_time = $this->_db->get_var("SELECT " . $this->_db->sysdate());
        //print "ezSQL demo for mySQL database run @ $current_time";

        // 打印出最后的查询和结果..
        //$this->_db->debug();

        // 获取表列表从当前数据库中..
        //$my_tables = $this->_db->get_results("SHOW TABLES",ARRAY_N);

        // 打印出最后的查询和结果..
        //$this->_db->debug();
        $my_tables = $this->_db->get_results($sql_str);

        return $my_tables;
    }

    /**
     * 分页获取数据
     * @param null $params where参数
     * @param null $limit 每页大小
     * @param null $start 跳过前多少条 ($pageindex-1)*$pagesize
     * @param null $order_by 排序条件
     * @return array 返回类型
     */
    public function  PageList($params = null, $limit = null, $start = null, $order_by = null)
    {
        $sql_str = "SELECT * FROM $this->_table";
        $sql_str .= ( count($params) > 0 ? ' WHERE ' : '' );
        $add_and = false;
        if (empty($params)) {
            $params = array();
        }
        foreach ($params as $key => $val) {
            if ($add_and) {
                $sql_str .= ' AND ';
            } else {
                $add_and = true;
            }
            $sql_str .= "$key = '$val'";
        }

        if (!empty($order_by)) {
            $sql_str .= ' ORDER BY';
            $add_comma = false;
            foreach ($order_by as $column => $order) {
                if ($add_comma) {
                    $sql_str .= ', ';
                } else {
                    $add_comma = true;
                }
                $sql_str .= " $column $order";
            }
        }
        if (!is_null($limit)) {
            //$sql_str .= ' LIMIT ' . "$limit," .(!is_null($start) ? "$start" : '') ;
            $sql_str .= ' LIMIT ' .(!is_null($start) ? "$start" : ''). ",$limit" ;
        }
        $my_tables = $this->_db->get_results($sql_str);
        //$usrinfo = $this->_db->select($this->_table,$params,$limit,$start,$order_by,$break);
        return $my_tables;
    }

    /**
     * 获取条数
     * @param array $wheres 条件
     * @return null 返回类型
     */
    public function  Count($wheres = array()){
        $where_string="";
        if (!empty($wheres)) {
            // load each key value pair, and implode them with an AND
            $where_array = array();
            foreach ($wheres as $key => $val) {
                $where_array[] = "$key='$val'";
            }
            // build the final where string
            $where_string = 'WHERE ' . implode(' AND ', $where_array);
        }
        $my_tables = $this->_db->get_var("SELECT count(*) FROM $this->_table $where_string");

        return $my_tables;
    }

    /**
     * 根据ID获取数据
     * @param $id 主键ID
     * @param null $params where参数
     * @param null $order_by 排序参数
     * @return array|null 返回类型
     */
    public function GetByID($id,$params = null,$order_by = null)
    {
        $sql_str = "SELECT * FROM ". $this->_table." WHERE ". $this->_parkey ." = '".$id."'";
        //$sql_str .= ( count($params) > 0 ? ' WHERE ' : '' );
        $add_and = false;
        if (empty($params)) {
            $params = array();
        }
        foreach ($params as $key => $val) {
            if ($add_and) {
                $sql_str .= ' AND ';
            } else {
                $add_and = true;
            }
            $sql_str .= "$key = '$val'";
        }

        if (!empty($order_by)) {
            $sql_str .= ' ORDER BY';
            $add_comma = false;
            foreach ($order_by as $column => $order) {
                if ($add_comma) {
                    $sql_str .= ', ';
                } else {
                    $add_comma = true;
                }
                $sql_str .= " $column $order";
            }
        }
        $my_tables = $this->_db->get_row($sql_str);
        return $my_tables;
    }

    /**
     * 删除
     * @param array $params 条件
     * @return bool 返回类型
     * @throws Exception
     */
    public function Delete($params = array())
    {
        //$params = array("test_id"=>$id);
        try{
            // building query string
            $sql_str = "DELETE FROM $this->_table";
            // append WHERE if necessary
            $sql_str .= ( count($params) > 0 ? ' WHERE ' : '' );

            $add_and = false;
            // add each clause using parameter array
            foreach ($params as $key => $val) {
                // only add AND after the first clause item has been appended
                if ($add_and) {
                    $sql_str .= ' AND ';
                } else {
                    $add_and = true;
                }

                // append clause item
                $sql_str .= "$key = '$val'";
            }
            if($my_tables = $this->_db->query($sql_str)){
                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            if (self::$SHOW_ERR == true) {
                throw new Exception ($e);
            }
            return false;
        }
    }

}

