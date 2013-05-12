<?php
require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'variables.php';

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
 * Sado Database Schema File
 *
 * @package Sado
 */

/**
 * @example
 * return array(
 *
 *		'.schema' => array(
 *			'cache_file_path' => '',
 *			'cache_force' => false,
 *			'cache_ignore' => ''
 *		),
 *
 *		'table1' => array(
 *			'key' => 'table1_id',
 *			'fields' => 'field1, field2, field3'
 *		),
 *
 *		'table2' => array(
 *			'key' => 'table2_id',
 *			'fields' => 'field1, field2, field3'
 *		),
 * );
 */
return array(

	'.schema' => array(
		'cache_file_path' => SADO_CACHE.DS.'sado.schema.cache.php',
		'cache_force' => false,
		'cache_ignore' => ''
	),
    'libros' => array( // set the table name 'users' 
            'key' => 'id', // define the primary key 
            'fields' => 'titulo, autor', // add the table fields 
            //'ignore' => 'date_created, date_modified', // these fields will be ignored (not cached when caching) 
            'class' => 'Libro', // this will add a relationship to classes (Sado will know these classes use this table/model) 
      ),	
    'categorias' => array( // set the table name 'users' 
            'key' => 'id', // define the primary key 
            'fields' => 'nombre', // add the table fields 
            //'ignore' => 'date_created, date_modified', // these fields will be ignored (not cached when caching) 
            'class' => 'Categoria', // this will add a relationship to classes (Sado will know these classes use this table/model) 
      ),	    

);