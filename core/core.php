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

		// WP init hook
		add_action('init', [$this, 'onInit']);

		// Print styles hook
		add_action('wp_print_styles', [$this, 'styles'], PHP_INT_MAX);
	}



	/**
	 * Handle WP init hook
	 */
	public function onInit() {

		// Check disabled by constant
		if (!$this->plugin->enabled('DISABLE_GUTENBERG')) {
			return;
		}

		// Remove the "Try Gutenberg" dashboard widget
		remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel');

		// Check Gutenberg as a plugin or default editor
		if ($this->plugin->factory->detector->detected()) {

			// Disable actions and hooks
			$this->plugin->factory->disabler();
		}
	}



	/**
	 * Handle the print styles hook
	 */
	public function styles() {

		// Check disabled by constant
		if (!$this->plugin->enabled('DISABLE_GUTENBERG')) {
			return;
		}

		// Check context
		if ($this->plugin->context()->front()) {
			$this->plugin->factory->styles();
		}
	}



}