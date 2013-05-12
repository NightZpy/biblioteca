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
 * Sado Pagination Class
 *
 * @package Sado
 * @name SadoPagination
 * @author Shay Anderson 12.11
 */
final class SadoPagination
{
	/**
	 * Query string current page var name
	 *
	 * @var string
	 */
	private $__get_page_var;

	/**
	 * Total number of rows
	 *
	 * @var int
	 */
	private $__num_rows = 0;

	/**
	 * Offset (starting record)
	 *
	 * @var int
	 */
	private $__offset = 0;

	/**
	 * Current page (default first page)
	 *
	 * @var int
	 */
	private $__page_curr = 1;

	/**
	 * Records per page
	 *
	 * @var int
	 */
	private $__rpp = 0;

	/**
	 * Init
	 *
	 * @param string $page_var_name (optional)
	 * @param int $records_per_page (optional)
	 */
	public function __construct($page_var_name = NULL, $records_per_page = 0)
	{
		$this->__get_page_var = $page_var_name !== NULL ? $page_var_name
			: SadoFactory::getConf('pagination_get_page_var');
		$this->__rpp = (int)$records_per_page > 0 ? (int)$records_per_page
			: (int)SadoFactory::getConf('pagination_records_per_page');
		if(isset($_GET[$this->__get_page_var]) && (int)$_GET[$this->__get_page_var] > 0)
		{
			$this->__page_curr = (int)$_GET[$this->__get_page_var];
		}
		if($this->__page_curr > 0)
		{
			$this->__offset = (int)($this->__page_curr - 1) * $this->__rpp;
		}
	}

	/**
	 * Limit getter (max records to get (rpp + 1))
	 *
	 * @return int
	 */
	public function getLimit()
	{
		return $this->__rpp + 1;
	}

	/**
	 * Next page getter
	 *
	 * @return int (0 if no next page)
	 */
	public function getNextPage()
	{
		return $this->__num_rows > $this->__rpp ? $this->__page_curr + 1 : 0;
	}

	/**
	 * Next page query string getter, ex: 'pg=2'
	 *
	 * @return string (null if no next page)
	 */
	public function getNextPageString()
	{
		return $this->getNextPage() > 0 ? $this->__get_page_var . '=' . $this->getNextPage() : NULL;
	}

	/**
	 * Offset (starting record) getter
	 *
	 * @return int
	 */
	public function getOffset()
	{
		return $this->__offset;
	}

	/**
	 * Previous page getter
	 *
	 * @return int (0 if no previous page)
	 */
	public function getPrevPage()
	{
		return $this->__page_curr > 1 ? $this->__page_curr - 1 : 0;
	}

	/**
	 * Previous page query string getter, ex: 'pg=2'
	 *
	 * @return string (null if no previous page)
	 */
	public function getPrevPageString()
	{
		return $this->getPrevPage() > 0 ? $this->__get_page_var . '=' . $this->getPrevPage() : NULL;
	}

	/**
	 * Set rows for grid (will pop off last row if exists)
	 *
	 * @param array $rows
	 * @return array
	 */
	public function setRows($rows = array())
	{
		if(is_array($rows) && count($rows) > $this->__rpp)
		{
			$this->__num_rows = count($rows);
			array_pop($rows);
		}
		return is_array($rows) ? $rows : array();
	}
}