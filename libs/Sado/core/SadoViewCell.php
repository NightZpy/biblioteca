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
 * Sado ORM View Cell Class
 *
 * @package Sado
 * @name SadoViewCell
 * @author Shay Anderson 10.11
 */
final class SadoViewCell
{
	/**
	 * Cell attributes
	 *
	 * @var string
	 */
	private $__attributes;

	/**
	 * Cell class name
	 *
	 * @var string
	 */
	private $__class_name;

	/**
	 * Cell span x cells
	 *
	 * @var int
	 */
	private $__span = 0;

	/**
	 * Cell style
	 *
	 * @var string
	 */
	private $__style;

	/**
	 * Cell value
	 *
	 * @var mixed
	 */
	private $__value;

	/**
	 * Set cell type
	 *
	 * @param int $type
	 * @param mixed $value
	 */
	public function __construct($value = NULL)
	{
		$this->__value = $value;
	}

	/**
	 * Attributes setter
	 *
	 * @param string $attributes
	 * @return SadoViewCell
	 */
	public function &attributes($attributes = NULL)
	{
		$this->__attributes = ' ' . $attributes;
		return $this;
	}

	/**
	 * Class name setter
	 *
	 * @param string $class_name
	 * @return SadoViewCell
	 */
	public function &className($class_name = NULL)
	{
		$this->__class_name = $class_name;
		return $this;
	}

	/**
	 * Cell HTML getter
	 *
	 * @return string
	 */
	public function getCell()
	{
		return '<td' . ( $this->__class_name ? " class=\"{$this->__class_name}\"" : NULL )
			. ( $this->__span > 0 ? " colspan=\"{$this->__span}\"" : NULL )
			. ( $this->__style ? " style=\"{$this->__style}\"" : NULL )
			. ( $this->__attributes ? ' ' . $this->__attributes : NULL ) . '>'
			. $this->__value . '</td>' . SadoFactory::getConf('html_eof_line');
	}

	/**
	 * Cell span setter
	 *
	 * @param int $span
	 * @return SadoViewCell
	 */
	public function &span($span = 0)
	{
		$this->__span = (int)$span;
		return $this;
	}

	/**
	 * Cell style setter
	 *
	 * @param mixed $style
	 * @return SadoViewCell
	 */
	public function &style($style = NULL)
	{
		$this->__style = $style;
		return $this;
	}
}