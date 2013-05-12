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
 * Sado Select Query Builder
 *
 * @package Sado
 * @name SadoSelectBuilder
 * @author Shay Anderson 10.11
 */
final class SadoSelectBuilder
{
	/**
	 * Query builder parts
	 *
	 * @var array
	 */
	private $__query = array(
		'from' => NULL,
		'group' => NULL,
		'join' => NULL,
		'limit' => NULL,
		'order' => NULL,
		'select' => NULL,
		'where' => NULL
	);

	/**
	 * Subquery flag
	 *
	 * @var bool
	 */
	private $__is_subquery = false;

	/**
	 * Sado connection
	 *
	 * @var Sado
	 */
	private $__sado;

	/**
	 * Initialize
	 *
	 * @param Sado $sado
	 */
	public function __construct(Sado &$sado)
	{
		$this->__sado = &$sado;
	}

	/**
	 * Get query string (builder query or written query, used for debugging query)
	 *
	 * @return string
	 */
	protected function _getQuery()
	{
		return ($this->__is_subquery ? '( ' : NULL)
				. ( $this->__query['select'] ? $this->__query['select'] : 'SELECT *' )
				. $this->__query['from']
				. $this->__query['join']
				. $this->__query['where']
				. $this->__query['group']
				. $this->__query['order']
				. $this->__query['limit']
				. ( !$this->__is_subquery ? ';' : ' )' ); // close query (unless subquery)
	}

	/**
	 * Set a [field] BETEEN [x] AND [y] clause
	 *
	 * @param string $field
	 * @param string $value_x
	 * @param string $value_y
	 * @return SadoSelectBuilder
	 */
	public function &between($field = NULL, $value_x = NULL, $value_y = NULL)
	{
		if(!$this->__query['where'])
		{
			$this->__query['where'] = ' WHERE ';
		}
		else
		{
			$this->__query['where'] .= ' AND ';
		}
		$this->__query['where'] .= " {$field} BETWEEN {$this->__sado->value($value_x)} AND {$this->__sado->value($value_y)}";
		return $this;
	}

	/**
	 * Set FROM [table] clause
	 *
	 * @param string $table
	 * @param string $alias (optional)
	 * @return SadoSelectBuilder
	 */
	public function &from($table = NULL, $alias = NULL)
	{
		$alias = $alias ? " AS {$alias}" : NULL;
		if(!$this->__query['from'])
		{
			$this->__query['from'] = " FROM {$table}{$alias}";
		}
		else
		{
			$this->__query['from'] .= ", {$table}{$alias} ";
		}
		return $this;
	}

	/**
	 * Get query results
	 *
	 * @param bool $return_query
	 * @return mixed
	 */
	public function get($return_query = false)
	{
		if($return_query)
		{
			return $this->_getQuery();
		}
		else
		{
			return $this->__sado->select($this->_getQuery());
		}
	}

	/**
	 * Set GROUP BY [field] clause
	 *
	 * @param string $field
	 * @param bool $asc
	 * @return SadoSelectBuilder
	 */
	public function &group($field = NULL, $asc = true)
	{
		if(!$this->__query['group'])
		{
			$this->__query['group'] = ' GROUP BY ';
		}
		else
		{
			$this->__query['group'] .= ', ';
		}
		$this->__query['group'] .= $field;
		return $this;
	}

	/**
	 * Set JOIN [table] clause
	 *
	 * @param string $table
	 * @param string $alias (optional)
	 * @param string $on
	 * @return SadoSelectBuilder
	 */
	public function &join($table = NULL, $alias = NULL, $on = NULL)
	{
		$this->__query['join'] .= " JOIN {$table} " . ( $alias ? "AS {$alias} " : NULL)
			. ' ' . ( $on ? "ON {$on} " : NULL );
		return $this;
	}

	/**
	 * Set JOIN LEFT [table] (ON [x]) clause
	 *
	 * @param string $table
	 * @param string $alias (optional)
	 * @param string $on
	 * @return SadoSelectBuilder
	 */
	public function &joinLeft($table = NULL, $alias = NULL, $on = NULL)
	{
		$this->__query['join'] .= ' LEFT JOIN ' . $table . ( $alias ? " AS {$alias} " : NULL)
			. ' ' . ( $on ? "ON {$on} " : NULL );
		return $this;
	}

	/**
	 * Set LIMIT ([offset], ) [row count]  clause
	 *
	 * @param int $row_count
	 * @param int $offset
	 * @return SadoSelectBuilder
	 */
	public function &limit($row_count = 0, $offset = 0)
	{
		if(!$this->__query['limit'] && $row_count)
		{
			$this->__query['limit'] = ' LIMIT ' . ( $offset ? "{$offset}, " : NULL ) . "{$row_count} ";
		}
		return $this;
	}

	/**
	 * Set ORDER BY [field] (ASC|DESC) clause
	 *
	 * @param string $field
	 * @param bool $asc
	 * @return SadoSelectBuilder
	 */
	public function &order($field = NULL, $asc = true)
	{
		if(!$field)
		{
			return $this;
		}
		if(!$this->__query['order'])
		{
			$this->__query['order'] = ' ORDER BY ';
		}
		else
		{
			$this->__query['order'] .= ', ';
		}
		$this->__query['order'] .= $field . ( $asc ? NULL : ' DESC' );
		return $this;
	}

	/**
	 * Set SELECT (DISTINCT) [field1, field2, ...] clause
	 *
	 * @param string $fields
	 * @param string $alias (optional)
	 * @param bool $distinct
	 * @return SadoSelectBuilder
	 */
	public function &select($fields = NULL, $alias = NULL, $distinct = false)
	{
		$alias = $alias ? " AS '{$alias}'" : NULL;
		if(!$this->__query['select'])
		{
			$this->__query['select'] = 'SELECT ' . ( $distinct ? 'DISTINCT ' : NULL ) . " {$fields}{$alias} ";
		}
		else
		{
			$this->__query['select'] .= ", {$fields}{$alias} ";
		}
		return $this;
	}

	/**
	 * Set SELECT (DISTINCT) [field1, field2, ...] clause (shortcut method for select method)
	 *
	 * @param strings $fields
	 * @param string $alias (optional)
	 * @return SadoSelectBuilder
	 */
	public function &selectDistinct($fields = NULL, $alias = NULL)
	{
		return $this->select($fields, $alias, true);
	}

	/**
	 * Set query to subquery type
	 *
	 * @param bool $is_subquery
	 * @return SadoSelectBuilder
	 */
	public function &subquery($is_subquery = true)
	{
		$this->__is_subquery = (bool)$is_subquery;
		return $this;
	}

	/**
	 * Set WHERE ... ([pre-keyword]) [field] [operator] [value] clause
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param string $operator
	 * @param bool $safe_value (safe value by default or false will force unsafe value)
	 * @param string $pre_keyword (AND, OR, etc)
	 * @return SadoSelectBuilder
	 */
	public function &where($field = NULL, $value = NULL, $operator = '=', $safe_value = true, $pre_keyword = NULL)
	{
		if($field === NULL && $value === NULL)
		{
			return $this;
		}
		if(!$this->__query['where'])
		{
			$this->__query['where'] = ' WHERE';
		}
		$this->__query['where'] .= " {$pre_keyword} {$field} {$operator} "
			// if force unsafe value OR using NULL as value use unsafe value OR NULL, otherwise make the value safe
			. ( !$safe_value || trim(strtoupper($value)) === 'NULL' ? $value : $this->__sado->value($value) ) . ' ';
		return $this;
	}

	/**
	 * Set WHERE ... AND [field] [operator] [value] clause (shortcut method for where method)
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param string $operator
	 * @param bool $safe_value
	 * @return SadoSelectBuilder
	 */
	public function &whereAnd($field = NULL, $value = NULL, $operator = '=', $safe_value = true)
	{
		return $this->where($field, $value, $operator, $safe_value, 'AND');
	}

	/**
	 * Set WHERE ... OR [field] [operator] [value] clause (shortcut method for where method)
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param string $operator
	 * @param bool $safe_value
	 * @return SadoSelectBuilder
	 */
	public function &whereOr($field = NULL, $value = NULL, $operator = '=', $safe_value = true)
	{
		return $this->where($field, $value, $operator, $safe_value, 'OR');
	}
}