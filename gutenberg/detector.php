<?php

// Subpackage namespace
namespace LittleBizzy\DisableGutenberg\Gutenberg;

// Aliased namespaces
use \LittleBizzy\DisableGutenberg\Helpers;

/**
 * Detector class
 *
 * @package Disable Gutenberg
 * @subpackage Gutenberg
 */
class Detector extends Helpers\Singleton {



	/**
	 * Gutenberg modes status
	 */
	private $byPlugin = false;
	private $byDefault = false;



	/**
	 * Pseudo constructor
	 */
	public function onConstruct() {

		// Check plugin activation
		if (has_filter('replace_editor', 'gutenberg_init') ) {
			$this->byPlugin = true;
		}

		// Check default editor
		global $wp_version;
		if (version_compare($wp_version, '5.0-beta', '>=')) {
			$this->byDefault = true;
		}
	}



	/**
	 * Return if any Gutenberg mode have been detected
	 */
	public function detected() {
		return $this->byPlugin || $this->byDefault;
	}



	/**
	 * Return plugin mode
	 */
	public function byPlugin() {
		return $this->byPlugin;
	}



	/**
	 * Return default editor mode
	 */
	public function byDefault() {
		return $this->byDefault;
	}



}