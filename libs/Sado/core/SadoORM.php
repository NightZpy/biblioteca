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
 * Sado Object Relational Mapper (ORM) Class
 *
 * @package Sado
 * @name SadoORM
 * @author Shay Anderson 10.11
 */
abstract class SadoORM
{
	/**
	 * Sado instance ID (instance ID default 1)
	 *
	 * @var int
	 */
	private $__instance_id = 1;

	/**
	 * Model primary key exists flag
	 *
	 * @var bool
	 */
	private $__is_key = true;

	/**
	 * Collection of SadoORM used for model joins
	 *
	 * @var array ((object, join_on, prefix, left), [...])
	 */
	private $__join_sado_orm = array();

	/**
	 * Current record ID
	 *
	 * @var int
	 */
	private $__record_id;

	/**
	 * Sado model object
	 *
	 * @var SadoModel
	 */
	private $__sado_model;

	/**
	 * Initialize params (auto set)
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Init
	 *
	 * @param mixed $id_or_load_fields_and_values (ex: load with ID: 4, or load with multiple fields: array('id' => 4, 'field2' => 'value')
	 * @param [mixed $var0, mixed $var1, [...]] (auto sets $params array)
	 */
	final public function __construct($id_or_load_fields_and_values = NULL)
	{
		$this->params = func_get_args(); // auto set params
		array_shift($this->params);
		$this->__sado_model = new SadoModel;
		if(!SadoFactory::getInstance($this->__instance_id) instanceof Sado)
		{
			SadoFactory::error('Failed to initialize SadoORM object, invalid '
				. "Sado instance with ID \"{$this->__instance_id}\"", __CLASS__);
		}
		$this->__init(); // child constructor
		$class_name = get_class($this);
		if(SadoFactory::getSchemaFile($this->__instance_id)->isValidClass($class_name)) // load model using schema
		{
			$schema = &SadoFactory::getSchemaFile($this->__instance_id);
			if(strlen($schema->getModelName($class_name)) > 0)
			{
				$this->name($schema->getModelName($class_name));
			}
			if(strlen($schema->getModelKey($class_name)) > 0 || $schema->getModelKey($class_name) === false) // false means no key required
			{
				$this->key($schema->getModelKey($class_name));
			}
			$this->field($schema->getModelFields($class_name));
			if($schema->getIsModelKey($class_name) === false)
			{
				$this->setHasKey(false);
			}
		}
		if(strlen($this->__sado_model->getName()) < 1)
		{
			$this->getSadoInstance()->error("Failed to initialize SadoORM object (\"{$class_name}\")"
				. ', invalid name for model (name not set)');
		}
		if($this->__is_key && strlen($this->__sado_model->getKey()) < 1)
		{
			$this->getSadoInstance()->error('Failed to initialize SadoORM object, invalid key for model "'
				. $this->__sado_model->getName()	. '" (key not set, required key can be turned off)');
		}
		if($id_or_load_fields_and_values !== NULL)
		{
			if(is_array($id_or_load_fields_and_values))
			{
				$this->load($id_or_load_fields_and_values);
			}
			else if(is_scalar($id_or_load_fields_and_values))
			{
				$this->id($id_or_load_fields_and_values);
			}
		}
	}

	/**
	 * Model field value getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	final public function __get($name = NULL)
	{
		return $this->__sado_model->__get($name);
	}

	/**
	 * Join query header getter
	 *
	 * @return sting (NULL on no join(s))
	 */
	final private function __getJoinQueryHeader()
	{
		if(count($this->__join_sado_orm) > 0) // check if join models
		{
			$query = 'SELECT DISTINCT '; // build query for joins
			$query_from = ' FROM ' . $this->getName();
			$fields = $this->getFields();
			foreach($fields as $k => $field) // add parent model fields
			{
				$fields[$k] = $this->getName() . '.' . $field;
			}
			$query .= implode(', ', $fields);
			$i = 0;
			foreach($this->__join_sado_orm as $join_orm) // add child model(s) fields
			{
				$i++;
				$fields = $join_orm['object']->getFields();
				if(count($fields) > 0)
				{
					foreach($fields as $field)
					{
						$this->field($join_orm['prefix'] . $field);
						$query .= ', ' . $join_orm['object']->getName() . '.' . $field . ' AS \''
							. $join_orm['prefix'] . $field . '\'';
					}
				}

				$query_from .= ( $join_orm['left'] ? ' LEFT' : NULL ) . ' JOIN ' . $join_orm['object']->getName()
					. ' ON ';
				$query_on = array();
				foreach($join_orm['join_on'] as $left_field => $right_field)
				{
					$query_on[] = "{$this->getName()}.{$left_field} = {$join_orm['object']->getName()}.{$right_field}";
				}
				$query_from .= implode(' AND ', $query_on);
			}
			return $query . $query_from;
		}
		return NULL;
	}

	/**
	 * Initialize object/model
	 *
	 * @return void
	 */
	protected function __init()
	{
		// overridable
	}

	/**
	 * Model field value setter
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return bool
	 */
	final public function __set($name = NULL, $value = NULL)
	{
		if($this->__sado_model->__set($name, $value))
		{
			$this->__sado_model->setFieldModified($name);
			return true;
		}
		return false;
	}

	/**
	 * Destroy record (currently loaded record or with record_id param)
	 *
	 * @param mixed $record_id (optional)
	 * @param array $x_args (additional delete arguments)
	 * @return bool
	 */
	final public function destroy($record_id = NULL, $x_args = array())
	{
		if($this->__is_key)
		{
			$record_id = $record_id ? $record_id : $this->__record_id;
			if($record_id)
			{
				$args = array($this->getKey() => $record_id);
				if(is_array($x_args) && count($x_args) > 0)
				{
					foreach($x_args as $field => $value)
					{
						if($field != $this->getKey() && $this->__sado_model->isField($field))
						{
							$args[$field] = $value;
						}
					}
				}
				if($this->getSadoInstance()->delete($this->getName(), $args)
					&& $this->getSadoInstance()->affected_rows > 0)
				{
					$this->reset();
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Add model field
	 *
	 * @param string|array $name
	 * @return void
	 */
	final protected function field($name = NULL)
	{
		if(is_array($name))
		{
			foreach($name as $field)
			{
				$this->field($field);
			}
		}
		else
		{
			$this->__sado_model->addField($name);
		}
	}

	/**
	 * Remove model field
	 *
	 * @param string|array $name
	 * @return void
	 */
	final public function fieldRemove($name = '')
	{
		if(is_array($name))
		{
			foreach($name as $field)
			{
				$this->fieldRemove($field);
			}
		}
		else
		{
			$this->__sado_model->remField($name);
		}
	}

	/**
	 * Model fields getter
	 *
	 * @return array
	 */
	final public function getFields()
	{
		return $this->__sado_model->getFields();
	}

	/**
	 * Record ID getter
	 *
	 * @return mixed
	 */
	final public function getId()
	{
		return $this->__record_id;
	}

	/**
	 * Model key getter
	 *
	 * @return string
	 */
	final public function getKey()
	{
		return $this->__is_key ? $this->__sado_model->getKey() : NULL;
	}

	/**
	 * Modified field names getter
	 *
	 * @return array (field1, field2, [...])
	 */
	final protected function getModifiedFields()
	{
		$modified_fields = array();
		foreach($this->getFields() as $field)
		{
			if($this->__sado_model->isFieldModified($field))
			{
				$modified_fields[] = $field;
			}
		}
		return $modified_fields;
	}

	/**
	 * Model name getter
	 *
	 * @return string
	 */
	final public function getName()
	{
		return $this->__sado_model->getName();
	}

	/**
	 * Sado instance getter
	 *
	 * @return Sado
	 */
	final public function &getSadoInstance()
	{
		return SadoFactory::getInstance($this->__instance_id);
	}

	/**
	 * Load model record by ID (key field value)
	 *
	 * @param mixed $record_id
	 * @return bool
	 */
	final public function id($record_id = NULL)
	{
		if($this->__is_key && $record_id)
		{
			if(strcmp($record_id, $this->__record_id) !== 0)
			{
				$query = $this->__getJoinQueryHeader();
				if($query) // check if join models
				{
					$query .= ' WHERE ' . $this->getName() . '.' . $this->getKey() . ' = '
						. $this->getSadoInstance()->value($record_id) . ' LIMIT 1;';
					$r = $this->getSadoInstance()->select($query);
				}
				else
				{
					$r = $this->getSadoInstance()
						->selectBuilder()
							->selectDistinct(implode(', ', $this->getFields()))
							->from($this->getName())
							->where($this->getKey(), $record_id)
							->limit(1)
							->get();
				}
				if(isset($r[0]) && count($r[0])) // check if valid record
				{
					$this->setFields($r[0]);
					$this->__record_id = $record_id;
					return true;
				}
			}
			else // record ID already loaded
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if field exists
	 *
	 * @param mixed $name
	 * @return bool
	 */
	final public function isField($name = NULL)
	{
		return $this->__sado_model->isField($name);
	}

	/**
	 * Check if record is loaded
	 *
	 * @return bool
	 */
	final public function isLoaded()
	{
		return isset($this->__record_id);
	}

	/**
	 * Check if record has been modified
	 *
	 * @return bool
	 */
	final protected function isModified()
	{
		foreach($this->getFields() as $field)
		{
			if($this->__sado_model->isFieldModified($field))
			{
				return true;
			}
		}
	}

	/**
	 * Model key setter
	 *
	 * @param string $key
	 * @return void
	 */
	final protected function key($key = NULL)
	{
		$this->__sado_model->setKey($key);
	}

	/**
	 * Load record by field(s) and value(s)
	 *
	 * @param array $fields_and_values
	 * @return bool
	 */
	final public function load($fields_and_values = array())
	{
		if(is_array($fields_and_values) && count($fields_and_values) > 0)
		{
			$query = $this->__getJoinQueryHeader();
			$join = false;
			if($query) // check if joins
			{
				$join = true;
				$query .= ' WHERE ';
			}
			else
			{
				$query = ' SELECT ' . implode(', ', $this->getFields())
					. " FROM {$this->getName()} "
					. ' WHERE ';
			}
			$args = false;
			foreach($fields_and_values as $field => $value) // add args
			{
				if($this->__sado_model->isField($field) || $join)
				{
					$query .= ( $args ? ' AND ' : NULL ) . ( $join && strpos($field, '.') === false ? $this->getName()
						. '.' : NULL ) . $field	. " = {$this->getSadoInstance()->value($value)} ";
					$args = true;
				}
			}
			if(!$args) // check for valid WHERE clause
			{
				return false;
			}
			$query .= ' LIMIT 1;';
			if($query)
			{
				$r = $this->getSadoInstance()->select($query);
				if(isset($r[0]) && count($r[0])) // check if valid record
				{
					$this->setFields($r[0]);
					if($this->__is_key) $this->__record_id = $this->__sado_model->__get($this->getKey());
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Register child class name with model for schema (for when child class not set in schema)
	 *
	 * @param string $model_name
	 * @return void
	 */
	final protected function model($model_name)
	{
		SadoFactory::getSchemaFile($this->__instance_id)->addClassToModel(get_class($this), $model_name);
	}

	/**
	 * Join model with another model
	 *
	 * @param SadoORM $sado_orm_object
	 * @param array $join_on ('model1_id' => 'model2_id'), or multiple: ('a_id' => 'b_id', 'a_id2' => 'b_id2')
	 * @param bool $left_join
	 * @return void
	 */
	final protected function modelJoin(SadoORM &$sado_orm_object, array $join_on, $field_prefix = NULL, $left_join = true)
	{
		$this->__join_sado_orm[] = array('object' => &$sado_orm_object, 'join_on' => $join_on, 'prefix' => $field_prefix,
			'left' => $left_join);
	}

	/**
	 * Model name setter
	 *
	 * @param string $name
	 * @return void
	 */
	final protected function name($name = NULL)
	{
		$this->__sado_model->setName($name);
	}

	/**
	 * Reset record (flush record ID and set field values null)
	 *
	 * @return void
	 */
	final public function reset()
	{
		$this->__record_id = NULL;
		foreach($this->getFields() as $field)
		{
			$this->__sado_model->__set($field, NULL);
			$this->__sado_model->setFieldModified($field, false);
		}
	}

	/**
	 * Save current record (update and insert)
	 *
	 * @param bool $manual_new_record_key
	 * @return int (affected rows)
	 */
	final public function save($manual_new_record_key = false)
	{
		if($this->__is_key)
		{
			// check if loaded record (update) and if record has been modified
			if($this->__record_id !== NULL && $this->isModified())
			{
				if($this->getSadoInstance()->update($this->getName(),
					$this->__sado_model->getFieldsAndValues($this->getModifiedFields()),
					array($this->getKey() => $this->__sado_model->__get($this->getKey()))))
				{
						return $this->getSadoInstance()->affected_rows;
				}
			}
			else if($this->__record_id === NULL) // add a new record (insert) if record ID not set (if not loaded)
			{
				$fields_and_values = $this->__sado_model->getFieldsAndValues();
				if(!$manual_new_record_key)
				{
					unset($fields_and_values[$this->getKey()]);
				}
				if($this->getSadoInstance()->insert($this->getName(), $fields_and_values))
				{
					if($this->getSadoInstance()->affected_rows && $this->getSadoInstance()->getInsertId())
					{
						$this->__record_id = $this->getSadoInstance()->getInsertId();
						$this->__sado_model->__set($this->getKey(), $this->__record_id);
						return $this->getSadoInstance()->affected_rows;
					}
				}
			}
		}
		return 0;
	}

	/**
	 * Multiple model field value setter
	 *
	 * @param array $fields_and_values
	 * @param bool $force_load (will force record as loaded, and set record ID if record ID in fields and values)
	 * @return bool
	 */
	final public function setFields($fields_and_values = array(), $force_load = false)
	{
		$this->__getJoinQueryHeader(); // set joins
		$field_set = $force_loaded = false;
		if(is_array($fields_and_values) && count($fields_and_values))
		{
			foreach($fields_and_values as $field => $value)
			{
				if($this->__sado_model->__set($field, $value))
				{
					$field_set = true;
				}
				if($force_load && $this->__is_key && $this->getKey() === $field && $value)
				{
					$this->__record_id = $value;
					$force_loaded = true;
				}
			}
		}
		return !$force_load ? $field_set : $force_loaded;
	}

	/**
	 * Model has key setter
	 *
	 * @param bool $has_key
	 * @return void
	 */
	final protected function setHasKey($has_key = true)
	{
		$this->__is_key = (bool)$has_key;
	}

	/**
	 * Sado instance ID setter
	 *
	 * @param int $instance_id
	 * @return void
	 */
	final protected function setInstanceId($instance_id)
	{
		$this->__instance_id = (int)$instance_id;
	}
}