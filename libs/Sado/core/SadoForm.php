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
 * Sado ORM Form Class
 *
 * @package Sado
 * @name SadoForm
 * @author Shay Anderson 10.11
 */
abstract class SadoForm
{
	/**
	 * Form field validators (SadoFormFieldValidator collection)
	 *
	 * @var array
	 */
	private $__field_validators = array();

	/**
	 * Form fields (SadoFormField collection)
	 *
	 * @var array
	 */
	private $__fields = array();

	/**
	 * Form HTML
	 *
	 * @var string
	 */
	private $__html;

	/**
	 * Form HTML foot
	 *
	 * @var string
	 */
	private $__html_foot;

	/**
	 * Form HTML head
	 *
	 * @var string
	 */
	private $__html_head;

	/**
	 * Form active flag
	 *
	 * @var bool
	 */
	private $__is_active = false;

	/**
	 * Auto save data to Sado connection flag
	 *
	 * @var bool
	 */
	private $__is_auto_save = true;

	/**
	 * Form posted flag
	 *
	 * @var bool
	 */
	private $__is_posted = false;

	/**
	 * Form valid flag for forcing header/footer errors
	 *
	 * @var bool
	 */
	private $__is_valid = true;

	/**
	 * Current Sado form ID
	 *
	 * @var int
	 */
	private static $__sado_form_id = 0;

	/**
	 * SadoORM object
	 *
	 * @var SadoORM
	 */
	private $__sado_orm;

	/**
	 * Save (insert) ID (generated if saved to DB)
	 *
	 * @var mixed
	 */
	private $__save_id;

	/**
	 * Form action
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * Form class name
	 *
	 * @var string
	 */
	protected $class_name;

	/**
	 * Form encoding type
	 *
	 * @var string
	 */
	protected $enctype;

	/**
	 * Form ID
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Form method (default post)
	 *
	 * @var string
	 */
	protected $method = 'post';

	/**
	 * Form onsubmit event
	 *
	 * @var string
	 */
	protected $onsubmit;

	/**
	 * Save field values with SadoORM object
	 *
	 * @return bool
	 */
	final private function __saveForm()
	{
		if($this->__sado_orm)
		{
			$fields_ready = false;
			foreach($this->__fields as $field)
			{
				if($this->__sado_orm->isField($field->getId()))
				{
					if(!$field->isReadonly())
					{
						if($this->__sado_orm->__set($field->getId(), $field->getValue()))
						{
							$fields_ready = true;
						}
					}
				}
			}
			if($fields_ready)
			{
				$saved = $this->__sado_orm->save() > 0;
				$this->__save_id = $this->__sado_orm->getId();
				return $saved;
			}
		}
		return false;
	}

	/**
	 * Field post valid setter
	 *
	 * @return void
	 */
	final private function __setFieldPostValues()
	{
		if(count($this->__fields))
		{
			foreach($this->__fields as $field)
			{
				if(isset($_POST[$field->getId()]))
				{
					$field->__setPostValue($_POST[$field->getId()]);
				}
			}
		}
	}

	/**
	 * Form HTML setter
	 *
	 * @return void
	 */
	final private function __setForm()
	{
		if(count($this->__fields) > 1 || $this->__html_head || $this->__html_foot) // more fields than default Sado form ID field, or html
		{
			foreach($this->__fields as $field)
			{
				if($this->isPosted() && isset($this->__field_validators[$field->getId()]))
				{
					if(!$this->__validateField($this->__field_validators[$field->getId()]))
					{
						$field->value($this->__field_validators[$field->getId()]->getMessage());
					}
				}
				$this->__html .= $field->getField();
				if($field->getType() == SadoFormField::TYPE_FILE)
				{
					$this->enctype = 'multipart/form-data';
				}
			}
			$this->__setFormHead();
			$this->__html .= $this->__html_foot . '</form>' . SadoFactory::getConf('html_eof_line');
		}
	}

	/**
	 * Form head HTML setter
	 *
	 * @return void
	 */
	final private function __setFormHead()
	{
		$this->__html = '<form' . SadoFormAttribute::id($this->id) . SadoFormAttribute::name($this->id)
			. SadoFormAttribute::action($this->action) . SadoFormAttribute::method($this->method)
			. SadoFormAttribute::onSubmit($this->onsubmit) . SadoFormAttribute::enctype($this->enctype)
			. SadoFormAttribute::className( $this->class_name ? $this->class_name
			: SadoFactory::getConf('form_default_class') )
			. '>' . SadoFactory::getConf('html_eof_line') . $this->__html_head . $this->__html;
	}

	/**
	 * Validate form field values
	 *
	 * @return bool
	 */
	final private function __validate()
	{
		if(count($this->__field_validators))
		{
			foreach($this->__field_validators as $validator)
			{
				if(!$this->__validateField($validator))
				{
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Validate form field value
	 *
	 * @param SadoFormFieldValidator $validator
	 * @return bool
	 */
	final private function __validateField(SadoFormFieldValidator $validator)
	{
		if(isset($this->__fields[$validator->getFieldId()]))
		{
			return $validator->isValid($this->__fields[$validator->getFieldId()]->getValue());
		}
		return true; // always return true unless validation fails
	}

	/**
	 * Activate form - method should be called after field setup and before getForm() method is called
	 *
	 * @return void
	 */
	final protected function activate()
	{
		if(!$this->__is_active)
		{
			$this->__is_active = true;
			self::$__sado_form_id++;
			$sado_form_id = md5($this->id . '-' . self::$__sado_form_id);
			$this->fieldHidden(SadoFactory::getConf('form_id_field_name'))->value($sado_form_id);
			// check if posted and if current form
			if($this->isPosted() && isset($_POST[SadoFactory::getConf('form_id_field_name')])
				&& $_POST[SadoFactory::getConf('form_id_field_name')] == $sado_form_id)
			{
				$this->__setFieldPostValues();
				$this->onPost();
				if($this->__validate())
				{
					$this->onValidate();
					if($this->__is_valid)
					{
						if($this->__is_auto_save && $this->__saveForm())
						{
							$this->onSave();
						}
						else
						{
							$this->onSaveFail();
						}
						return;
					}
				}
				$this->onValidateFail();
			}
		}
	}

	/**
	 * Auto save data to Sado connection flag setter
	 *
	 * @param bool $auto_save
	 */
	final protected function autoSave($auto_save = true)
	{
		$this->__is_auto_save = (bool)$auto_save;
	}

	/**
	 * Form field setter
	 *
	 * @param string $field_id
	 * @param bool $auto_value (will auto set field value with SadoORM field value if true)
	 * @return SadoFormField
	 */
	final protected function &field($field_id = NULL, $auto_value = true)
	{
		if(!$field_id)
		{
			$field_id = count($this->__fields); // auto set ID
		}
		if(!isset($this->__fields[$field_id])) // check if new form field
		{
			$this->__fields[$field_id] = new SadoFormField($field_id);
		}
		if($auto_value && $this->__sado_orm && $this->__sado_orm->isField($field_id)
			&& !$this->__fields[$field_id]->isPostValueSet())
		{
			$this->__fields[$field_id]->value($this->__sado_orm->__get($field_id));
		}
		return $this->__fields[$field_id];
	}

	/**
	 * Form button field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldButton($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_BUTTON);
	}

	/**
	 * Form checkbox field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldCheckbox($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_CHECKBOX);
	}

	/**
	 * Form file field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldFile($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_FILE);
	}

	/**
	 * Form hidden field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldHidden($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_HIDDEN);
	}

	/**
	 * Form password field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldPassword($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_PASSWORD)
			->autoPostValue(false); // no auto post value by default
	}

	/**
	 * Form radio field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldRadio($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_RADIO);
	}

	/**
	 * Form select field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldSelect($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_SELECT);
	}

	/**
	 * Form submit field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldSubmit($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_SUBMIT);
	}

	/**
	 * Form text field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldText($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_TEXT);
	}

	/**
	 * Form textarea field setter
	 *
	 * @param string $field_id
	 * @return SadoFormField
	 */
	final protected function &fieldTextarea($field_id = NULL)
	{
		return $this->field($field_id)
			->type(SadoFormField::TYPE_TEXTAREA);
	}

	/**
	 * Flush field (will not allow post values for field, disallows user from adding client-side fields with values)
	 *
	 * @param mixed $field_id (string|array)
	 * @return void
	 */
	final protected function flushField($field_id = '')
	{
		if(is_array($field_id))
		{
			foreach($field_id as $id)
			{
				$this->flushField($id);
			}
		}
		else if(isset($this->__fields[$field_id]))
		{
			unset($this->__fields[$field_id]);
		}
	}

	/**
	 * Form HTML getter
	 *
	 * @return string
	 */
	final public function getForm()
	{
		if(!$this->__is_active)
		{
			return 'Notice: form must be activated (' . __CLASS__
				. ' activate() method) before calling this method.';
		}
		if(!$this->__html)
		{
			$this->__setForm();
		}
		return $this->__html;
	}

	/**
	 * SadoORM getter
	 *
	 * @return SadoORM
	 */
	final protected function &getSadoOrm()
	{
		return $this->__sado_orm;
	}

	/**
	 * Get save (insert) ID
	 *
	 * @return mixed
	 */
	final public function getSaveId()
	{
		return $this->__save_id;
	}

	/**
	 * Add HTML/text for form
	 *
	 * @param string $html
	 * @return void
	 */
	final protected function html($html = NULL)
	{
		$this->field()
			->type(SadoFormField::TYPE_HTML)
			->value($html);
	}

	/**
	 * Add HTML/text form foot
	 *
	 * @param string $html
	 * @return void
	 */
	final protected function htmlFoot($html = NULL)
	{
		$this->__html_foot .= $html;
	}

	/**
	 * Add HTML/text form head
	 *
	 * @param string $html
	 * @return void
	 */
	final protected function htmlHead($html = NULL)
	{
		$this->__html_head .= $html;
	}

	/**
	 * Check if form is posted
	 *
	 * @return bool
	 */
	final protected function isPosted()
	{
		if(!$this->__is_posted)
		{
			$this->__is_posted = $_SERVER['REQUEST_METHOD'] == 'POST';
		}
		return $this->__is_posted;
	}

	/**
	 * SadoORM object setter
	 *
	 * @param SadoORM $sado_orm_object
	 * @return void
	 */
	final protected function model(SadoORM &$sado_orm_object)
	{
		$this->__sado_orm = &$sado_orm_object;
	}

	/**
	 * Called on form posted
	 *
	 * @return void
	 */
	protected function onPost()
	{
		// overridable
	}

	/**
	 * Called on form saved
	 *
	 * @return void
	 */
	protected function onSave()
	{
		// overridable
	}

	/**
	 * Called on failed for save
	 *
	 * @return void
	 */
	protected function onSaveFail()
	{
		// overridable
	}

	/**
	 * Called on form posted and field values pass all validation rules
	 *
	 * @return void
	 */
	protected function onValidate()
	{
		// overridable
	}

	/**
	 * Called on form posted and field values failed validation rule(s)
	 *
	 * @return void
	 */
	protected function onValidateFail()
	{
		// overridable
	}

	/**
	 * Field validate rule setter
	 *
	 * @param string $field_id
	 * @param string $fail_message
	 * @return SadoFormFieldValidator
	 */
	final protected function &validate($field_id = NULL, $fail_message = NULL)
	{
		$field = $this->field()
			->type(SadoFormField::TYPE_HTML);
		$this->__field_validators[$field->getId()] = new SadoFormFieldValidator($field_id);
		$this->__field_validators[$field->getId()]->message($fail_message);
		return $this->__field_validators[$field->getId()];
	}

	/**
	 * Force footer error
	 *
	 * @param string $error_message
	 * @param bool $field_validator_wrapper
	 * @return void
	 */
	final protected function validateForceFooterError($error_message = '', $field_validator_wrapper = true)
	{
		if(strlen(trim($error_message)) > 0)
		{
			$this->__is_valid = false;
			$this->htmlHead(( $field_validator_wrapper ? SadoFactory::getConf('form_field_validator_wrapper_open') : NULL ) . $error_message
				. ( $field_validator_wrapper ? SadoFactory::getConf('form_field_validator_wrapper_close') : NULL ));
		}
	}

	/**
	 * Force header error
	 *
	 * @param string $error_message
	 * @param bool $field_validator_wrapper
	 * @return void
	 */
	final protected function validateForceHeaderError($error_message = '', $field_validator_wrapper = true)
	{
		if(strlen(trim($error_message)) > 0)
		{
			$this->__is_valid = false;
			$this->htmlHead(( $field_validator_wrapper ? SadoFactory::getConf('form_field_validator_wrapper_open') : NULL ) . $error_message
				. ( $field_validator_wrapper ? SadoFactory::getConf('form_field_validator_wrapper_close') : NULL ));
		}
	}
}