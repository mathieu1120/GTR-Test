<?php

class Db
{
  private static $_instance = null;
  private $_link = null;

  private $_server;
  private $_user;
  private $_password;
  private $_database;

  private $_lastQuery;
  
  public static function getInstance()
  {
    if (self::$_instance === null)
    self::$_instance = new self();
    return self::$_instance;
  }

  private function __construct()
  {
    $this->_link = new mysqli(SERVER_DB, USER_DB, PASSWORD_DB, DATABASE_DB);
    if ($this->_link->connect_errno)
    {
      d('Failed to connect to MySQL: ('.$this->_link->connect_errno.') '.$this->_link->connect_error);
    }
  }

  public function __destruct()
  {
    if ($this->_link)
    $this->disconnect();
  }

  public function disconnect()
  {
    $this->_link->close();
  }

  private function _query($sql)
  {
    $this->_lastQuery = $sql;
    if ($result = $this->_link->query($sql))
    return $result;
    d($this->error());
  }

  public function escape($string)
  {
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
      return stripslashes($string);
    }

    return $this->_link->real_escape_string($string);
  }

  public function error()
  {
    return array($this->_lastQuery, $this->_link->info, $this->_link);
  }

  public function executeSelect($sql, $by = null)
  {
    $result = $this->_query($sql);
    $data = array();
    if ($result)
    {
      while (($row = $result->fetch_assoc()))
      {
        if ($by)
        $data[$row[$by]] = $row;
        else
        $data[] = $row;
      }
    }
    return $data;
  }

  public function update($table, $param, $where)
  {
    if (!$table || !$param)
    return false;

    $values = '';
    foreach ($param as $key => $val)
    {
      if ($val === null)
      $values .= '`'.$key.'` = NULL,';
      else
      $values .= '`'.$key.'` = "'.$val.'",';
    }

    $values = substr($values, 0, -1);
    
    $w = $this->getWhere($where);
    if ($this->_query('UPDATE '.$table.' SET '.$values.$w))
    return $this->getRow($table, $where);
    return false;
  }

  public function insert($table, $param)
  {
    if (!$table || !$param)
    return false;

    $keys = '';
    $values = '';
    foreach ($param as $key => $val)
    {
      $values .= '"'.utf8_encode($val).'",';
      $keys .= '`'.$key.'`,';
    }

    $values = substr($values, 0, -1);
    $keys = substr($keys, 0, -1);
    $this->_query('INSERT INTO '.$table.' ('.$keys.') VALUE ('.$values.')');
    return $this->_link->insert_id;
  }

  public function getAllData($table, $order = array(), $key = null)
  {
    $o = $this->getOrder($order);
    $data = array();
    $result = $this->_query('SELECT * FROM '.$table.$o);
    if ($result)
    {
      while (($row = $result->fetch_assoc()))
      {
        if ($key)
        $data[$row[$key]] = $row;
        else
        $data[] = $row;
      }
    }
    return $data;
  }

  public function getAllDataByKey($table, $key, $order = array())
  {
    return $this->getAllData($table, $order, $key);
  }

  public function getRow($table, $where)
  {
    $w = $this->getWhere($where);
    $result = $this->_query('SELECT * FROM '.$table.$w.' LIMIT 0,1');
    if ($result)
    return $result->fetch_assoc();
    return null;
  }

  public function find($table, $where, $order = null, $by = null, $select = '*')
  {
    $w = $this->getWhere($where);
    $o = $this->getOrder($order);
    
    $result = $this->_query('SELECT '.$select.' FROM '.$table.$w.$o);
    $data = array();
    if ($result)
    {
      while (($row = $result->fetch_assoc()))
      {
        if ($by)
        $data[$row[$by]] = $row;
        else
        $data[] = $row;
      }
    }
    return $data;
  }

  public function getValue($table, $field, $where = null)
  {
    $w = $this->getWhere($where);
    $ressource = $this->_query('SELECT `'.$field.'` FROM '.$table.$w);
    if ($ressource)
    {
      $result = $ressource->fetch_assoc();
      return $result[$field];
    }
    return null;
  }

  public function numberOfRows($table, $where)
  {
    $w = $this->getWhere($where);
    $ressource = $this->_query('SELECT COUNT(*) as number_rows FROM '.$table.$w);
    if ($ressource)
    return $ressource->fetch_assoc();

    return null;
  }

  public function isStored($table, $where)
  {
    $w = $this->getWhere($where);
    $ressource = $this->_query('SELECT 1 FROM '.$table.$w);

    if (!$ressource)
    return false;
    $result = $ressource->fetch_assoc();
    return (bool)$result[1];
  }

  public function getOrder($order = null)
  {
    $o = '';
    if (count($order) == 1)
    {
      $o = ' ORDER BY `'.key($order).'` '.$order[key($order)];
    }
    else if ($order)
    {
      $o = ' ORDER BY';
      foreach ($order as $field => $ord)
      $o .= ' `'.$field.'` '.$ord.',';
      $o = substr($o, 0, -1);
    }
    
    return $o;
  }

  public function getWhere($where = null, $letter = '')
  {
    $w = '';
    if ($letter)
    $letter .= '.';
    if (count($where) == 1)
    {
      if (is_array($where[key($where)]))
      {
        $w = ' WHERE (';
        foreach ($where[key($where)] as $or)
        $w .= $letter.'`'.key($where).'` = "'.$or.'" OR ';
        $w = substr($w, 0, -4).')';
      }
      else
      {
        if (is_string(key($where)))
        $w .= ' WHERE '.$letter.'`'.key($where).'` = "'.$where[key($where)].'"';
        else
        $w .= ' WHERE '.$letter.$where[key($where)];
      }
    }
    else if ($where)
    {
      $w = ' WHERE ';
      foreach ($where as $key => $val)
      {
        if (is_string($key) && is_array($val))
        {
          $w .= '(';
          foreach ($val as $keyOr => $or)
          $w .= $letter.'`'.$key.'` = "'.$or.'" OR ';
          $w = substr($w, 0, -4).') AND ';          
        }
        else if (is_array($val))
        {
          $w .= '(';
          foreach ($val as $or)
          $w .= $letter.$or.' OR ';
          $w = substr($w, 0, -4).') AND ';
        }
        else
        {
          if (is_string($key))
          $w .= $letter.'`'.$key.'` = "'.$val.'" AND ';
          else
          $w .= $letter.$val.' AND ';
        }
      }
      $w = substr($w, 0, -5);
    }
    return $w;
  }

  public function remove($table, $where)
  {
    $w = $this->getWhere($where);
    return $this->_query('DELETE FROM '.$table.$w);
  }

  public function string($string)
  {
    return $this->_link->real_escape_string($string);
  }

  public function createTable($table, $fieldsArray)
  {
    $fields = array();
    $primaryKey = array();
    
    foreach ($fieldsArray as $key => $field)
    {
      if (isset($field['Key']) && $field['Key'] == 'PRI')
      $primaryKey[] = $key;
      $fields[] = '`'.$key.'` '.$field['Type'].($field['size'] ? '('.$field['size'].')' : '')
                                              .($field['Null'] == 'YES' ? ' NULL' : ' NOT NULL')
                                              .($field['Extra'] ? ' '.$field['Extra'] : '')
                                              .$this->_getDefault($field);
    }
    return $this->_query('CREATE TABLE IF NOT EXISTS `'.$table.'`('.implode(',', $fields).($primaryKey ? ', PRIMARY KEY (`'.implode(',', $primaryKey).'`)' : '').')');
  }

  public function addField($table, $field)
  {
    $param = '`'.$field['Field'].'` '.$field['Type'].($field['size'] ? '('.$field['size'].')' : '')
                                              .($field['Null'] == 'YES' ? ' NULL' : ' NOT NULL')
                                              .($field['Extra'] ? ' '.$field['Extra'] : '')
                                              .$this->_getDefault($field);

    return $this->_query('ALTER TABLE `'.$table.'` ADD '.$param);
  }

  public function updateField($table, $field, $oldField = '')
  {
    $param = '`'.$field['Field'].'` '.$field['Type'].($field['size'] ? '('.$field['size'].')' : '')
                                              .($field['Null'] == 'YES' ? ' NULL' : ' NOT NULL')
                                              .($field['Extra'] ? ' '.$field['Extra'] : '')
                                              .$this->_getDefault($field);
    return $this->_query('ALTER TABLE `'.$table.'` CHANGE `'.($oldField ? $oldField : $field['Field']).'` '.$param);
  }

  private function _getDefault($field)
  {
    //rules for defautl
    if (($field['Default'] !== false && $field['Default'] == '' && $field['Null'] == 'YES') || $field['Default'] === false)
    return '';
    else if ($field['Default'] == '' && $field['Null'] != 'YES')
    {
      if (in_array($field['Type'], array('int', 'float', 'tinyint', 'double')))
      {
        //if ($field['Extra'] == 'auto_increment')
        return '';
        //return ' DEFAULT "0"';
      }
      else
      return ' DEFAULT ""';
    }
    else if ($field['Default'] == 'CURRENT_TIMESTAMP')
    return ' DEFAULT '.$field['Default'];
    else //default has something
    return ' DEFAULT "'.$field['Default'].'"';
  }
  
  public function removeTable($table)
  {
    return $this->_query('DROP TABLE IF EXISTS `'.$table.'`');
  }

  public function removeField($table, $field)
  {
    return Db::getInstance()->_query('ALTER TABLE `'.$table.'` DROP `'.$field.'`');
  }

  public function getSchema()
  {
    $result = $this->_query('SHOW TABLES');
    $tables = array();
    if ($result)
    {
      while (($row = $result->fetch_assoc()))
      {
        $tables[$row['Tables_in_'.DATABASE_DB]] = array();
        $columnsResult = $this->_query('SHOW COLUMNS FROM '.$row['Tables_in_'.DATABASE_DB]);
        while($column = $columnsResult->fetch_assoc())
        {
          if (strpos($column['Type'], '(') !== false)
          {
            $column['size'] = substr($column['Type'], strpos($column['Type'], '(') + 1, -1);
            $column['Type'] = substr($column['Type'], 0, strpos($column['Type'], '('));
          }
          $tables[$row['Tables_in_'.DATABASE_DB]][$column['Field']] = $column;
        }
      }
    }
    return $tables;
  }

  public function getColumnsFromTable($table)
  {
    $result = array();
    $columnsResult = $this->_query('SHOW COLUMNS FROM '.$table);
    while($column = $columnsResult->fetch_assoc())
    $result[] = $column;
    return $result;
  }
}