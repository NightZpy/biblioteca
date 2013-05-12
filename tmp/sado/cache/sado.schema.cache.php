<?php
/**
 * Sado Database Schema Cache File
 *
 * DO NOT EDIT unless re-caching
 *
 * Cached: 05/11/2013 06:33:37
 *
 * @package Sado
 */
return array(
	/**
	 * DO NOT remove '.cache' array, set 'schema' => '' for re-caching
	 */
	'.cache' => array(
		'schema' => '244fad678fcbbfc58195d5f30e2f543f'
	),
	'categorias' => array(
		'key' => 'id',
		'fields' => 'nombre, descripcion'
	),
	'libros' => array(
		'key' => 'id',
		'fields' => 'codigo, autor, titulo, descripcion, editorial, ejemplar, fecha_ingreso, categoria_id'
	),
	'personas' => array(
		'key' => 'id',
		'fields' => 'codigo, nombres, apellidos, cedula, nacionalidad, email, telefono, movil, direccion, procedencia, tipo_persona_id'
	),
	'prestamos' => array(
		'key' => 'id',
		'fields' => 'persona_id, libro_id, fecha_prestamo, fecha_entrega'
	),
	'supendidos' => array(
		'key' => 'id',
		'fields' => 'libro_id, persona_id, desde, hasta'
	),
	'tipo_personas' => array(
		'key' => 'id',
		'fields' => 'nombre'
	),
	'usuarios' => array(
		'key' => 'id',
		'fields' => 'usuario, password, cedula, nombres, apellidos, direccion, email, movil'
	));