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
 * Sado ORM Grid Class
 *
 * @package Sado
 * @name SadoGrid
 * @author Shay Anderson 10.11
 */
abstract class SadoGrid
{
	/**
	 * Current record
	 *
	 * @var array
	 */
	private $__current_record = array();

	/**
	 * Grid fields (SadoGridField collection)
	 *
	 * @var array
	 */
	private $__fields = array();

	/**
	 * Grid HTML
	 *
	 * @var string
	 */
	private $__html;

	/**
	 * Zero records are detected flag
	 *
	 * @var bool
	 */
	private $__is_zero_records = false;

	/**
	 * SadoORM object
	 *
	 * @var SadoORM
	 */
	private $__sado_orm;

	/**
	 * SadoPagination object
	 *
	 * @var SadoPagination
	 */
	private $__sado_pagination;

	/**
	 * Grid attributes (ex: style, id, etc)
	 *
	 * @var string
	 */
	protected $attributes;

	/**
	 * Grid border width
	 *
	 * @var int
	 */
	protected $border = 0;

	/**
	 * Grid cell padding width
	 *
	 * @var int
	 */
	protected $cellpadding = 0;

	/**
	 * Grid cell spacing width
	 *
	 * @var int
	 */
	protected $cellspacing = 0;

	/**
	 * Grid class name
	 *
	 * @var string
	 */
	protected $class_name;

	/**
	 * Grid HTML footer
	 *
	 * @var string
	 */
	protected $html_foot;

	/**
	 * Grid HTML header
	 *
	 * @var string
	 */
	protected $html_head;

	/**
	 * Custom ORDER BY clause field(s) on default query
	 *
	 * @var string
	 */
	protected $query_order_by;

	/**
	 * Custom WHERE clause argument(s) on default query
	 * @var string
	 */
	protected $query_where;

	/**
	 * Grid td attributes
	 *
	 * @var string
	 */
	protected $td_attributes;

	/**
	 * Grid th attributes
	 *
	 * @var string
	 */
	protected $th_attributes;

	/**
	 * Grid thead tr attributes
	 *
	 * @var string
	 */
	protected $thead_tr_attributes;

	/**
	 * Grid tr attributes
	 *
	 * @var string
	 */
	protected $tr_attributes;

	/**
	 * Grid width
	 *
	 * @var string
	 */
	protected $width;

	/**
	 * Current record field record value getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	final public function __get($name = NULL)
	{
		if(array_key_exists($name, $this->__current_record))
		{
			return $this->__current_record[$name];
		}
		return NULL;
	}

	/**
	 * Grid HTML setter
	 *
	 * @return void
	 */
	final private function __setGrid()
	{
		$result_set = $this->getRecords();
		$this->onRecords();
		if(is_array($result_set) && count($result_set) && count($this->__fields))
		{
			$this->__html = "{$this->html_head}<table border=\"{$this->border}\" cellpadding=\"{$this->cellpadding}\" "
				. "cellspacing=\"{$this->cellspacing}\"" . ( $this->width ? " width=\"{$this->width}\"" : NULL )
				. ( $this->class_name ? " class=\"{$this->class_name}\"" : ( SadoFactory::getConf('grid_default_class') ?
					' class="' . SadoFactory::getConf('grid_default_class') . '"' : NULL ) )
				. ( $this->attributes ? " {$this->attributes}" : NULL ). ">" . SadoFactory::getConf('html_eof_line');
			$header_titles = array();
			$is_header_titles = false;
			if(count($this->__fields))
			{
				foreach($this->__fields as $field)
				{
					if($field->isVisible())
					{
						$header_titles[] = array('title' => $field->getTitle(), 'attributes' => $field->getAttributesTitle());
						if($field->getTitle())
						{
							$is_header_titles = true;
						}
					}
				}
				unset($field);
				if($is_header_titles && count($header_titles))
				{
					$this->__html .= '<thead><tr' . ( $this->thead_tr_attributes ? " {$this->thead_tr_attributes}" : NULL )
						. '>' . SadoFactory::getConf('html_eof_line');
					foreach($header_titles as $title)
					{
						$this->__html .= "<th{$title['attributes']}" . ( $this->th_attributes ? " {$this->th_attributes}" : NULL )
							. '>' . ( $title['title'] ? $title['title'] : '&nbsp;' ) . '</th>';
					}
					$this->__html .= SadoFactory::getConf('html_eof_line') . '</tr></thead>'
						. SadoFactory::getConf('html_eof_line');
				}
				if(count($result_set))
				{
					foreach($result_set as $r)
					{
						$this->__current_record = $r;
						$this->__html .= '<tr' . ( $this->tr_attributes ? " {$this->tr_attributes}" : NULL )
							. '>' . SadoFactory::getConf('html_eof_line');
						foreach($this->__fields as $field)
						{
							if($field->isVisible())
							{
								$this->__html .= '<td' . ( $this->td_attributes ? " {$this->td_attributes}" : NULL )
									. ( $field->getAttributes() ? $field->getAttributes() : NULL ) . '>';
								if($field->getFilter())
								{
									if(method_exists($this, $field->getFilter()))
									{
										$this->__html .= $this->{$field->getFilter()}();
									}
									else
									{
										$this->__html .= '&nbsp;';
									}
								}
								else if(isset($r[$field->getId()]))
								{
									$this->__html .= strlen($r[$field->getId()]) > 0 ? $r[$field->getId()] : '&nbsp;';
								}
								else
								{
									$this->__html .= '&nbsp;';
								}
								$this->__html .= '</td>';
							}
						}
						$this->__html .= SadoFactory::getConf('html_eof_line') . '</tr>'
							. SadoFactory::getConf('html_eof_line');
					}
				}
			}
			$this->__html .= "</table>{$this->html_foot}" . SadoFactory::getConf('html_eof_line');
		}
		else
		{
			$this->__is_zero_records = true;
		}
	}

	/**
	 * Grid field setter
	 *
	 * @param string $field_id
	 * @return SadoGridField
	 */
	final protected function &field($field_id = NULL)
	{
		$field = NULL;
		if($field_id)
		{
			if(isset($this->__fields[$field_id]))
			{
				$field = &$this->__fields[$field_id];
			}
			else
			{
				$field = new SadoGridField($field_id);
				$this->__fields[$field_id] = $field;
			}
		}
		else
		{
			$field = new SadoGridField();
			$this->__fields[] = &$field;
		}
		return $field;
	}

	/**
	 * Multiple grid field setter
	 *
	 * @param array $fields
	 * @return void
	 */
	final protected function fields($fields = array())
	{
		if(is_array($fields) && count($fields) > 0)
		{
			foreach($fields as $field_id)
			{
				$this->field($field_id);
			}
		}
	}

	/**
	 * Grid HTML getter
	 *
	 * @param bool $return_html (flag to return HTML or records array)
	 * @return mixed (string|array)
	 */
	final public function getGrid($return_html = true)
	{
		if(!$return_html)
		{
			return $this->getRecords();
		}
		if(!$this->__html && !$this->__is_zero_records)
		{
			$this->__setGrid();
		}
		return $this->__html;
	}

	/**
	 * Model name getter
	 *
	 * @return string
	 */
	final protected function getModelName()
	{
		if($this->__sado_orm)
		{
			return $this->__sado_orm->getName();
		}
		return NULL;
	}

	/**
	 * Grid records query getter
	 *
	 * @return string
	 */
	protected function getQuery()
	{
		if($this->__sado_orm)
		{
			return 'SELECT * FROM ' . $this->__sado_orm->getName()
				. ( strlen($this->query_where) > 0 ? ' WHERE ' . $this->query_where . ' ' : NULL )
				. ( strlen($this->query_order_by) > 0 ? ' ORDER BY ' . $this->query_order_by . ' ' : NULL )
				. ( $this->__sado_pagination ? ' LIMIT ' . $this->__sado_pagination->getOffset() . ', '
				. $this->__sado_pagination->getLimit() : NULL ) . ';';
		}
		return NULL;
	}

	/**
	 * Records getter
	 *
	 * @return array
	 */
	protected function getRecords()
	{
		if($this->__sado_orm && $this->getQuery())
		{
			if($this->__sado_pagination)
			{
				return $this->__sado_pagination->setRows($this->__sado_orm->getSadoInstance()->query($this->getQuery()));
			}
			else
			{
				return $this->__sado_orm->getSadoInstance()->query($this->getQuery());
			}
		}
		return array();
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
	 * SadoPagination getter
	 *
	 * @return SadoPagination
	 */
	final public function &getSadoPagination()
	{
		return $this->__sado_pagination;
	}

	/**
	 * Toggle all fields hidden
	 *
	 * @return void
	 */
	final protected function hideAllFields()
	{
		foreach($this->__fields as $field)
		{
			$field->hide();
		}
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
	 * Called when records are set
	 *
	 * @return void
	 */
	protected function onRecords()
	{
		// overridable
	}

	/**
	 * Pagination setter
	 *
	 * @param string $page_var_name (optional)
	 * @param string $records_per_page (optional)
	 * @return void
	 */
	final protected function pagination($page_var_name = NULL, $records_per_page = 0)
	{
		$this->__sado_pagination = new SadoPagination($page_var_name, $records_per_page);
	}

	/**
	 * Auto add all model fields to grid
	 *
	 * @return void
	 */
	final protected function showAllFields()
	{
		if($this->__sado_orm)
		{
			$this->fields($this->__sado_orm->getFields());
		}
	}
}