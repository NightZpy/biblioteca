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
 * Sado Factory Instance Configuration
 *
 * @package Sado
 * @name SadoFactoryInstanceConf
 * @author Shay Anderson 10.11
 */
final class SadoFactoryInstanceConf
{
	/**
	 * DB name
	 *
	 * @var string
	 */
	private $__database;

	/**
	 * DB Host
	 *
	 * @var string
	 */
	private $__host;

	/**
	 * Instance ID
	 *
	 * @var int
	 */
	private $__instance_id = 0;

	/**
	 * Instance name/title
	 *
	 * @var string
	 */
	private $__name;

	/**
	 * DB password
	 *
	 * @var string
	 */
	private $__password;

	/**
	 * DB port
	 *
	 * @var int
	 */
	private $__port;

	/**
	 * Schema file object
	 *
	 * @var SadoSchemaFile
	 */
	private $__schema_file;

	/**
	 * DB username
	 *
	 * @var string
	 */
	private $__username;

	/**
	 * Init
	 *
	 * @param int $instance_id
	 */
	public function __construct($instance_id)
	{
		$this->__instance_id = (int)$instance_id;
	}

	/**
	 * Database name getter
	 *
	 * @return string
	 */
	public function getDatabase()
	{
		return $this->__database;
	}

	/**
	 * Host getter
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->__host;
	}

	/**
	 * Name getter
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->__name;
	}

	/**
	 * Password getter
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->__password;
	}

	/**
	 * Port getter
	 *
	 * @return string
	 */
	public function getPort()
	{
		return $this->__port;
	}

	/**
	 * Schema file object getter
	 *
	 * @return SadoSchemaFile
	 */
	public function &getSchemaFile()
	{
		return $this->__schema_file;
	}

	/**
	 * Username getter
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->__username;
	}

	/**
	 * Database setter
	 *
	 * @param string $database
	 * @return SadoFactoryInstanceConf
	 */
	public function &database($database = NULL)
	{
		$this->__database = $database;
		return $this;
	}

	/**
	 * Host setter
	 *
	 * @param string $host
	 * @return SadoFactoryInstanceConf
	 */
	public function &host($host = NULL)
	{
		$this->__host = $host;
		return $this;
	}

	/**
	 * Name setter
	 *
	 * @param string $name
	 * @return SadoFactoryInstanceConf
	 */
	public function &name($name = NULL)
	{
		$this->__name = $name;
		return $this;
	}

	/**
	 * Password setter
	 *
	 * @param string $password
	 * @return SadoFactoryInstanceConf
	 */
	public function &password($password = NULL)
	{
		$this->__password = $password;
		return $this;
	}

	/**
	 * Port setter
	 *
	 * @param int $port
	 * @return SadoFactoryInstanceConf
	 */
	public function &port($port)
	{
		$this->__port = (int)$port;
		return $this;
	}

	/**
	 * Schema file setter
	 *
	 * @param string $schema_file_path
	 * @return SadoFactoryInstanceConf
	 */
	public function &schemaFile($schema_file_path)
	{
		$this->__schema_file = new SadoSchemaFile($schema_file_path, $this->__instance_id);
		return $this;
	}

	/**
	 * Username setter
	 *
	 * @param string $username
	 * @return SadoFactoryInstanceConf
	 */
	public function &username($username = NULL)
	{
		$this->__username = $username;
		return $this;
	}
}