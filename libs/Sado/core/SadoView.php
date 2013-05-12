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
 * Sado ORM View Class
 *
 * @package Sado
 * @name SadoView
 * @author Shay Anderson 10.11
 */
abstract class SadoView
{
	/**
	 * Current SadoViewCell object
	 *
	 * @var SadoViewCell
	 */
	private $__curr_cell;

	/**
	 * View HTML
	 *
	 * @var string
	 */
	private $__html;

	/**
	 * Currently in row flag
	 *
	 * @var bool
	 */
	private $__is_in_row = false;

	/**
	 * Num of rows in view
	 *
	 * @var int
	 */
	private $__num_rows = 0;

	/**
	 * Sado ORM object
	 *
	 * @var SadoORM
	 */
	private $__sado_orm;

	/**
	 * View table attributes
	 *
	 * @var string
	 */
	protected $attributes;

	/**
	 * View table border size
	 *
	 * @var int
	 */
	protected $border = 0;

	/**
	 * View table cellpadding
	 *
	 * @var int
	 */
	protected $cellpadding = 0;

	/**
	 * View table cell spacing
	 *
	 * @var int
	 */
	protected $cellspacing = 0;

	/**
	 * View table class name
	 *
	 * @var string
	 */
	protected $class_name;

	/**
	 * View table footer HTML
	 *
	 * @var string
	 */
	protected $html_foot;

	/**
	 * View table header HTML
	 *
	 * @var string
	 */
	protected $html_head;

	/**
	 * View table width
	 *
	 * @var mixed (int|string)
	 */
	protected $width;

	/**
	 * Close current cell (if current cell exists)
	 *
	 * @return void
	 */
	final private function __startCell()
	{
		if($this->__num_rows < 1)
		{
			$this->__html .= '<tr>' . SadoFactory::getConf('html_eof_line');
			$this->__is_in_row = true;
			$this->__num_rows++;
		}
		if($this->__curr_cell !== NULL) // add and close last/current cell
		{
			$this->__html .= $this->__curr_cell->getCell();
			$this->__curr_cell = NULL;
		}
	}

	/**
	 * Cell setter
	 *
	 * @param string $html
	 * @return SadoViewCell
	 */
	final public function &cell($html = NULL)
	{
		$this->__startCell();
		$this->__curr_cell = new SadoViewCell($html);
		return $this->__curr_cell;
	}

	/**
	 * Field cell setter
	 *
	 * @param string $field_id
	 * @return SadoViewCell (or false on error)
	 */
	final public function &cellField($field_id = NULL)
	{
		$this->__startCell();
		$b = false;
		if(!$this->__sado_orm)
		{
			SadoFactory::error('Failed to initialize SadoView, invalid SadoORM object', __CLASS__);
			return $b;
		}
		if($this->__sado_orm->isField($field_id))
		{
			$this->__curr_cell = new SadoViewCell($this->__sado_orm->__get($field_id));
			return $this->__curr_cell;
		}
		else
		{
			$this->__sado_orm->getSadoInstance()->error("Failed to add cell field, invalid field \"{$field_id}\"");
			return $b;
		}
	}

	/**
	 * Label cell setter
	 *
	 * @param type $html
	 * @return SadoViewCell
	 */
	final public function &cellLabel($html = NULL)
	{
		$this->__startCell();
		$this->__curr_cell = new SadoViewCell($html);
		$this->__curr_cell->className(SadoFactory::getConf('view_label_default_class'));
		return $this->__curr_cell;
	}

	/**
	 * SadoORM object getter
	 *
	 * @return SadoORM
	 */
	final public function &getSadoOrm()
	{
		return $this->__sado_orm;
	}

	/**
	 * View HTML getter
	 *
	 * @return string
	 */
	final public function getView()
	{
		if($this->__html !== NULL)
		{
			$this->__startCell(); // close last field
			return $this->html_head . "<table border=\"{$this->border}\" cellpadding=\"{$this->cellpadding}\" "
				. "cellspacing=\"{$this->cellspacing}\"" . ( $this->width ? " width=\"{$this->width}\"" : NULL )
				. ( $this->class_name ? " class=\"{$this->class_name}\"" : ( SadoFactory::getConf('view_default_class')
					? ' class="' . SadoFactory::getConf('view_default_class'). '"' : NULL ) )
				. $this->attributes . '>' . SadoFactory::getConf('html_eof_line') . ( $this->__is_in_row ?
					$this->__html . '</tr>' . SadoFactory::getConf('html_eof_line') : $this->__html )
				. '</table>' . SadoFactory::getConf('html_eof_line') . $this->html_foot;
		}
		return NULL;
	}

	/**
	 * SadoORM object setter
	 *
	 * @param SadoORM $_sado_orm_object
	 * @return void
	 */
	final public function model(SadoORM &$sado_orm_object)
	{
		$this->__sado_orm = &$sado_orm_object;
	}

	/**
	 * Row setter
	 *
	 * @param string $attributes
	 * @return void
	 */
	final public function row($attributes = NULL)
	{
		if($this->__num_rows > 0)
		{
			$this->__startCell(); // close last field
		}
		if(!$this->__is_in_row) // start
		{
			$this->__html .= '<tr' . ( $attributes !== NULL ? ' ' . $attributes : NULL ) . '>'
				. SadoFactory::getConf('html_eof_line');
			$this->__is_in_row = true;
			$this->__num_rows++;
		}
		else // end
		{
			$this->__html .= '</tr>' . SadoFactory::getConf('html_eof_line')
				. '<tr' . ( $attributes !== NULL ? ' ' . $attributes : NULL ) . '>'
				. SadoFactory::getConf('html_eof_line');
			$this->__num_rows++;
		}
	}
}