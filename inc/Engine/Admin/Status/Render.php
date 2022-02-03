<?php

namespace WP_Rocket\Engine\Admin\Status;

use WP_Rocket\Abstract_Render;

defined( 'ABSPATH' ) || exit;

/**
 * Handles rendering of deactivation intent form on plugins page
 *
 * @since 3.0
 * @author Remy Perona
 */
class Render extends Abstract_Render {

	public function render_status_table( string $template, WPListTable_Interface $wp_list_table ) {
		$wp_list_table->prepare_items();
		echo $this->generate(
			$template,
			[
				'status_list_table' => $wp_list_table,
			]
		);
	}
}
