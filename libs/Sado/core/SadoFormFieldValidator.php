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
 * Sado ORM Form Field Validator
 *
 * @package Sado
 * @name SadoFormFieldValidator
 * @author Shay Anderson 10.11
 */
final class SadoFormFieldValidator
{
	/**
	 * Rule types
	 */
	const RULE_ALPHA = 0;
	const RULE_ALPHANUMERIC = 1;
	const RULE_EMAIL = 2;
	const RULE_LENGTH = 3;
	const RULE_NOT_NULL = 4;
	const RULE_NUMERIC = 5;
	const RULE_NUMERIC_GREATER_THAN_ZERO = 6;
	const RULE_REGEX = 7;

	/**
	 * Form field ID
	 *
	 * @var mixed
	 */
	private $__field_id;

	/**
	 * Failed is valid flag
	 *
	 * @var bool
	 */
	private $__is_valid = true;

	/**
	 * Field validated flag
	 *
	 * @var bool
	 */
	private $__is_validated = false;

	/**
	 * Validation fail/error message
	 *
	 * @var string
	 */
	private $__message;

	/**
	 * Field validation rules
	 *
	 * @var array ((type, value, value2), [...])
	 */
	private $__rules = array();

	/**
	 * Set field ID
	 *
	 * @param mixed $field_id
	 */
	public function __construct($field_id = NULL)
	{
		$this->__field_id = $field_id;
	}

	/**
	 * Field validation rule setter
	 *
	 * @param int $type
	 * @param mixed $value
	 * @param mixed $value2
	 * @return void
	 */
	private function __rule($type = 0, $value = NULL, $value2 = NULL)
	{
		$this->__rules[] = array(
			'type' => $type,
			'value' => $value,
			'value2' => $value2
		);
	}

	/**
	 * Validate field value
	 *
	 * @param mixed $field_value
	 * @return void
	 */
	private function __validate($field_value = NULL)
	{
		if(count($this->__rules))
		{
			foreach($this->__rules as $rule)
			{
				// only keep validating if still valid
				if($this->__is_valid)
				{
					switch($rule['type'])
					{
						case self::RULE_ALPHA:
							$this->__is_valid = ctype_alpha($field_value);
							break;
						case self::RULE_ALPHANUMERIC:
							$this->__is_valid = ctype_alnum($field_value);
							break;
						case self::RULE_EMAIL:
							$this->__is_valid = preg_match('/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.'
								. '[a-zA-Z]{2,4}$/', $field_value);
							break;
						case self::RULE_LENGTH:
							$this->__is_valid = ( $rule['value'] > 0 ? strlen($field_value) >= $rule['value'] : true ) &&
								( $rule['value2'] > 0 ? strlen($field_value) <= $rule['value2'] : true );
							break;
						case self::RULE_NOT_NULL:
							$this->__is_valid = strlen($field_value) > 0;
							break;
						case self::RULE_NUMERIC:
							$this->__is_valid = ctype_digit($field_value);
							break;
						case self::RULE_NUMERIC_GREATER_THAN_ZERO:
							$this->__is_valid = ctype_digit($field_value) && (int)$field_value > 0;
							break;
						case self::RULE_REGEX:
							$this->__is_valid = preg_match($rule['value'], $field_value);
							break;
					}
				}
			}
		}
		$this->__is_validated = true;
	}

	/**
	 * Force validation fail (for manual validation)
	 *
	 * @return SadoFormFieldValidator
	 */
	public function &force()
	{
		$this->__is_valid = false;
		$this->__is_validated = true;
		return $this;
	}

	/**
	 * Field ID getter
	 *
	 * @return string
	 */
	public function getFieldId()
	{
		return $this->__field_id;
	}

	/**
	 * Field fail/error getter
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->__message;
	}

	/**
	 * Check if validation passed
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function isValid($value = NULL)
	{
		if(!$this->__is_validated)
		{
			$this->__validate($value);
		}
		return $this->__is_valid;
	}

	/**
	 * Field fail/error message setter
	 *
	 * @param string $fail_message
	 * @return SadoFormFieldValidator
	 */
	public function &message($fail_message = NULL)
	{
		$this->__message = SadoFactory::getConf('form_field_validator_wrapper_open') . $fail_message
			. SadoFactory::getConf('form_field_validator_wrapper_close');
		return $this;
	}

	/**
	 * Field validation alpha (alphabetic characters) rule setter
	 *
	 * @return SadoFormFieldValidator
	 */
	public function &ruleAlpha()
	{
		$this->__rule(self::RULE_ALPHA);
		return $this;
	}

	/**
	 * Field validation alphanumeric rule setter
	 *
	 * @return SadoFormFieldValidator
	 */
	public function &ruleAlphanumeric()
	{
		$this->__rule(self::RULE_ALPHANUMERIC);
		return $this;
	}

	/**
	 * Field validation email rule setter
	 *
	 * @return SadoFormFieldValidator
	 */
	public function &ruleEmail()
	{
		$this->__rule(self::RULE_EMAIL);
		return $this;
	}

	/**
	 * Field validation length rule setter
	 *
	 * @param int $min
	 * @param int $max
	 * @return SadoFormFieldValidator
	 */
	public function &ruleLength($min = 0, $max = 0)
	{
		$this->__rule(self::RULE_LENGTH, (int)$min, (int)$max);
		return $this;
	}

	/**
	 * Field validation not null rule setter
	 *
	 * @return SadoFormFieldValidator
	 */
	public function &ruleNotNull()
	{
		$this->__rule(self::RULE_NOT_NULL);
		return $this;
	}

	/**
	 * Field validation numeric rule setter
	 *
	 * @return SadoFormFieldValidator
	 */
	public function &ruleNumeric()
	{
		$this->__rule(self::RULE_NUMERIC);
		return $this;
	}

	/**
	 * Field validation numeric rule setter
	 *
	 * @return SadoFormFieldValidator
	 */
	public function &ruleNumericGreaterThanZero()
	{
		$this->__rule(self::RULE_NUMERIC_GREATER_THAN_ZERO);
		return $this;
	}

	/**
	 * Field validation reg ex rule setter
	 *
	 * @param string $reg_ex_pattern
	 * @return SadoFormFieldValidator
	 */
	public function &ruleRegEx($reg_ex_pattern)
	{
		$this->__rule(self::RULE_REGEX, $reg_ex_pattern);
		return $this;
	}
}