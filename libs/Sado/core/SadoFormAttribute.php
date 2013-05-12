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
 * Sado ORM Form Attribute
 *
 * @package Sado
 * @name SadoFormAttribute
 * @author Shay Anderson 10.11
 */
final class SadoFormAttribute
{
	/**
	 * Format given attribute
	 *
	 * @param string $type
	 * @param mixed $value
	 * @param bool $value_required (if true will only return attribute if value is set)
	 */
	private static function __attribute($type = NULL, $value = NULL, $value_required = true)
	{
		if($value_required && $value !== NULL || !$value_required)
		{
			return " {$type}=\"{$value}\"";
		}
		return NULL;
	}

	/**
	 * Format action attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function action($value = NULL)
	{
		return self::__attribute('action', $value, false);
	}

	/**
	 * Format checked attribute
	 *
	 * @return string
	 */
	public static function checked()
	{
		return self::__attribute('checked', 'checked');
	}

	/**
	 * Format class attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function className($value = NULL)
	{
		return self::__attribute('class', $value);
	}

	/**
	 * Format enctype attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function enctype($value = NULL)
	{
		return self::__attribute('enctype', $value);
	}

	/**
	 * Format for attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function forName($value = NULL)
	{
		return self::__attribute('for', $value);
	}

	/**
	 * Format id attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function id($value = NULL)
	{
		return self::__attribute('id', $value);
	}

	/**
	 * Format maxlength attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function maxlength($value = NULL)
	{
		return self::__attribute('maxlength', $value);
	}

	/**
	 * Format multiple attribute
	 *
	 * @return string
	 */
	public static function multiple()
	{
		return self::__attribute('multiple', 'multiple');
	}

	/**
	 * Format method attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function method($value = NULL)
	{
		return self::__attribute('method', $value);
	}

	/**
	 * Format name attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function name($value = NULL)
	{
		return self::__attribute('name', $value);
	}

	/**
	 * Format onblur attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function onBlur($value = NULL)
	{
		return self::__attribute('onblur', $value);
	}

	/**
	 * Format onclick attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function onClick($value = NULL)
	{
		return self::__attribute('onclick', $value);
	}

	/**
	 * Format onfocus attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function onFocus($value = NULL)
	{
		return self::__attribute('onfocus', $value);
	}

	/**
	 * Format onsubmit attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function onSubmit($value = NULL)
	{
		return self::__attribute('onsubmit', $value);
	}

	/**
	 * Format placeholder attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function placeholder($value = NULL)
	{
		return self::__attribute('placeholder', $value);
	}

	/**
	 * Format readonly attribute
	 *
	 * @return string
	 */
	public static function readonly()
	{
		return self::__attribute('readonly', 'readonly');
	}

	/**
	 * Format selected attribute
	 *
	 * @return string
	 */
	public static function selected()
	{
		return self::__attribute('selected', 'selected');
	}

	/**
	 * Format size attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function size($value = NULL)
	{
		return self::__attribute('size', $value);
	}

	/**
	 * Format style attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function style($value = NULL)
	{
		return self::__attribute('style', $value);
	}

	/**
	 * Format tabindex attribute
	 *
	 * @param int $index
	 * @return string
	 */
	public static function tabindex($value = NULL)
	{
		return self::__attribute('tabindex', $value);
	}

	/**
	 * Format type attribute
	 *
	 * @param string $value
	 * @return string
	 */
	public static function type($value = NULL)
	{
		return self::__attribute('type', $value);
	}

	/**
	 * Format value attribute
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function value($value = NULL)
	{
		return self::__attribute('value', $value);
	}
}