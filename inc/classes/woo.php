<?php
/**
 * NSP_Woo
 *
 * @package NSP
 */

class NSP_Woo {
	public function __construct() {
		add_filter( 'woocommerce_background_image_regeneration', '__return_false' );
	}
}
