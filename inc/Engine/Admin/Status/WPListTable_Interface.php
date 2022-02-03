<?php
namespace WP_Rocket\Engine\Admin\Status;

defined( 'ABSPATH' ) || exit;

/**
 * Render interface
 *
 * @since 3.11
 */
interface WPListTable_Interface {
	/**
	 * Get a list of columns. The format is:
	 * 'internal-name' => 'Title'
	 *
	 * @since 3.11
	 */
	public function get_columns();

	/**
	 * Prepares the list of items for displaying.
	 *
	 * @since 3.11
	 */
	public function prepare_items();

}
