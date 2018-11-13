<?php

// Subpackage namespace
namespace LittleBizzy\DisableGutenberg\Core;

// Aliased namespaces
use \LittleBizzy\DisableGutenberg\Helpers;

/**
 * Core class
 *
 * @package Disable Gutenberg
 * @subpackage Core
 */
final class Core extends Helpers\Singleton {



	/**
	 * Pseudo constructor
	 */
	protected function onConstruct() {

		// Factory object
		$this->plugin->factory = new Factory($this->plugin);

		// Attempt to run an object
		//$this->plugin->factory->myObject()
	}



}