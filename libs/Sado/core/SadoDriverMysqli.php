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
 * Sado Mysqli Class
 *
 * @package Sado
 * @name SadoDriverMysqli
 * @author Shay Anderson 10.11
 */
final class SadoDriverMysqli extends Sado
{
	/**
	 * Mysqli object
	 *
	 * @var mysqli
	 */
	private $__mysqli;

	/**
	 * Connect to DB
	 *
	 * @return void
	 */
	protected function _connect()
	{
		if(!$this->_is_connected)
		{
			$instance_conf = SadoFactory::getInstanceConf($this->_instance_id);
			$this->__mysqli = @new mysqli($instance_conf->getHost(), $instance_conf->getUsername(),
				$instance_conf->getPassword(), $instance_conf->getDatabase(),
				( $instance_conf->getPort() ? $instance_conf->getPort() : $this->_getDefaultPort() ));
			if(mysqli_connect_error())
			{
				$this->error('Connection error: ' . mysqli_connect_error() . ' (' . mysqli_connect_errno() . ')');
				return;
			}
			SadoFactory::log('Connection opened', $this->_instance_id);
			$this->_is_connected = true;
		}
	}

	/**
	 * Default port number getter
	 *
	 * @return int
	 */
	protected function _getDefaultPort()
	{
		return 3306;
	}

	/**
	 * Call stored function/procedure
	 *
	 * @param string $func_name
	 * @param mixed $params [, mixed $...] (OUT param ex: '@outparam')
	 * @return array (result set)
	 */
	public function call($func_name = NULL, $params = NULL)
	{
		// set params
		$params = func_get_args();
		unset($params[0]); // remove $func_name
		if($this->_isConnected())
		{
			$params_str = NULL;
			if(is_array($params))
			{
				if(count($params) > 0)
				{
					foreach($params as $k => $param)
					{
						$params[$k] = preg_match('/^@[^\s\'"]+$/', $param) // check for @outparam
							? $param : $this->value($param);
					}
					$params_str = implode(',', $params);
				}
			}
			else if($params !== NULL)
			{
				$params_str = preg_match('/^@[^\s\'"]+$/', $param) // check for @outparam
					 ? $params : $this->value($params);
			}
			return $this->select("CALL {$func_name}({$params_str});");
		}
		return array();
	}

	/**
	 * Escape string for safe SQL statement use
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function clean($value = NULL)
	{
		if($this->_isConnected())
		{
			$value = function_exists('get_magic_quotes_gpc') ? stripcslashes($value) : $value;
			return $this->__mysqli->real_escape_string($value);
		}
	}

	/**
	 * Close DB connection
	 *
	 * @return void
	 */
	public function close()
	{
		if($this->_isConnected(false))
		{
			$this->__mysqli->close();
			$this->_is_connected = false;
			SadoFactory::log('Connection closed', $this->_instance_id);
		}
	}

	/**
	 * Delete statement
	 *
	 * @param string $table
	 * @param array $args
	 * @return boolean (false on error)
	 */
	public function delete($table = NULL, $args = array())
	{
		return $this->query("DELETE FROM {$table} " . ( is_array($args) && count($args) > 0 ? 'WHERE '
			. implode(' AND ', $this->value($args)) : NULL ) . ';');
	}

	/**
	 * Insert ID getter
	 *
	 * @return int
	 */
	public function getInsertId()
	{
		if($this->_is_connected)
		{
			return $this->__mysqli->insert_id;
		}
		return 0;
	}

	/**
	 * Model key field name getter
	 *
	 * @param string $model_name
	 * @return string (ex: 'col1')
	 */
	public function getModelKey($model_name)
	{
		$key = '';
		$fields = $this->select("DESCRIBE {$this->clean($model_name)};");
		if(count($fields) > 0)
		{
			$key0 = $key1 = NULL;
			$mult_keys = false;
			foreach($fields as $field)
			{
				if(isset($field['Field']))
				{
					if(isset($field['Key']) && $field['Key'] == 'PRI' && isset($field['Extra']) && $field['Extra'])
					{
						$key0 = $field['Field'];
					}
					else if(isset($field['Key']) && $field['Key'] == 'PRI')
					{
						if($key1 === NULL)
						{
							$key1 = $field['Field'];
						}
						else
						{
							$mult_keys = true;
						}
					}
				}
			}
			if(!$mult_keys) // only set key if not multiple keys (cannot logically auto assign key)
			{
				if(strlen($key0) > 0)
				{
					$key = $key0;
				}
				else if(strlen($key1) > 0)
				{
					$key = $key1;
				}
			}
		}
		return $key;
	}

	/**
	 * Model table fields getter
	 *
	 * @param string $model_name
	 * @return array('col1', 'col2' [, ...])
	 */
	public function getModelTableFields($model_name)
	{
		$fields = array();
		foreach($this->select("DESCRIBE {$this->clean($model_name)};") as $field)
		{
			if(isset($field['Field']))
			{
				$fields[] = $field['Field'];
			}
		}
		return $fields;
	}

	/**
	 * Model table names getter
	 *
	 * @return array ('table1', 'table2' [, ...])
	 */
	public function getModelTables()
	{
		$tables = array();
		foreach($this->select('SHOW TABLE STATUS;') as $table)
		{
			if(isset($table['Name']))
			{
				$tables[] = $table['Name'];
			}
		}
		return $tables;
	}

	/**
	 * Insert statement
	 *
	 * @param string $table
	 * @param array $fields_and_values
	 * @param boolean $ignore_insert_errors
	 * @return bool (false on error)
	 */
	public function insert($table = NULL, $fields_and_values = array(), $ignore_insert_errors = false)
	{
		if(!is_array($fields_and_values) || count($fields_and_values) < 1)
		{
			return false;
		}
		$values = array();
		foreach($fields_and_values as $v)
		{
			$values[] = $this->value($v);
		}
		$ignore = $ignore_insert_errors ? ' IGNORE' : '';
		return $this->query("INSERT{$ignore} INTO {$table}(" . implode(', ', array_keys($fields_and_values))
			. ') VALUES(' . implode(', ', $values) . ');');
	}

	/**
	 * Ping DB connection
	 *
	 * @return bool (false on error)
	 */
	public function ping()
	{
		if($this->_isConnected())
		{
			if($this->__mysqli->ping())
			{
				SadoFactory::log('Ping successful', $this->_instance_id);
				return true;
			}
			else
			{
				$this->error("Ping error: {$this->__mysqli->error}");
			}
		}
		return false;
	}

	/**
	 * Execute single query
	 *
	 * @param string $query
	 * @return mixed (array if results set, else bool(false on error))
	 */
	public function query($query = NULL)
	{
		if($this->_isConnected())
		{
			$query = $this->_cleanSelectQueryValues($query);
			SadoFactory::log("Executing query: \"{$query}\"", $this->_instance_id);
			$result = $this->__mysqli->query($query);
			if(!$result && $this->__mysqli->error)
			{
				$this->error("Query error: {$this->__mysqli->error}");
			}
			$this->affected_rows = $this->__mysqli->affected_rows;
			if($result instanceof mysqli_result)
			{
				$this->num_rows = $result->num_rows;
				if($this->num_rows > 0)
				{
					while($row = $result->fetch_assoc())
					{
						$this->_records[] = $row;
					}
				}
				$result->close();
				if($this->__mysqli->more_results())
				{
					$this->__mysqli->next_result();
				}
				return $this->_records;
			}
			return $result;
		}
	}

	/**
	 * Select statement
	 *
	 * @param string $query
	 * @return array
	 */
	public function select($query = NULL)
	{
		return $this->query($query);
	}

	/**
	 * Update statement
	 *
	 * @param string $table
	 * @param array $fields_and_values
	 * @param array $args
	 * @return bool (false on error)
	 */
	public function update($table = NULL, $fields_and_values = array(), $args = array())
	{
		if(!is_array($fields_and_values) || count($fields_and_values) < 1)
		{
			return false;
		}
		return $this->query("UPDATE {$table} SET " . implode(', ', $this->value($fields_and_values))
			. ( is_array($args) && count($args) ? ' WHERE ' . implode(' AND ', $this->value($args)) : NULL ) . ';');
	}
}