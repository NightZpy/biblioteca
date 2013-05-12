<?php
/**
 * Sado - PHP Simple Abstract Database Objects with ORM Library by Shay Anderson
 *
 * Sado is free software and is distributed WITHOUT ANY WARRANTY
 *
 * @version $v: 1.1.r235 Mar 28, 2013 $;
 * @copyright Copyright 2013 ShayAnderson.com <http://www.shayanderson.com>
 * @license MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @link http://www.shayanderson.com/projects/sado-php-orm.htm
 */

/**
 * Sado Abstract Class
 *
 * @package Sado
 * @name Sado
 * @author Shay Anderson 10.11
 */
abstract class Sado
{
	/**
	 * Instance ID
	 *
	 * @var int
	 */
	protected $_instance_id;

	/**
	 * DB connected flag
	 *
	 * @var bool
	 */
	protected $_is_connected = false;

	/**
	 * Records from SELECT
	 *
	 * @var array
	 */
	protected $_records = array();

	/**
	 * Number of affected rows
	 *
	 * @var int
	 */
	public $affected_rows = 0;

	/**
	 * Last error
	 *
	 * @var string
	 */
	public $error;

	/**
	 * Error flag
	 *
	 * @var bool
	 */
	public $is_error = false;

	/**
	 * Number of rows in result set
	 *
	 * @var int
	 */
	public $num_rows = 0;

	/**
	 * Init
	 */
	final public function __construct(){}

	/**
	 * Finalize
	 */
	final public function __destruct()
	{
		SadoFactory::finalize();
	}

	/**
	 * Clean values in SELECT query that is surrounded by curly brackets, ex: {"value's"} => "values\'", or {'d"s'} => 'd\"s'
	 *
	 * @param string $query
	 * @return string
	 */
	final protected function _cleanSelectQueryValues($query = '')
	{
		if(!empty($query) && strpos($query, '{') !== false)
		{
			preg_match_all('/\{([\'"]{1})(.*)([\'"]{1})\}/isU', $query, $matches); // match {"val"} or {'val'}
			if(isset($matches[0]) && count($matches[0]) > 0)
			{
				foreach($matches[0] as $k => $v)
				{
					if(isset($matches[1][$k]) && isset($matches[2][$k]) && isset($matches[3][$k]))
					{
						$query = str_replace($v, $matches[1][$k] . $this->clean($matches[2][$k]) . $matches[3][$k], $query);
					}
				}
			}
		}
		return $query;
	}

	/**
	 * Connect to DB
	 *
	 * @return void
	 */
	abstract protected function _connect();

	/**
	 * Default port number getter
	 *
	 * @return int
	 */
	abstract protected function _getDefaultPort();

	/**
	 * Check if connected to DB and prepare for next request
	 *
	 * @param bool $auto_connect
	 * @return bool
	 */
	final protected function _isConnected($auto_connect = true)
	{
		$this->_reset(); // reset connection params
		if(!$this->_is_connected && $auto_connect) // check if connected
		{
			if(!SadoFactory::getInstanceConf($this->_instance_id)->getDatabase())
			{
				$this->error('Connection failed: invalid database name (database name not set)');
				return false;
			}
			if(!SadoFactory::getInstanceConf($this->_instance_id)->getHost())
			{
				$this->error('Connection failed: invalid host (host not set)');
				return false;
			}
			if(!SadoFactory::getInstanceConf($this->_instance_id)->getUsername())
			{
				$this->error('Connection failed: invalid username (username name not set)');
				return false;
			}
			if(!SadoFactory::getInstanceConf($this->_instance_id)->getPassword())
			{
				$this->error('Connection failed: invalid password (password not set)');
				return false;
			}
			$this->_connect();
		}
		return $this->_is_connected;
	}

	/**
	 * Reset instance default params (error, affected rows, etc)
	 *
	 * @return void
	 */
	final protected function _reset()
	{
		$this->_records = array();
		$this->affected_rows = 0;
		$this->error = NULL;
		$this->is_error = false;
		$this->num_rows = 0;
	}

	/**
	 * Call stored function/procedure
	 *
	 * @param string $func_name
	 * @param mixed $args [, mixed $...]
	 * @return array (result set)
	 */
	abstract public function call($func_name = NULL, $args = NULL);

	/**
	 * Escape string for safe SQL statement use
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	abstract public function clean($value = NULL);

	/**
	 * Close DB connection
	 *
	 * @return void
	 */
	abstract public function close();

	/**
	 * Delete statement
	 *
	 * @param string $table
	 * @param array $args
	 * @return bool (false on error)
	 */
	abstract public function delete($table = NULL, $args = array());

	/**
	 * DB instance error setter
	 *
	 * @param string $error_message
	 * @return void
	 */
	final public function error($error_message = NULL)
	{
		if($error_message !== NULL)
		{
			$this->is_error = true;
			$this->error = $error_message;
			SadoFactory::error($error_message, get_class($this), $this->_instance_id);
			SadoFactory::log($error_message, $this->_instance_id);
		}
	}

	/**
	 * Insert ID getter
	 *
	 * @return int
	 */
	abstract public function getInsertId();

	/**
	 * Instance ID getter
	 *
	 * @return int
	 */
	final public function getInstanceId()
	{
		return $this->_instance_id;
	}

	/**
	 * Model key field name getter
	 *
	 * @param string $model_name
	 * @return string (ex: 'col1')
	 */
	abstract public function getModelKey($model_name);

	/**
	 * Model table fields getter
	 *
	 * @param string $model_name
	 * @return array('col1', 'col2' [, ...])
	 */
	abstract public function getModelTableFields($model_name);

	/**
	 * Model table names getter
	 *
	 * @return array ('table1', 'table2' [, ...])
	 */
	abstract public function getModelTables();

	/**
	 * Get next record in records queue
	 *
	 * @param bool $reset (if true current position will be reset)
	 * @return array ('field1', 'field2' [, ...])
	 */
	final public function getRecordNext($reset = false)
	{
		if($reset)
		{
			reset($this->_records);
		}
		$a = current($this->_records);
		next($this->_records);
		return $a;
	}

	/**
	 * Records (from SELECT) getter
	 *
	 * @return array (('field1', 'field1'), ('field1', 'field2') [, ...])
	 */
	final public function getRecords()
	{
		reset($this->_records);
		return $this->_records;
	}

	/**
	 * Records as ORM objects (from SELECT) getter
	 *
	 * @param string $orm_class_name
	 * @return array (array of child SadoORM objects)
	 */
	final public function getRecordsAsObjects($orm_class_name)
	{
		$objects = array();
		if(count($this->getRecords()) > 0 && class_exists($orm_class_name))
		{
			$i = 0;
			foreach($this->getRecords() as $fields)
			{
				$objects[$i] = new $orm_class_name;
				$objects[$i]->setFields($fields, true); // force load
				$i++;
			}
		}
		return $objects;
	}

	/**
	 * Insert statement
	 *
	 * @param string $table
	 * @param array $fields_and_values
	 * @param boolean $ignore_insert_errors
	 * @return boolean (false on error)
	 */
	abstract public function insert($table = NULL, $fields_and_values = array(), $ignore_insert_errors = false);

	/**
	 * Ping DB connection
	 *
	 * @return bool (false on error)
	 */
	abstract public function ping();

	/**
	 * Execute single query
	 *
	 * @param string $query
	 * @return mixed (array if results set, else bool)
	 */
	abstract public function query($query = NULL);

	/**
	 * Select statement
	 *
	 * @param string $query
	 * @return array
	 */
	abstract public function select($query = NULL);

	/**
	 * Select query builder
	 *
	 * @return SadoSelectBuilder
	 */
	final public function selectBuilder()
	{
		return new SadoSelectBuilder($this);
	}

	/**
	 * Instance ID setter
	 *
	 * @param int $instance_id
	 * @return void
	 */
	final public function setInstanceId($instance_id = 1)
	{
		$this->_instance_id = (int)$instance_id;
		SadoFactory::log('Connection ready', $instance_id);
	}

	/**
	 * Update statement
	 *
	 * @param string $table
	 * @param array $fields_and_values
	 * @param array $args
	 * @return bool (false on error)
	 */
	abstract public function update($table = NULL, $fields_and_values = array(), $args = array());

	/**
	 * Prepare safe values for query, ex: "test's" => "'test\'s'", if array: field1 = 'val1', [...]
	 *
	 * @param mixed $value
	 * @return string
	 */
	final public function value($value = NULL)
	{
		if($this->_isConnected())
		{
			if(!is_array($value))
			{
				return "'{$this->clean($value)}'";
			}
			else
			{
				foreach($value as $k => $v)
				{
					$value[$k] = "{$k} = {$this->value($v)}";
				}
				return $value;
			}
		}
	}
}