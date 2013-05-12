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
 * Sado ORM Grid Field Class
 *
 * @package Sado
 * @name SadoGridField
 * @author Shay Anderson 10.11
 */
final class SadoGridField
{
	/**
	 * Field attributes (ex: id, class, style, etc)
	 *
	 * @var string
	 */
	private $__attributes;

	/**
	 * Field title attributes
	 *
	 * @var string
	 */
	private $__attributes_title;

	/**
	 * Field filter
	 *
	 * @var string
	 */
	private $__filter;

	/**
	 * Field ID
	 *
	 * @var string
	 */
	private $__id;

	/**
	 * Field visible flag
	 *
	 * @var bool
	 */
	private $__is_visible = true;

	/**
	 * Field title
	 *
	 * @var string
	 */
	private $__title;

	/**
	 * Set field ID
	 *
	 * @param string $field_id
	 */
	public function __construct($field_id = NULL)
	{
		$this->__id = $field_id;
	}

	/**
	 * Field attributes setter
	 *
	 * @param string $attributes
	 * @return SadoGridField
	 */
	public function &attributes($attributes = NULL)
	{
		$this->__attributes .= ' ' . $attributes;
		return $this;
	}

	/**
	 * Field title attributes setter
	 *
	 * @param string $attributes
	 * @return SadoGridField
	 */
	public function &attributesTitle($attributes = NULL)
	{
		$this->__attributes_title .= ' ' . $attributes;
		return $this;
	}

	/**
	 * Field filter setter
	 *
	 * @param string $filter
	 * @return SadoGridField
	 */
	public function &filter($filter = NULL)
	{
		$this->__filter = $filter;
		return $this;
	}

	/**
	 * Field attributes getter
	 *
	 * @return string
	 */
	public function getAttributes()
	{
		return $this->__attributes;
	}

	/**
	 * Field title attributes getter
	 *
	 * @return string
	 */
	public function getAttributesTitle()
	{
		return $this->__attributes_title;
	}

	/**
	 * Field filter getter
	 *
	 * @return string
	 */
	public function getFilter()
	{
		return $this->__filter;
	}

	/**
	 * Field ID getter
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->__id;
	}

	/**
	 * Field title getter
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->__title;
	}

	/**
	 * Field visible off setter
	 *
	 * @return SadoGridField
	 */
	public function &hide()
	{
		$this->__is_visible = false;
		return $this;
	}

	/**
	 * Field is visible flag getter
	 *
	 * @return bool
	 */
	public function isVisible()
	{
		return $this->__is_visible;
	}

	/**
	 * Field visible on setter
	 *
	 * @return SadoGridField
	 */
	public function &show()
	{
		$this->__is_visible = true;
		return $this;
	}

	/**
	 * Field title setter
	 *
	 * @param string $title
	 * @return SadoGridField
	 */
	public function &title($title = NULL)
	{
		$this->__title = $title;
		return $this;
	}
}