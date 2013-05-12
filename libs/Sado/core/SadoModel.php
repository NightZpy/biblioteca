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
 * Sado Model Class
 *
 * @package Sado
 * @name SadoModel
 * @author Shay Anderson 10.11
 */
final class SadoModel
{
	/**
	 * Model fields
	 *
	 * @var array (collection of SadoModelField objects)
	 */
	private $__fields = array();

	/**
	 * Model key field name
	 *
	 * @var string
	 */
	private $__key;

	/**
	 * Model name
	 *
	 * @var string
	 */
	private $__name;

	/**
	 * Field value getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name = NULL)
	{
		return $this->isField($name) ? $this->__fields[$name]->getValue() : NULL;
	}

	/**
	 * Field value setter
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return bool
	 */
	public function __set($name = NULL, $value = NULL)
	{
		if($this->isField($name))
		{
			$this->__fields[$name]->setValue($value);
			return true;
		}
		return false;
	}

	/**
	 * Add model field
	 *
	 * @param string $name
	 * @return void
	 */
	public function addField($name = NULL)
	{
		if(!$this->isField($name) && is_string($name))
		{
			$this->__fields[$name] = new SadoModelField($name);
		}
	}

	/**
	 * Get field names
	 *
	 * @return array
	 */
	public function getFields()
	{
		return array_keys($this->__fields);
	}

	/**
	 * Get field names with values
	 *
	 * @param array $fields (optional, only return specific fields)
	 * @return array (field1 => value, field2 => value, [...])
	 */
	public function getFieldsAndValues($fields = array())
	{
		$fields_and_values = array();
		foreach($this->__fields as $name => $field)
		{
			if(is_array($fields) && count($fields) > 0)
			{
				if(in_array($name, $fields))
				{
					$fields_and_values[$name] = $field->getValue();
				}
			}
			else
			{
				$fields_and_values[$name] = $field->getValue();
			}
		}
		return $fields_and_values;
	}

	/**
	 * Get model key
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->__key;
	}

	/**
	 * Get model name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->__name;
	}

	/**
	 * Check if field exists
	 *
	 * @param mixed $name
	 * @return bool
	 */
	public function isField($name = NULL)
	{
		return isset($this->__fields[$name]);
	}

	/**
	 * Check if field is modified
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isFieldModified($name = NULL)
	{
		return $this->isField($name) && $this->__fields[$name]->isModified();
	}

	/**
	 * Remove model field
	 *
	 * @param string $name
	 * @return void
	 */
	public function remField($name = '')
	{
		if($this->isField($name))
		{
			unset($this->__fields[$name]);
		}
	}

	/**
	 * Model field value modified flag setter
	 *
	 * @param string $name
	 * @param bool $modified
	 * @return void
	 */
	public function setFieldModified($name = NULL, $modified = true)
	{
		if($this->isField($name))
		{
			$this->__fields[$name]->setModified($modified);
		}
	}

	/**
	 * Model key setter
	 *
	 * @param string $key
	 * @return void
	 */
	public function setKey($key = NULL)
	{
		$this->__key = $key;
		$this->addField($this->__key);
	}

	/**
	 * Model name setter
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name = NULL)
	{
		$this->__name = $name;
	}
}