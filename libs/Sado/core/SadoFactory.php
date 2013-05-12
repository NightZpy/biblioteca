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
 * Sado Instance Factory
 *
 * @package Sado
 * @name SadoFactory
 * @author Shay Anderson 10.11
 */
final class SadoFactory
{
	/**
	 * Driver types
	 */
	const DRIVER_MYSQLI = 0;

	/**
	 * Global error types
	 */
	const ERROR_TYPE = E_USER_ERROR;

	/**
	 * Autoload directories
	 *
	 * @var array
	 */
	private static $__autoload_dirs = array(
		'core',
	);

	/**
	 * Sado configuration settings
	 *
	 * @var array
	 */
	private static $__conf = array();

	/**
	 * Sado library directory
	 *
	 * @var string
	 */
	private static $__dir_sado_lib;

	/**
	 * Sado drivers
	 *
	 * @var array
	 */
	private static $__drivers = array(
		self::DRIVER_MYSQLI => 'Mysqli'
	);

	/**
	 * Sado driver instances collection
	 *
	 * @var array
	 */
	private static $__instances = array();

	/**
	 * Finalized flag
	 *
	 * @var bool
	 */
	private static $__is_finalized = false;

	/**
	 * Log entries
	 *
	 * @var array
	 */
	private static $__log = array();

	/**
	 * Do nothing
	 */
	private function __construct(){}

	/**
	 * Autoloading classes
	 *
	 * @param string $class_name
	 * @return bool
	 */
	public static function autoload($class_name = NULL)
	{
		if(self::$__dir_sado_lib === NULL)
		{
			return false;
		}
		foreach(self::$__autoload_dirs as $dir)
		{
			$file = self::$__dir_sado_lib . str_replace('/', DIRECTORY_SEPARATOR, $dir) . DIRECTORY_SEPARATOR
				. $class_name . self::getConf('file_lib_ext');
			if(file_exists($file))
			{
				require_once $file;
				return true;
			}
		}
		return false;
	}

	/**
	 * Load configuration file
	 *
	 * @param string $conf_file_path
	 * @return bool
	 */
	public static function confFile($conf_file_path)
	{
		if(!is_file($conf_file_path))
		{
			self::error("Failed to load configuration file \"{$conf_file_path}\"", __CLASS__);
			return false;
		}
		self::$__conf = require $conf_file_path;
		if(!is_array(self::$__conf))
		{
			self::$__conf = array();
			trigger_error("Failed to load configuration file \"{$conf_file_path}\", invalid format (file must return array)",
				E_USER_ERROR);
			return false;
		}
		return true;
	}

	/**
	 * Trigger error
	 *
	 * @param string $error_message
	 * @param string $class_name
	 * @param string $instance_id_str
	 * @param bool $log_err
	 * @return void
	 */
	public static function error($error_message = NULL, $class_name = NULL, $instance_id = 0, $log_err = true)
	{
		if(!empty($error_message))
		{
			$error_message = 'Sado: ' . ( !empty($class_name) ? $class_name . ': ' : NULL )
					. $error_message . ( (int)$instance_id > 0 ? " (Sado instance {$instance_id})" : NULL );
			if($log_err)
			{
				self::log($error_message);
			}
			if(is_array(self::getConf('udf_error_handler')) && count(self::getConf('udf_error_handler')) > 0
				|| strlen(self::getConf('udf_error_handler')) > 0 ) // user defined error handler
			{
				if(is_callable(self::getConf('udf_error_handler')))
				{
					call_user_func(self::getConf('udf_error_handler'), $error_message);
					return;
				}
				else
				{
					if(self::getConf('display_errors'))
					{
						trigger_error('Failed to call user defined error handler ("' . ( is_array(self::getConf('udf_error_handler'))
							? implode('-', self::getConf('udf_error_handler')) : self::getConf('udf_error_handler') ) . '")',
							self::ERROR_TYPE);
					}
				}
			}
			if(self::getConf('display_errors'))
			{
				self::finalize();
				trigger_error($error_message, self::ERROR_TYPE);
			}
		}
	}

	/**
	 * Deconstructor - finalize connections
	 *
	 * @return void
	 */
	public static function finalize()
	{
		if(!self::$__is_finalized)
		{
			if(count(self::$__instances) > 0)
			{
				foreach(self::$__instances as $instance)
				{
					$instance[0]->close();
				}
			}
			if(self::getConf('dump_log') && count(self::$__log) > 0)
			{
				echo '<pre>' . print_r(self::$__log, true) . '</pre>';
			}
			self::$__is_finalized = true;
		}
	}

	/**
	 * Configuration getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function getConf($key)
	{
		if(array_key_exists($key, self::$__conf))
		{
			return self::$__conf[$key];
		}
		else if(isset(self::$__conf['display_errors']) && self::$__conf['display_errors'])
		{
			self::error("Failed to get configuration setting value, invalid configuration key \"{$key}\"", __CLASS__);
		}
	}

	/**
	 * Sado driver instance getter
	 *
	 * @param int $instance_id
	 * @return Sado
	 */
	public static function &getInstance($instance_id = 1)
	{
		if(isset(self::$__instances[$instance_id]))
		{
			return self::$__instances[$instance_id][0];
		}
		else
		{
			self::error("Failed to get Sado instance with ID \"{$instance_id}\", instance ID cannot be found", __CLASS__);
		}
		$a = false;
		return $a;
	}

	/**
	 * Sado driver instance configuration getter
	 *
	 * @param int $instance_id
	 * @return SadoFactoryInstanceConf
	 */
	public static function getInstanceConf($instance_id = 1)
	{
		if(isset(self::$__instances[$instance_id]))
		{
			return self::$__instances[$instance_id][1];
		}
		else
		{
			self::error("Failed to get Sado instance configuration, instance ID \"{$instance_id}\" cannot be found", __CLASS__);
		}
	}

	/**
	 * Debug log getter
	 *
	 * @return array
	 */
	public static function getLog()
	{
		return self::$__log;
	}

	/**
	 * Sado driver instance schema file getter
	 *
	 * @param int $instance_id
	 * @return SadoSchemaFile
	 */
	public static function &getSchemaFile($instance_id = 1)
	{
		return self::getInstanceConf($instance_id)->getSchemaFile();
	}

	/**
	 * Logger
	 *
	 * @param string $message
	 * @param int $instance_id
	 * @return void
	 */
	public static function log($message = NULL, $instance_id = 0)
	{
		if(self::getConf('debug') && $message !== NULL)
		{
			$label = NULL;
			if(is_int($instance_id) && $instance_id > 0 && isset(self::$__instances[$instance_id]))
			{
				if(self::$__instances[$instance_id][1]->getName() !== NULL)
				{
					$label = self::$__instances[$instance_id][1]->getName() . " (Instance {$instance_id})";
				}
				else
				{
					$label = 'Instance ' . $instance_id;
				}
			}
			$message = ( $label !== NULL ? "{$label}: " : NULL ) . $message;
			if(is_array(self::getConf('udf_log_handler')) && count(self::getConf('udf_log_handler')) > 0
				|| strlen(self::getConf('udf_log_handler')) > 0 ) // user defined log handler
			{
				if(is_callable(self::getConf('udf_log_handler')))
				{
					call_user_func(self::getConf('udf_log_handler'), $message);
					return;
				}
				else
				{
					self::error('Failed to call user defined log handler ("' . ( is_array(self::getConf('udf_log_handler'))
						? implode('-', self::getConf('udf_log_handler')) : self::getConf('udf_log_handler') ) . '")',
						NULL, NULL, false);
				}
			}
			self::$__log[] = $message;
		}
	}

	/**
	 * Setup class autoloading
	 *
	 * @param string $dir_sado_lib
	 * @return void
	 */
	public static function registerAutoload($dir_sado_lib = NULL)
	{
		self::$__dir_sado_lib = $dir_sado_lib;
		spl_autoload_register(array('SadoFactory', 'autoload'));
	}

	/**
	 * Sado instance setter
	 *
	 * @param int $driver (SadoFactory::DRIVER_[x])
	 * @return SadoFactoryInstanceConf
	 */
	public static function &registerInstance($driver)
	{
		$a = false;
		if(count(self::$__conf) < 1)
		{
			trigger_error('Failed to register instance, configuration file must be loaded before registering instance',
				self::ERROR_TYPE);
			return $a;
		}
		if(isset(self::$__drivers[$driver]))
		{
			$instance_id = count(self::$__instances) + 1;
			$driver_name = 'SadoDriver' . self::$__drivers[$driver];
			self::$__instances[$instance_id] = array(
				new $driver_name(),
				new SadoFactoryInstanceConf($instance_id)
			);
			self::$__instances[$instance_id][0]->setInstanceId($instance_id);
			return self::$__instances[$instance_id][1];
		}
		else
		{
			self::error("Failed to register instance, invalid driver type \"{$driver}\"", __CLASS__);
		}
		return $a;
	}

	/**
	 * Compress value
	 *
	 * @param mixed $value
	 * @param int $level
	 * @return string
	 */
	public static function valueCompress($value = NULL, $level = 9)
	{
		return gzcompress($value, (int)$level);
	}

	/**
	 * Uncompress value
	 *
	 * @param string $value
	 * @return mixed
	 */
	public static function valueUncompress($value = NULL)
	{
		return gzuncompress($value);
	}
}