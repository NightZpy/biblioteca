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
 * Sado Configuration Settings
 *
 * @package Sado
 */
return array(
	/**
	 * Turn debugging/logging mode on/off (default: true)
	 *
	 * @var bool
	 */
	'debug' => true,

	/**
	 * Turn display errors on/off (default: true)
	 *
	 * @var bool
	 */
	'display_errors' => true,

	/**
	 * Dump debug log (default: true)
	 *
	 * @var bool
	 */
	'dump_log' => true,

	/**
	 * File extension used for Sado library files (default: '.php')
	 *
	 * @var string
	 */
	'file_lib_ext' => '.php',

	/**
	 * Sado ORM form default class (default: NULL)
	 *
	 * @var string
	 */
	'form_default_class' => NULL,

	/**
	 * Default Sado ORM form field classes (defaults: NULL)
	 *
	 * @var string
	 */
	'form_field_default_class_button' => NULL,
	'form_field_default_class_file' => NULL,
	'form_field_default_class_label' => NULL,
	'form_field_default_class_password' => NULL,
	'form_field_default_class_radio' => NULL,
	'form_field_default_class_select' => NULL,
	'form_field_default_class_submit' => NULL,
	'form_field_default_class_text' => NULL,
	'form_field_default_class_textarea' => NULL,

	/**
	 * Sado ORM form error (on validator fail) field wrappers (defaults: '')
	 *
	 * @var string
	 */
	'form_field_validator_wrapper_open' => '',
	'form_field_validator_wrapper_close' => '',

	/**
	 * Will trim (white spaces) form field value on post when true (default: true)
	 * This helps with format validation when a user enters white spaces only
	 *
	 * @var bool
	 */
	'form_field_value_auto_trim' => true,

	/**
	 * Sado ORM form field wrappers (defaults: '')
	 *
	 * @var string
	 */
	'form_field_wrapper_open' => '',
	'form_field_wrapper_close' => '',

	/**
	 * Sado ORM form ID field name (default: 'sado_form_id')
	 *
	 * @var string
	 */
	'form_id_field_name' => 'sado_form_id',

	/**
	 * Sado ORM grid default class (default: NULL)
	 *
	 * @var string
	 */
	'grid_default_class' => NULL,

	/**
	 * HTML end of line (default: PHP_EOL)
	 *
	 * @var string
	 */
	'html_eof_line' => PHP_EOL,

	/**
	 * Sado pagination get/query string current page var, ex: ?pg=2 (default: 'pg')
	 *
	 * @var string
	 */
	'pagination_get_page_var' => 'pg',

	/**
	 * Sado pagination records per page (default: 20)
	 *
	 * @var int
	 */
	'pagination_records_per_page' => 20,

	/**
	 * User defined error handler, set custom error handler can be called with:
	 * single function: 'myErrorHandler' or class method: array('ErrorClass', 'handleErrorMethod')
	 * (default: '')
	 *
	 * @var string|array
	 */
	'udf_error_handler' => '',

	/**
	 * User defined log handler, set custom logging handler can be called with:
	 * single function: 'myLogHandler' or class method: array('LogClass', 'handleLogMethod')
	 * (default: '')
	 *
	 * @var string|array
	 */
	'udf_log_handler' => '',

	/**
	 * Sado ORM view default class (default: NULL)
	 *
	 * @var string
	 */
	'view_default_class' => NULL,

	/**
	 * Sado ORM view label default class (default: NULL)
	 *
	 * @var string
	 */
	'view_label_default_class' => NULL
);