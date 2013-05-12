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
 * Sado Model Field Class
 *
 * @package Sado
 * @name SadoModelField
 * @author Shay Anderson 10.11
 */
final class SadoModelField
{
	/**
	 * Modified value flag
	 *
	 * @var bool
	 */
	private $__is_modified = false;

	/**
	 * Field name
	 *
	 * @var string
	 */
	private $__name;

	/**
	 * Field value
	 *
	 * @var mixed
	 */
	private $__value;

	/**
	 * Set field name
	 *
	 * @param string $name
	 */
	public function __construct($name = NULL)
	{
		$this->__name = $name;
	}

	/**
	 * Field value getter
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->__value;
	}

	/**
	 * Value modified flag getter
	 *
	 * @return bool
	 */
	public function isModified()
	{
		return $this->__is_modified;
	}

	/**
	 * Value modified flag setter
	 *
	 * @param bool $modified
	 * @return void
	 */
	public function setModified($modified = true)
	{
		$this->__is_modified = (bool)$modified;
	}

	/**
	 * Field value setter
	 *
	 * @param mixed $value
	 * @return void
	 */
	public function setValue($value = NULL)
	{
		$this->__value = $value;
	}
}