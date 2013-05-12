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
 * Sado Bootstrap
 *
 * @package Sado
 */

/**
 * ==============================================================================
 * Environment settings
 * ==============================================================================
 * Set Sado lib directory, include trailing slash '/'
 * static example: '/var/www/example/lib/Sado/'
 */
$dir_sado_lib = dirname(__FILE__) . '/';

// include SadoFactory class
require_once $dir_sado_lib . 'core'.DS.'SadoFactory.php';


/**
 * ==============================================================================
 * Autoloading
 * ==============================================================================
 * Set autoloading, if using another autoloading method comment out autoloader
 * other autoloaders should autoload the directory: [dir_sado_lib]core/
 */
SadoFactory::registerAutoload($dir_sado_lib);


/**
 * ==============================================================================
 * Sado configuration
 * ==============================================================================
 * Load configuration file
 */
SadoFactory::confFile($dir_sado_lib . 'sado.conf.php');


/**
 * ==============================================================================
 * Register Sado instances
 * ==============================================================================
 * Register instance 1
 */
SadoFactory::registerInstance(SadoFactory::DRIVER_MYSQLI)
	->host('localhost')
	->username('root')
	->password('18990567')
	->database('biblioteca')
	->name('Biblioteca')
	->schemaFile($dir_sado_lib . 'sado.schema.php');

/**
 * Second connection (instance 2) example:
 * SadoFactory::registerInstance(SadoFactory::DRIVER_MYSQLI)
 *		->host('192.168.1.200')
 *		->username('root')
 *		->password('secret')
 *		->database('testdb')
 *		->name('Second DB Connection')
 *		->schemaFile($dir_sado_lib . 'sado.schema.2.php');
 */