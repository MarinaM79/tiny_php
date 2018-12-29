<?php
/**
 * User: zjz
 * File: db.php
 * Date: 2018/12/10
 * Time: 13:43
 */


namespace src\tiny\DB;


use src\tiny\Logs\Error_log;

class DB
{
  protected $host;  // mysql connect host
  protected $port;  //  mysql connect port
  protected $database;  //  database name
  protected $type;  //  database type , example : mysql, mariadb, mongodb, sql_server ...
  protected $user;  //  mysql user account
  protected $password;  // mysql password
  protected $db;  //  mysql connect resource
  protected $field;  //  query field
  protected $query;  //  query string
  protected $table;  // table
  protected $where;  // where condition
  protected $conn_tab;  // join tables
  protected $result;  // the query result
  protected $sort;
  protected $value;
  protected $exits_sql;
  protected $order;
  protected $limit;

  //  set the pdo connect config
  public function __construct()
  {
      $this->host = MYSQL_HOST;
      $this->port = MYSQL_PORT;
      $this->database = MYSQL_DATABASE;
      $this->user = MYSQL_USER;
      $this->password = MYSQL_PASSWORD;
      $this->init();
  }

  // init the pdo connection
  protected function init(){
      try{
          $this->db = new \PDO('mysql:host='.$this->host.';dbname='.$this->database,$this->user,$this->password);
      }
      catch (\Exception $e){
          new Error_log($e);
          exit('Database Connect Error, Please Check Your Database Config');
      }
  }

  // table func
  public static function table($table){
      $dbs = new DB();
      $dbs->table = $table;
      $dbs->instance('table');
      $dbs->exits_sql['table'] = true;
      return $dbs;
  }

  // select function
  public function select($field){
      if (is_array($field)){
          $this->field = $field;
      }else{
          $this->field[] = $field;
      }
      $this->instance('select');
      $this->exits_sql['select'] = true;
      return $this;
  }

  // where function
  public function where($field,$condition,$field2 = ''){
      if ($field2 == ''){
          if (is_string($condition))$condition = '"'.$condition.'"';
          if (!is_array($condition))$this->where[] = $field.' = '.$condition;
          if (is_array($condition)){
              $coditions = ' (';
              $len = count($condition)-1;
              foreach ($condition as $k => $v){
                  if (is_string($v))$v='"'.$v.'"';
                  if ($k!=$len){
                      $coditions.=$v.',';
                  }else{
                      $coditions.=$v.') ';
                  }
              }
              $this->where[] = $field.' in '.$coditions;
          }
      }else{
          if (is_string($field2))$field2 = '"'.$field2.'"';
          if (!is_array($field2))$this->where[] = $field.' '.$condition.' '.$field2;
          if (is_array($field2)){
              $field2s = ' (';
              $len = count($field2)-1;
              foreach ($field2 as $k => $v){
                  if (is_string($v))$v='"'.$v.'"';
                  if ($k!=$len){
                      $field2s.=$v.',';
                  }else{
                      $field2s.=$v.') ';
                  }
              }
              $this->where[] = $field.$condition.$field2s;
          }
      }
      $this->instance('where');
      $this->exits_sql['where'] = true;
      return $this;
  }

  // update function
  public function update($data){
      foreach ($data as $k => $v){
          $this->field[] = $k;
      }
      $this->value = $data;
      $this->instance('update');
      $this->exits_sql['update'] = true;
      $this->exec();
  }

  // join function
  public function join($table_name,$field,$condition,$field2 = ''){
      if ($field2==''){
          if (is_string($condition))$condition = '"'.$condition.'"';
          if (!is_array($condition))$table_name = $table_name.' ON '.$field.' = '.$condition;
          if (is_array($condition)){
              $qu = ' (';
              $len = count($condition)-1;
              foreach ($condition as $k => $v){
                  if ($k!=$len){
                      $qu.=$v.',';
                  }else{
                      $qu.=$v.') ';
                  }
              }
              $table_name = $table_name.' ON '.$field.' IN '.$qu;
          }
      }else{
          if (is_string($field2))$field2 = '"'.$field2.'"';
          if (!is_array($field2))$table_name = $table_name.' ON '.$field.$condition.$field2;
          if (is_array($field2)){
              $qu = ' (';
              $len = count($field2)-1;
              foreach ($field2 as $k => $v){
                  if ($k!=$len){
                      $qu.=$v.',';
                  }else{
                      $qu.=$v.') ';
                  }
              }
              $table_name = $table_name.' ON '.$field.$condition.$qu;
          }
      }
      $this->conn_tab['join'][] = $table_name;
      $this->instance('join');
      $this->exits_sql['join'] = true;
      return $this;
  }

  // LeftJoin function
  public function LeftJoin($table_name,$field,$condition,$field2 = ''){
      if ($field2==''){
          if (is_string($condition))$condition = '"'.$condition.'"';
          if (!is_array($condition))$table_name = $table_name.' ON '.$field.' = '.$condition;
          if (is_array($condition)){
              $qu = ' (';
              $len = count($condition)-1;
              foreach ($condition as $k => $v){
                  if ($k!=$len){
                      $qu.=$v.',';
                  }else{
                      $qu.=$v.') ';
                  }
              }
              $table_name = $table_name.' ON '.$field.' IN '.$qu;
          }
      }else{
          if (is_string($field2))$field2 = '"'.$field2.'"';
          if (!is_array($field2))$table_name = $table_name.' ON '.$field.$condition.$field2;
          if (is_array($field2)){
              $qu = ' (';
              $len = count($field2)-1;
              foreach ($field2 as $k => $v){
                  if ($k!=$len){
                      $qu.=$v.',';
                  }else{
                      $qu.=$v.') ';
                  }
              }
              $table_name = $table_name.' ON '.$field.$condition.$qu;
          }
      }
      $this->conn_tab['leftjoin'][] = $table_name;
      $this->instance('leftjoin');
      $this->exits_sql['leftjoin'] = true;
      return $this;
  }

  // RighJoin function
  public function RightJoin($table_name,$field,$condition,$field2 = ''){
      if ($field2==''){
          if (is_string($condition))$condition = '"'.$condition.'"';
          if (!is_array($condition))$table_name = $table_name.' ON '.$field.' = '.$condition;
          if (is_array($condition)){
              $qu = ' (';
              $len = count($condition)-1;
              foreach ($condition as $k => $v){
                  if ($k!=$len){
                      $qu.=$v.',';
                  }else{
                      $qu.=$v.') ';
                  }
              }
              $table_name = $table_name.' ON '.$field.' IN '.$qu;
          }
      }else{
          if (is_string($field2))$field2 = '"'.$field2.'"';
          if (!is_array($field2))$table_name = $table_name.' ON '.$field.$condition.$field2;
          if (is_array($field2)){
              $qu = ' (';
              $len = count($field2)-1;
              foreach ($field2 as $k => $v){
                  if ($k!=$len){
                      $qu.=$v.',';
                  }else{
                      $qu.=$v.') ';
                  }
              }
              $table_name = $table_name.' ON '.$field.$condition.$qu;
          }
      }
      $this->conn_tab['rightjoin'][] = $table_name;
      $this->instance('rightjoin');
      $this->exits_sql['rightjoin'] = true;
      return $this;
  }

  // delete function
  public function delete(){
      $this->instance('delete');
      $this->exits_sql['delete'] = true;
      $this->exec();
      return $this->result;
  }

  // orderBy function
  public function orderBy($field,$sort = 'DESC'){
      $this->order = $field.' '.$sort.' ';
      $this->instance('order');
      return $this;
  }

  //  insert function
  public function insert($data){
      foreach ($data as $k => $v){
          $this->field[] = $k;
      }
      $this->value = $data;
      $this->instance('insert');
      $this->exits_sql['insert'] = true;
      $this->exec();
  }

  // add function
  public function add($data){
      foreach ($data as $k => $v){
          $this->field[] = $k;
      }
      if (!isset($data['created_at'])){
          $data['created_at'] = date('Y-m-d H:i:s',time());
          $this->field[] = 'created_at';
      }
      if (!isset($data['updated_at'])){
          $data['updated_at'] = date('Y-m-d H:i:s',time());
          $this->field[] = 'updated_at';
      }
      $this->value = $data;
      $this->instance('insert');
      $this->exits_sql['insert'] = true;
      $this->exec();
  }

  // limit function
  public function limit($start, $end = ''){
      $this->exits_sql['limit'] = true;
      if ($end==''){
          $start = ' 0,'.$start.' ';
      }else{
          $start = ' '.$start.','.$end.' ';
      }
      $this->limit = $start;
      $this->instance('limit');
      return $this;
  }

  // paginate
  public function paginate($num){
      $start = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
      $starts = ($start-1)*$num;
      $this->query.=' LIMIT '.$starts.','.$num;
      $this->exec();
      return $this->result;
  }

  // instance function
  public function instance($type){

      if ($type=='select'){  // select query
          if (!$this->query){
              $field = '';
              foreach ($this->field as $k => $v){
                  $k!=count($this->field)-1 && $field.=$v.',';
              }
              $field.=$this->field[count($this->field)-1];
              $this->query='SELECT '.$field.' FROM '.$this->table;
          }
      }

      if ($type=='limit'){
          $this->query.=' LIMIT '.$this->limit;
      }

      if ($type=='where'){  // where query
          if (isset($this->exits_sql['select'])){  // exits select
              $condition = ' WHERE ';
              foreach ($this->where as $v){
                  $condition.=$v;
              }
              $this->query.=$condition;
          }
      }

      if ($type=='delete'){  // delete query
          if (isset($this->exits_sql['where'])){
              $condition = ' WHERE ';
              foreach ($this->where as $v){
                  $condition.=$v;
              }
              $this->query = 'DELETE FROM '.$this->table.$condition;
          }
      }

      if ($type=='update'){  // update query
          if (isset($this->exits_sql['where'])){
              $qu = '';
              $len = count($this->field);
              foreach ($this->field as $k => $v){
                  $val = $this->value[$v];
                  if (is_string($val))$val = '"'.$val.'"';
                  if ($k!=$len-1){
                      $qu.=$v.' = '.$val.',';
                  }else{
                      $qu.=$v.' = '.$val;
                  }
              }
              $condition = ' WHERE ';
              foreach ($this->where as $v){
                  $condition.=$v;
              }
              $this->query = 'UPDATE '.$this->table.' SET '.$qu.$condition;
          }
      }

      if ($type=='insert'){
          $field = ' ( ';
          $vals = 'VALUES(';
          $len = count($this->field);
          foreach ($this->field as $k => $v){
              $val = $this->value[$v];
              if (is_string($val))$val = ' "'.$val.'" ';
              if ($k!=$len-1){
                  $field.=$v.',';
                  $vals.=$val.',';
              }else{
                  $field.=$v.') ';
                  $vals.=$val.') ';
              }
          }
          $this->query = 'INSERT INTO '.$this->table.$field.$vals;
      }

      if ($type=='join'){  // join query
          if (isset($this->exits_sql['select'])){
              $this->query.=' INNER JOIN '.$this->conn_tab['join'][0];
              array_pop($this->conn_tab['join']);
          }
      }

      if ($type=='leftjoin'){
          if (isset($this->exits_sql['select'])){
              $this->query.=' LEFT JOIN '.$this->conn_tab['leftjoin'][0];
              array_pop($this->conn_tab['leftjoin']);
          }
      }

      if ($type=='rightjoin'){
          if (isset($this->exits_sql['select'])){
              $this->query.=' RIGHT JOIN '.$this->conn_tab['rightjoin'][0];
              array_pop($this->conn_tab['rightjoin']);
          }
      }

      if ($type=='order'){  // orderBy query
          if (isset($this->exits_sql['select'])){
              $this->query.=' ORDER BY '.$this->order;
          }
      }
  }

  // execute function
  public function exec(){
      $this->query.=';';
      $res = $this->db->prepare($this->query);
      $res->execute();
      foreach ($res->fetchAll(\PDO::FETCH_ASSOC) as $k => $row){
          $this->result[] = $row;
      }
      $this->db = null;
  }

  public function get()
  {
      $this->exec();
      return $this->result;
  }

  public function first(){
      $this->exec();
      return $this->result[0];
  }

  //  the native sql query function
  public static function query($query){
      $db = new DB();
      $db->query = $query;
      $db->exec();
      return $db->result;
  }
}