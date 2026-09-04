<?php
/**
 * Removes plugin options on uninstall.
 *
 * @package nivon
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'nivon_options' );
