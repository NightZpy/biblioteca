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
 * Sado Schema File Class
 *
 * @package Sado
 * @name SadoSchemaFile
 * @author Shay Anderson 4.12
 */
final class SadoSchemaFile
{
	/**
	 * Schema file key names
	 */
	const KEY_METADATA = '.schema';
	const KEY_MODEL_CLASS = 'class';
	const KEY_MODEL_FIELDS = 'fields';
	const KEY_MODEL_IGNORE = 'ignore';
	const KEY_MODEL_KEY = 'key';

	/**
	 * Class relationship to model name
	 *
	 * @var array ('Model1' => 'model_1' [, ...])
	 */
	private $__classes = array();

	/**
	 * Schema metadata
	 *
	 * @var array
	 */
	private $__metadata = array(
		'cache_file_path' => '',
		'cache_force' => false,
		'cache_ignore' => array()
	);

	/**
	 * Schema
	 *
	 * @var array
	 */
	private $__schema = array();

	/**
	 * Init schema
	 *
	 * @param string $schema_file_path
	 * @param int $instance_id
	 */
	public function __construct($schema_file_path, $instance_id)
	{
		if(!is_readable($schema_file_path))
		{
			SadoFactory::error("Failed to load schema file \"{$schema_file_path}\", cannot find/read file", __CLASS__);
			return;
		}
		$schema = require $schema_file_path;
		if(is_array($schema))
		{
			foreach($schema as $model_name => $model)
			{
				if(is_array($model) && count($model) > 0)
				{
					foreach($model as $k => &$v)
					{
						$v = self::formatValue($v);
						if(count($v) < 1)
						{
							continue;
						}
						if($model_name == self::KEY_METADATA)
						{
							if(array_key_exists($k, $this->__metadata))
							{
								if(is_array($this->__metadata[$k])) // allow mult values
								{
									$this->__metadata[$k] = $v;
								}
								else if(isset($v[0]))
								{
									$this->__metadata[$k] = $v[0];
								}
							}
						}
						else
						{
							if($k == self::KEY_MODEL_CLASS)
							{
								foreach($v as $w)
								{
									$this->__classes[$w] = $model_name;
								}
							}
							else
							{
								if(!isset($this->__schema[$model_name]))
								{
									$this->__schema[$model_name] = array();
								}
								$this->__schema[$model_name][$k] = $v;
							}
						}
					}
				}
			}
		}
		if($this->isValid())
		{
			SadoFactory::log("Loaded schema file \"{$schema_file_path}\"", $instance_id);
		}
		if(!empty($this->__metadata['cache_file_path']))
		{
			$schema_cache = new SadoSchemaFileCache($this->__metadata['cache_file_path'], $instance_id, $this->__metadata['cache_ignore'],
				(bool)$this->__metadata['cache_force'], SadoSchemaFileCache::getSchemaHash($schema));
			$cache = $schema_cache->getCache();
			if(count($cache) > 0)
			{
				foreach($cache as $c_model_name => $c_model)
				{
					foreach($c_model as $k => $v)
					{
						if(isset($this->__schema[$c_model_name][$k]))
						{
							if($k != self::KEY_MODEL_KEY) // do not override key
							{
								$diff = array_diff($v, $this->__schema[$c_model_name][$k]);
								if(is_array($diff) && count($diff) > 0)
								{
									foreach($diff as $w)
									{
										$this->__schema[$c_model_name][$k][] = trim($w);
									}
								}
							}
						}
						else
						{
							if(!isset($this->__schema[$c_model_name]))
							{
								$this->__schema[$c_model_name] = array();
							}
							$this->__schema[$c_model_name][$k] = $v;
						}
					}
					if(!isset($this->__schema[$c_model_name][self::KEY_MODEL_KEY]))
					{
						SadoFactory::error("Failed to cache schema file to \"{$this->__metadata['cache_file_path']}\", failed to"
							. " find PRIMARY KEY for table \"{$c_model_name}\" (you must manually set key, or set no key, in schema file"
							. ' before schema can be cached)', __CLASS__, $instance_id);
					}
				}
			}
			unset($cache);
		}
		unset($schema);
	}

	/**
	 * Add class to model (set relationship between class and model)
	 *
	 * @param string $class_name
	 * @param string $model_name
	 * @return void
	 */
	public function addClassToModel($class_name, $model_name)
	{
		if($this->isValid())
		{
			$this->__classes[trim($class_name)] = trim($model_name);
		}
	}

	/**
	 * Format value for schema
	 *
	 * @param mixed $value
	 * @return array
	 */
	public static function formatValue($value)
	{
		if(is_array($value)) // only parse scalar
		{
			return $value;
		}
		if(strpos($value, ',') !== false) // comma separated values
		{
			return array_filter(array_map('trim', explode(',', $value)));
		}
		else if(is_string($value))
		{
			trim($value);
			if(strlen($value) > 0)
			{
				return array($value);
			}
		}
		else
		{
			return array($value);
		}
		return array();
	}

	/**
	 * Check if model key required, not required if key set to false in schema
	 *
	 * @param string $class_name
	 * @return bool (false no key required for model)
	 */
	public function getIsModelKey($class_name)
	{
		return $this->getModelKey($class_name) !== false;
	}

	/**
	 * Model fields getter
	 *
	 * @param string $class_name
	 * @return array ('field1', 'field2' [, ...])
	 */
	public function getModelFields($class_name)
	{
		if(isset($this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_FIELDS]))
		{
			if(count($this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_FIELDS]) > 0
				&& isset($this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_IGNORE]))
			{
				$this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_FIELDS] =
					array_diff($this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_FIELDS],
						$this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_IGNORE]);
				unset($this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_IGNORE]);
			}
			return $this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_FIELDS];
		}
		return array();
	}

	/**
	 * Model key getter
	 *
	 * @param string $class_name
	 * @return string|bool (false if no key required)
	 */
	public function getModelKey($class_name)
	{
		if(isset($this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_KEY][0]))
		{
			return $this->__schema[$this->getModelName($class_name)][self::KEY_MODEL_KEY][0];
		}
		return '';
	}

	/**
	 * Model name getter
	 *
	 * @param string $class_name
	 * @return string
	 */
	public function getModelName($class_name)
	{
		if($this->isValidClass($class_name))
		{
			return $this->__classes[$class_name];
		}
		return '';
	}

	/**
	 * Check if schema has initialized successfully and is ready
	 *
	 * @return bool
	 */
	public function isValid()
	{
		return count($this->__schema) > 0;
	}

	/**
	 * Check if schema has been initialized for model (which is related to class name)
	 *
	 * @param string $class_name
	 * @return bool
	 */
	public function isValidClass($class_name)
	{
		return isset($this->__classes[$class_name]);
	}
}