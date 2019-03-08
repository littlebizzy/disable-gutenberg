<?php

// Subpackage namespace
namespace LittleBizzy\DisableGutenberg\Helpers;

/**
 * Plugin class
 *
 * @package WordPress Plugin
 * @subpackage Helpers
 */
class Plugin {



	// Properties
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Basic plugin data
	 */
	private $file;
	private $root;
	private $prefix;
	private $version;
	private $repo;
	private $packageNamespace;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Set basic plugin data
	 */
	public function __construct() {

		// Vendor path
		$namespace = explode('\\', __NAMESPACE__);
		$this->packageNamespace = '\\'.implode('\\', array_slice($namespace, 0, 2)).'\\';

		// Set data
		$this->file 	= constant($this->packageNamespace.'FILE');
		$this->root 	= dirname($this->file);
		$this->prefix 	= constant($this->packageNamespace.'PREFIX');
		$this->version 	= constant($this->packageNamespace.'VERSION');
		$this->repo 	= defined($this->packageNamespace.'REPO')? constant($this->packageNamespace.'REPO') : false;
	}



	// Methods
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Magic GET method
	 */
	public function __get($name) {
		return $this->$name;
	}



	/**
	 * Check functionality enabled based on custom constant
	 */
	public function enabled($name, $default = true, $enabled = true) {
		return defined($name)? (constant($name) === $enabled) : $default;
	}



	/**
	 * Access to the context object
	 */
	public function context() {
		static $context;
		if (!isset($context)) {
			$context = new Context;
		}
		return $context;
	}



}