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
 * Sado Schema File Cache Class
 *
 * @package Sado
 * @name SadoSchemaFileCache
 * @author Shay Anderson 04.12
 */
final class SadoSchemaFileCache
{
	/**
	 * Metadata key names
	 */
	const KEY_METADATA =  '.cache';
	const KEY_METADATA_SCHEMA = 'schema';

	/**
	 * Cached schema
	 *
	 * @var array
	 */
	private $__cache = array();

	/**
	 * Init
	 *
	 * @param string $schema_cache_file_path
	 * @param int $instance_id
	 * @param array $ignore_tables
	 * @param bool $force_cache
	 * @param string $schema_hash
	 */
	public function __construct($schema_cache_file_path, $instance_id, $ignore_tables = array(), $force_cache = false, $schema_hash = '')
	{
		$dir_cache = $file_cache = NULL;
		if(!is_file($schema_cache_file_path) && strpos($schema_cache_file_path, DIRECTORY_SEPARATOR) !== false)
		{
			$file_cache = substr($schema_cache_file_path, strrpos($schema_cache_file_path, DIRECTORY_SEPARATOR) + 1,
				strlen($schema_cache_file_path));
			$dir_cache = str_replace($file_cache, NULL, $schema_cache_file_path);
			if(!is_dir($dir_cache) || !is_writable($dir_cache))
			{
				SadoFactory::error("Failed to cache schema file to cache file \"{$schema_cache_file_path}\","
					. " cache directory \"{$dir_cache}\" is not writable (add write permissions to directory)",
					__CLASS__, $instance_id);
				return;
			}
			$force_cache = true;
		}
		else if(!is_writable($schema_cache_file_path))
		{
			SadoFactory::error("Failed to cache schema file, cache file \"{$schema_cache_file_path}\" is no writable"
				. ' (add write permissions to file)', __CLASS__, $instance_id);
			return;
		}
		if(!$force_cache) // file, check if valid schema
		{
			$cache = require $schema_cache_file_path;
			if(!is_array($cache)) // no schema
			{
				$force_cache = true;
			}
			else
			{
				if(!isset($cache[self::KEY_METADATA][self::KEY_METADATA_SCHEMA])
					|| $schema_hash != $cache[self::KEY_METADATA][self::KEY_METADATA_SCHEMA]) // schema modified, cache
				{
					$force_cache = true;
				}
				else
				{
					foreach($cache as $model_name => $model)
					{
						if($model_name == self::KEY_METADATA)
						{
							unset($cache[$model_name]);
							continue;
						}
						foreach($model as $k => $v)
						{
							$cache[$model_name][$k] = SadoSchemaFile::formatValue($v); // convert to arrays
						}
					}
					$this->__cache = &$cache;
					SadoFactory::log("Loaded cached schema file \"{$schema_cache_file_path}\"", $instance_id);
				}
			}
		}
		if($force_cache)
		{
			$schema = array();
			$sado = &SadoFactory::getInstance($instance_id);
			foreach($sado->getModelTables() as $table)
			{
				foreach($ignore_tables as $ignore)
				{
					if(substr($ignore, 0, 1) == '*') // match '*post'
					{
						$ignore = str_replace('*', NULL, $ignore);
						if(substr($table, -(strlen($ignore))) == $ignore) // *field matches ignore
						{
							continue 2;
						}
					}
					else if(substr($ignore, -1) == '*') // match 'pre*'
					{
						$ignore = str_replace('*', NULL, $ignore);
						if(substr($table, 0, strlen($ignore)) == $ignore) // field* matches ignore
						{
							continue 2;
						}
					}
					else if($table == $ignore) // ignore table
					{
						continue 2;
					}
				}
				$schema[$table] = array();
				$key = $sado->getModelKey($table);
				if(strlen($key) > 0)
				{
					$schema[$table][SadoSchemaFile::KEY_MODEL_KEY] = SadoSchemaFile::formatValue($key);
				}
				$fields = $sado->getModelTableFields($table);
				if(count($fields) > 0)
				{
					foreach($fields as $k => $field)
					{
						if($field == $key)
						{
							unset($fields[$k]);
						}
					}
					$schema[$table][SadoSchemaFile::KEY_MODEL_FIELDS] = $fields;
				}
			}
			if(count($schema) > 0)
			{
				$this->__writeCacheFile($schema_cache_file_path, $schema, $schema_hash, $instance_id);
			}
		}
	}

	/**
	 * Write schema cache file
	 *
	 * @param string $cache_file_path
	 * @param array $schema
	 * @param string $schema_hash
	 * @param int $instance_id
	 * @return void
	 */
private function __writeCacheFile($cache_file_path, $schema, $schema_hash, $instance_id)
	{
		$cache = NULL;
		if(is_array($schema))
		{
			$cache_ts = date('m/d/Y H:i:s');
			$cache_key = self::KEY_METADATA;
			$cache_key_schema = self::KEY_METADATA_SCHEMA;
			$cache = <<<C
<?php
/**
 * Sado Database Schema Cache File
 *
 * DO NOT EDIT unless re-caching
 *
 * Cached: {$cache_ts}
 *
 * @package Sado
 */
return array(
	/**
	 * DO NOT remove '{$cache_key}' array, set '{$cache_key_schema}' => '' for re-caching
	 */
	'{$cache_key}' => array(
		'{$cache_key_schema}' => '{$schema_hash}'
	)
C;
			ksort($schema);
			foreach($schema as $model_name => $model)
			{
				if(!empty($model_name) && is_array($model) && count($model) > 0)
				{
					$cache .= <<<C
,
	'{$model_name}' => array(

C;
					$i = 0;
					foreach($model as $k => $v)
					{
						$sep = $i == 0 ? NULL : <<<C
,

C;
						if(is_array($v))
						{
							$v = implode(', ', $v);
						}
						$cache .= <<<C
{$sep}		'{$k}' => '{$v}'
C;
						$i++;
					}
					$cache .= <<<C

	)
C;
				}
			}
			$cache .= <<<C
);
C;
		}
		if(!empty($cache))
		{
			SadoFactory::log("Writing to schema cache file \"{$cache_file_path}\"", $instance_id);
			file_put_contents($cache_file_path, $cache, LOCK_EX);
			$this->__cache = &$schema;
		}
	}

	/**
	 * Schema cache getter
	 *
	 * @return array ('table1' => array('id' => '', 'fields' => '') [, ...])
	 */
	public function getCache()
	{
		return $this->__cache;
	}

	/**
	 * Schema hash getter
	 *
	 * @param array $schema
	 * @return string
	 */
	public static function getSchemaHash(array $schema)
	{
		return md5(serialize($schema));
	}
}