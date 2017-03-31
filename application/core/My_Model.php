<?php

/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/17
 * Time: 16:41
 */
class My_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
}

class BaseModel extends My_Model
{
    protected $tableName = '';
    protected $key = '';

    public function __construct($tbl_name = '')
    {
        parent::__construct();
        if ($tbl_name) {
            $this->tableName = $tbl_name;
        }
    }

    /**
     * 新增
     * @param $data
     * @return int 失败返回0，成功返回生成的id
     */
    public function insert($data)
    {
        $flag = $this->db->insert($this->tableName, $data);
        $this->logSql();
        if ($flag) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    /**
     * 删除，支持主键删除和条件删除
     * @param $where 字符串或数组array('id!!='=>$id,'name>'=>$name,'date<'=>$date)
     * @return mixed
     */
    public function delete($where)
    {
        if (is_numeric($where)) {
            $where[$this->key] = $where;
        }
        $flag = $this->db->delete($this->tableName, $where);
        $this->logSql();
        return $flag;
    }

    /**
     * 更新
     * @param $data 要更新数据
     * @param $where 字符串或数组array('id!!='=>$id,'name>'=>$name,'date<'=>$date)
     * @return mixed
     */
    public function update($data, $where)
    {
        $flag = $this->db->update($this->tableName, $data, $where);
        $this->logSql();
        return $flag;
    }

    /**
     * 返回一条记录
     * @param $where 字符串或数组array('id!!='=>$id,'name>'=>$name,'date<'=>$date)
     * @param string $filed
     * @param string $type object 或 array
     * @return mixed
     */
    public function getRow($where, $filed = '*', $type = 'array')
    {
        $this->db->select($filed);
        $this->db->where($where);
        $query = $this->db->get($this->tableName);
        $row = $query->row(0, $type);
        $this->logSql();
        return $row;
    }

    /**
     * 返回多条记录
     * @param $where 字符串或数组array('id!!='=>$id,'name>'=>$name,'date<'=>$date)
     * @param string $filed
     * @param string $type object 或 array
     * @param int $page
     * @param int $pageSize
     * @param string $orderBy
     * @return mixed
     */
    public function getList($where, $filed = '*', $orderBy = 'id desc', $type = 'array', $page = 1, $pageSize = 10)
    {
        $this->db->select($filed);
        $this->db->where($where);
        $this->db->order_by($orderBy);
        $offset = ($page - 1) * $pageSize;
        $query = $this->db->get($this->tableName, $pageSize, $offset);
        $list = $query->result($type);
        $this->logSql();
        return $list;
    }

    /**
     * @author 汤圆
     * @function 返回多条记录
     * @param $where
     * @param string $filed
     * @param $column
     * @param array $rage
     * @param string $orderBy
     * @param string $type
     * @return mixed
     */
    public function whereIn($where, $filed = '*', $column, array $rage, $orderBy = 'id desc', $type = 'array')
    {
        $this->db->select($filed);
        $this->db->where($where);
        $this->db->where_in($column, $rage);
        $this->db->order_by($orderBy);
        $query = $this->db->get($this->tableName);
        return $query->result($type);
    }

    /**
     * 获取查询的总条数
     * @param $where
     * @return mixed
     */
    public function getCount($where)
    {
        $this->db->where($where);
        return $this->db->count_all_results($this->tableName);
    }

    public function sumWhere($column, $where, $type = 'array')
    {
        $this->db->where($where);
        $this->db->select_sum($column);
        $query = $this->db->get($this->tableName);
        $row = $query->row(0, $type);
        $this->logSql();
        return $row;
    }

    /**
     * @author 汤圆
     * @function 执行查询sql
     * @param $sql
     * @return mixed
     */
    public function getSqlResult($sql)
    {
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * 批量执行sql语句
     * @param $sql
     * @param bool|false $debug
     * @return mixed
     */
    public function runSql($sql, $debug = false)
    {
        $this->db->trans_start();
        $sqlArr = explode(';', $sql);
        foreach ($sqlArr as $value) {
            $this->db->query($value);
        }
        $this->db->trans_complete();
        $this->logSql();
        return $this->db->trans_status();
    }

    /**
     * 调试和日志
     * @param bool|false $debug
     */
    public function logSql($debug = false)
    {
        if ($debug == true || $this->config->item('debug') == true) {
            $sql = $this->db->last_query();
            echo $sql;
        }

        if ($this->config->item('log') == true) {
            $sql = $this->db->last_query();
            $this->writeSql($sql);
        }
    }

    public function writeSql($data, $type = 0)
    {
        if (empty($data)) {
            return;
        }

        switch ($type) {
            case 0:
                $pre = 'SQL';
                break;
            case 1:
                $pre = 'Log';
                break;
            default:
                $pre = 'Error';
        }
        $filepath = "./application/logs/" . date('Ymd');
        if (!is_dir($filepath)) {
            mkdir($filepath);
        }
        $filename = $filepath . "{$pre}_" . date('His') . ".log";
        $hd = fopen($filename, "a+");
        if ($hd) {
            $logdata = array();
            $logdata[] = date("Y-m-d H:i:s");
            $logdata[] = $_SERVER['REQUEST_URI'];
            $logdata[] = var_export($data, true);
            $logdata[] = var_export($data, true);
            $string = implode("      ", $logdata);
            fputs($hd, $string, "\r\n");
        }
        fclose($hd);
    }
}

?>