<?php

// Subpackage namespace
namespace LittleBizzy\DisableGutenberg\Gutenberg;

/**
 * Styles class
 *
 * @package Disable Gutenberg
 * @subpackage Gutenberg
 */
class Styles {



	/**
	 * Constructor
	 */
	public function __construct() {

		// Retrieve styles
		$styles = wp_styles();

		// Check styles queue
		if (empty($styles->queue) || !is_array($styles->queue)) {
			return;
		}

		// Enum registered items
		foreach ($styles->registered as $key => $object) {

			// Check queued item
			if (!in_array($key, $styles->queue)) {
				continue;
			}

			// Check block library
			if ('wp-block-library' == $key) {

				// Extract item
				unset($styles->registered[$key]);

				// Done
				return;
			}
		}
	}



}