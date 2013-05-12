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
 * Sado ORM Form Field Class
 *
 * @package Sado
 * @name SadoFormField
 * @author Shay Anderson 10.11
 */
final class SadoFormField
{
	/**
	 * Field types
	 */
	const TYPE_BUTTON = 1;
	const TYPE_CHECKBOX = 2;
	const TYPE_FILE = 3;
	const TYPE_HIDDEN = 4;
	const TYPE_HTML = 5;
	const TYPE_PASSWORD = 6;
	const TYPE_RADIO = 7;
	const TYPE_SELECT = 8;
	const TYPE_SUBMIT = 9;
	const TYPE_TEXT = 10;
	const TYPE_TEXTAREA = 11;

	/**
	 * Manual attributes
	 *
	 * @var string
	 */
	private $__attributes;

	/**
	 * Class name
	 *
	 * @var string
	 */
	private $__class;

	/**
	 * Default field classes
	 *
	 * @var array
	 */
	private static $__classes = array();

	/**
	 * Field HTML
	 *
	 * @var string
	 */
	private $__html;

	/**
	 * Field ID
	 *
	 * @var mixed
	 */
	private $__id;

	/**
	 * Auto set post value flag
	 *
	 * @var bool
	 */
	private $__is_auto_post_value = true;

	/**
	 * Multiple field select flag
	 *
	 * @var bool
	 */
	private $__is_multiple = false;

	/**
	 * Post value is set flag
	 *
	 * @var bool
	 */
	private $__is_post_value_set = false;

	/**
	 * Readonly field flag
	 *
	 * @var bool
	 */
	private $__is_readonly = false;

	/**
	 * Field label
	 *
	 * @var array
	 */
	private $__label = array(
		'text' => NULL,
		'class' => NULL,
		'attributes' => NULL
	);

	/**
	 * Field maxlength attribute
	 *
	 * @var int
	 */
	private $__maxlength = 0;

	/**
	 * onblur event
	 *
	 * @var string
	 */
	private $__onblur;

	/**
	 * onclick event
	 *
	 * @var string
	 */
	private $__onclick;

	/**
	 * onfocus event
	 *
	 * @var string
	 */
	private $__onfocus;

	/**
	 * Field options used for select, radio
	 *
	 * @var array
	 */
	private $__options = array();

	/**
	 * Field placeholder text
	 *
	 * @var string
	 */
	private $__placeholder;

	/**
	 * Field selected option (allowed single select [0] or multiple selects [0], [1], [...])
	 *
	 * @var array
	 */
	private $__selected = array();

	/**
	 * Field size
	 *
	 * @var int
	 */
	private $__size = 0;

	/**
	 * Style attribute
	 *
	 * @var string
	 */
	private $__style;

	/**
	 * tabindex attribute
	 *
	 * @var int
	 */
	private $__tabindex = 0;

	/**
	 * Field type (default is text)
	 *
	 * @var string
	 */
	private $__type = self::TYPE_TEXT;

	/**
	 * Field value
	 *
	 * @var mixed
	 */
	private $__value;

	/**
	 * Field wrapper close (overrides global wrapper)
	 *
	 * @var string
	 */
	private $__wrapper_close;

	/**
	 * Field wrapper open (overrides global wrapper)
	 *
	 * @var string
	 */
	private $__wrapper_open;

	/**
	 * Initialize
	 *
	 * @param mixed $field_id
	 */
	public function __construct($field_id = NULL)
	{
		$this->__id = $field_id;
		if(!count(self::$__classes)) // init default classes
		{
			self::$__classes = array(
				self::TYPE_BUTTON => SadoFactory::getConf('form_field_default_class_button'),
				self::TYPE_FILE => SadoFactory::getConf('form_field_default_class_file'),
				'label' => SadoFactory::getConf('form_field_default_class_label'),
				self::TYPE_PASSWORD => SadoFactory::getConf('form_field_default_class_password'),
				self::TYPE_RADIO => SadoFactory::getConf('form_field_default_class_radio'),
				self::TYPE_SELECT => SadoFactory::getConf('form_field_default_class_select'),
				self::TYPE_SUBMIT => SadoFactory::getConf('form_field_default_class_submit'),
				self::TYPE_TEXT => SadoFactory::getConf('form_field_default_class_text'),
				self::TYPE_TEXTAREA => SadoFactory::getConf('form_field_default_class_textarea')
			);
		}
	}

	/**
	 * Field post value setter
	 *
	 * @param string $value
	 * @return void
	 */
	public function __setPostValue($value = NULL)
	{
		if(!$this->__is_post_value_set)
		{
			$this->__value = (bool)SadoFactory::getConf('form_field_value_auto_trim') ? trim($value) : $value;
			$this->__is_post_value_set = true;
		}
	}

	/**
	 * Field base attributes HTML getter
	 *
	 * @return string
	 */
	private function __getAttributes()
	{
		return SadoFormAttribute::id( is_string($this->__id) ? $this->__id : NULL ) // only add string IDs
			. SadoFormAttribute::name(  is_string($this->__id) ? $this->__id : NULL  )
			. SadoFormAttribute::className( $this->__class !== NULL ? $this->__class
				: ( isset(self::$__classes[$this->__type]) && self::$__classes[$this->__type] != NULL
					? self::$__classes[$this->__type] : NULL ) )
			. SadoFormAttribute::size( $this->__size > 0 ? $this->__size : NULL )
			. SadoFormAttribute::maxlength( $this->__maxlength > 0 ? $this->__maxlength : NULL )
			. SadoFormAttribute::style($this->__style)
			. ( $this->__is_readonly ? SadoFormAttribute::readonly() : NULL )
			. SadoFormAttribute::onBlur($this->__onblur)
			. SadoFormAttribute::onClick($this->__onclick)
			. SadoFormAttribute::onBlur($this->__onblur)
			. SadoFormAttribute::tabindex( $this->__tabindex > 0 ? $this->__tabindex : NULL )
			. SadoFormAttribute::placeholder($this->__placeholder)
			. $this->__attributes;
	}

	/**
	 * Field label HTML getter
	 *
	 * @return string
	 */
	private function __getLabel()
	{
		if($this->__label['text'] !== NULL)
		{
			return '<label' . SadoFormAttribute::forName($this->__id)
				. SadoFormAttribute::className( $this->__label['class'] !== NULL ? $this->__label['class']
					: ( isset(self::$__classes['label']) && self::$__classes['label'] ? self::$__classes['label'] : NULL ) )
				. ( $this->__label['attributes'] ? ( preg_match('#^ #', $this->__label['attributes'])
					? $this->__label['attributes'] : " {$this->__label['attributes']}" ) : NULL )
				. ">{$this->__label['text']}</label>" . SadoFactory::getConf('html_eof_line');
		}
		return NULL;
	}

	/**
	 * Get field options
	 *
	 * @return string
	 */
	private function __getOptions()
	{
		$options = NULL;
		if(is_array($this->__options) && count($this->__options))
		{
			foreach($this->__options as $value => $text)
			{
				switch($this->__type)
				{
					case self::TYPE_RADIO:
						$options .= $this->__wrapper_open . '<input'
							. SadoFormAttribute::type($this->__getTypeText($this->__type)) . SadoFormAttribute::value($value) . $this->__getAttributes()
							. ( in_array($value, $this->__selected) ? SadoFormAttribute::checked() : NULL )
							. " />{$text}" . $this->__wrapper_close . SadoFactory::getConf('html_eof_line');
						break;
					case self::TYPE_SELECT:
						$options .= '<option' . SadoFormAttribute::value($value)
							. (  in_array($value, $this->__selected) ? SadoFormAttribute::selected() : NULL )
							. ">{$text}</option>" . SadoFactory::getConf('html_eof_line');
						break;
				}
			}
		}
		return $options;
	}

	/**
	 * Type text getter
	 *
	 * @param int $type
	 * @return string
	 */
	private function __getTypeText($type = 0)
	{
		$type_text = array(
			self::TYPE_BUTTON => 'button',
			self::TYPE_CHECKBOX => 'checkbox',
			self::TYPE_FILE => 'file',
			self::TYPE_HIDDEN => 'hidden',
			self::TYPE_PASSWORD => 'password',
			self::TYPE_RADIO => 'radio',
			self::TYPE_SUBMIT => 'submit',
			self::TYPE_TEXT => 'text',
		);
		if(isset($type_text[$type]))
		{
			return $type_text[$type];
		}
		return NULL;
	}

	/**
	 * Field HTML setter
	 *
	 * @return void
	 */
	private function __setField()
	{
		if($this->__type)
		{
			switch($this->__type)
			{
				case self::TYPE_BUTTON:
				case self::TYPE_SUBMIT:
					$this->__html .= $this->__wrapper_open . '<input'
						. SadoFormAttribute::type($this->__getTypeText($this->__type))
						. $this->__getAttributes()
						. SadoFormAttribute::value($this->__value) . ' />' . $this->__wrapper_close
						. SadoFactory::getConf('html_eof_line');
					break;
				case self::TYPE_HIDDEN:
					$this->__html .= '<input' . SadoFormAttribute::type($this->__getTypeText($this->__type))
						. $this->__getAttributes()
						. SadoFormAttribute::value( $this->__is_auto_post_value ? $this->__value : NULL ) . ' />'
						. SadoFactory::getConf('html_eof_line');
					break;
				case self::TYPE_HTML:
					$this->__html .= $this->__value;
					break;
				case self::TYPE_CHECKBOX:
				case self::TYPE_FILE:
				case self::TYPE_PASSWORD:
				case self::TYPE_TEXT:
					$this->__html .= ( $this->__wrapper_open ? $this->__wrapper_open : SadoFactory::getConf('form_field_wrapper_open') )
						. $this->__getLabel()
						. '<input'
						. SadoFormAttribute::type($this->__getTypeText($this->__type))
						. $this->__getAttributes()
						. SadoFormAttribute::value( $this->__is_auto_post_value ? $this->__value : NULL )
						. ' />' . ( $this->__wrapper_close ? $this->__wrapper_close : SadoFactory::getConf('form_field_wrapper_close') )
						. SadoFactory::getConf('html_eof_line');
					break;
				case self::TYPE_RADIO:
					$this->__html .= $this->__getLabel() . $this->__getOptions() . SadoFactory::getConf('html_eof_line');
					break;
				case self::TYPE_SELECT:
					$this->__html .= ( $this->__wrapper_open ? $this->__wrapper_open : SadoFactory::getConf('form_field_wrapper_open') )
						. $this->__getLabel() . '<select'
						. $this->__getAttributes()
						. ( $this->__is_multiple ? SadoFormAttribute::multiple() : NULL )
						. '>' . SadoFactory::getConf('html_eof_line') . $this->__getOptions() . '</select>'
						. ( $this->__wrapper_close ? $this->__wrapper_close : SadoFactory::getConf('form_field_wrapper_close') )
						. SadoFactory::getConf('html_eof_line');
					break;
				case self::TYPE_TEXTAREA:
					$this->__html .= ( $this->__wrapper_open ? $this->__wrapper_open : SadoFactory::getConf('form_field_wrapper_open') )
						. $this->__getLabel()
						. '<textarea' . $this->__getAttributes() . '>' . ( $this->__is_auto_post_value ? $this->__value : NULL )
						. '</textarea>'	. ( $this->__wrapper_close ? $this->__wrapper_close : SadoFactory::getConf('form_field_wrapper_close') )
						. SadoFactory::getConf('html_eof_line');
					break;
			}
		}
	}

	/**
	 * Manual attributes setter
	 *
	 * @param string $attribute
	 * @return SadoFormField
	 */
	public function &attributes($attributes = NULL)
	{
		$this->__attributes .= " {$attributes}";
		return $this;
	}

	/**
	 * Field auto set post value setter
	 *
	 * @param bool $auto_post_value
	 * @return SadoFormField
	 */
	public function &autoPostValue($auto_post_value = true)
	{
		$this->__is_auto_post_value = (bool)$auto_post_value;
		return $this;
	}

	/**
	 * Field checked attribute
	 *
	 * @param bool $checked
	 * @return SadoFormField
	 */
	public function &checked($checked = true)
	{
		if($checked)
		{
			$this->__attributes .= SadoFormAttribute::checked();
		}
		return $this;
	}

	/**
	 * Field class name setter
	 *
	 * @param string $class
	 * @return SadoFormField
	 */
	public function &className($class = NULL)
	{
		$this->__class = $class;
		return $this;
	}

	/**
	 * Field HTML getter
	 *
	 * @return string
	 */
	public function getField()
	{
		if(!$this->__html)
		{
			$this->__setField();
		}
		return $this->__html;
	}

	/**
	 * Field ID getter
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return $this->__id;
	}

	/**
	 * Field type getter
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->__type;
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
	 * Field is post value set flag getter
	 *
	 * @return bool
	 */
	public function isPostValueSet()
	{
		return $this->__is_post_value_set;
	}

	/**
	 * Field is readonly flag getter
	 *
	 * @return bool
	 */
	public function isReadonly()
	{
		return $this->__is_readonly;
	}

	/**
	 * Field label setter
	 *
	 * @param string $label
	 * @param string $class
	 * @param string $attributes
	 * @return SadoFormField
	 */
	public function &label($label = NULL, $class = NULL, $attributes = NULL)
	{
		$this->__label['text'] = $label;
		$this->__label['class'] = $class;
		$this->__label['attributes'] = $attributes;
		return $this;
	}

	/**
	 * Field maxlength setter
	 *
	 * @param int $maxlength
	 * @return SadoFormField
	 */
	public function &maxlength($maxlength = 0)
	{
		$this->__maxlength = (int)$maxlength;
		return $this;
	}

	/**
	 * Field select multiple setter
	 *
	 * @param bool $multiple
	 * @return SadoFormField
	 */
	public function &multiple($multiple = true)
	{
		$this->__is_multiple = (bool)$multiple;
		return $this;
	}

	/**
	 * Field onblur setter
	 *
	 * @param string $onblur
	 * @return SadoFormField
	 */
	public function &onblur($onblur = NULL)
	{
		$this->__onblur = $onblur;
		return $this;
	}

	/**
	 * Field onclick setter
	 *
	 * @param string $onclick
	 * @return SadoFormField
	 */
	public function &onclick($onclick = NULL)
	{
		$this->__onclick = $onclick;
		return $this;
	}

	/**
	 * Field onfocus setter
	 *
	 * @param string $onfocus
	 * @return SadoFormField
	 */
	public function &onFocus($onfocus = NULL)
	{
		$this->__onfocus = $onfocus;
		return $this;
	}

	/**
	 * Field options setter
	 *
	 * @param array $options
	 * @return SadoFormField
	 */
	public function &options($options = array())
	{
		if(is_array($options))
		{
			$this->__options = $options;
		}
		return $this;
	}

	/**
	 * Field placeholder text setter
	 *
	 * @param string $text
	 * @return SadoFormField
	 */
	public function &placeholder($text = NULL)
	{
		$this->__placeholder = $text;
		return $this;
	}

	/**
	 * Field readonly setter
	 *
	 * @param bool $readonly
	 * @return SadoFormField
	 */
	public function &readonly($readonly = true)
	{
		$this->__is_readonly = (bool)$readonly;
		return $this;
	}

	/**
	 * Field selected option setter
	 *
	 * @param mixed $selected (string|array)
	 * @return SadoFormField
	 */
	public function &selected($selected = NULL)
	{
		if(!is_array($selected))
		{
			if($selected !== NULL && !in_array($selected, $this->__selected)) $this->__selected[] = $selected;
		}
		else if(is_array($selected) && count($selected) > 0)
		{
			foreach($selected as $select)
			{
				$this->selected(trim($select));
			}
		}
		return $this;
	}

	/**
	 * Field size setter
	 *
	 * @param int $size
	 * @return SadoFormField
	 */
	public function &size($size = 0)
	{
		$this->__size = (int)$size;
		return $this;
	}

	/**
	 * Field style setter
	 *
	 * @param string $style
	 * @return SadoFormField
	 */
	public function &style($style = NULL)
	{
		$this->__style = $style;
		return $this;
	}

	/**
	 * Field tabindex setter
	 *
	 * @param int $index
	 * @return SadoFormField
	 */
	public function &tabindex($index = 0)
	{
		$this->__tabindex = (int)$index;
		return $this;
	}

	/**
	 * Field type setter
	 *
	 * @param int $field_type
	 * @return SadoFormField
	 */
	public function &type($field_type = 0)
	{
		$this->__type = (int)$field_type;
		return $this;
	}

	/**
	 * Field value setter
	 *
	 * @param mixed $value
	 * @return SadoFormField
	 */
	public function &value($value = NULL)
	{
		$this->__value = $value;
		return $this;
	}

	/**
	 * Field wrapper setter
	 *
	 * @param string $open
	 * @param string $close
	 * @return SadoFormField
	 */
	public function &wrapper($open = NULL, $close = NULL)
	{
		$this->__wrapper_open = $open;
		$this->__wrapper_close = $close;
		return $this;
	}
}