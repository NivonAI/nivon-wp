<?php
/**
 * Plugin Name:       Nivon
 * Plugin URI:        https://nivon.ai
 * Description:       Embed your Nivon chatbot on any WordPress site.
 * Version:           1.0.0
 * Requires at least: 4.7
 * Requires PHP:      7.0
 * Author:            Nivon AI
 * Author URI:        https://pimjo.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nivon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NIVON_VERSION', '1.0.0' );
define( 'NIVON_WIDGET_SRC', 'https://assets.nivon.ai/agent/widget.min.js' );

/**
 * Saved options.
 *
 * @return array
 */
function nivon_get_options() {
	$options = get_option( 'nivon_options', array() );

	return wp_parse_args(
		is_array( $options ) ? $options : array(),
		array(
			'publish_key' => '',
			'chatbot_id'  => '',
		)
	);
}

/* -------------------------------------------------------------------------
 * Settings
 * ---------------------------------------------------------------------- */

/**
 * Add the options page under Settings.
 */
function nivon_add_options_page() {
	add_options_page(
		__( 'Nivon Options', 'nivon' ),
		__( 'Nivon Options', 'nivon' ),
		'manage_options',
		'nivon-options',
		'nivon_render_options_page'
	);
}
add_action( 'admin_menu', 'nivon_add_options_page' );

/**
 * Register the option and fields.
 */
function nivon_register_settings() {
	register_setting(
		'nivon_options_group',
		'nivon_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'nivon_sanitize_options',
			'default'           => array(),
		)
	);

	add_settings_section(
		'nivon_main',
		'',
		'nivon_section_intro',
		'nivon-options'
	);

	add_settings_field(
		'nivon_publish_key',
		__( 'Publish key', 'nivon' ),
		'nivon_publish_key_field',
		'nivon-options',
		'nivon_main',
		array( 'label_for' => 'nivon_publish_key' )
	);

	add_settings_field(
		'nivon_chatbot_id',
		__( 'Chatbot ID', 'nivon' ),
		'nivon_chatbot_id_field',
		'nivon-options',
		'nivon_main',
		array( 'label_for' => 'nivon_chatbot_id' )
	);
}
add_action( 'admin_init', 'nivon_register_settings' );

/**
 * Where to find the values.
 */
function nivon_section_intro() {
printf(
	'<p>%s <a href="%s" target="_blank" rel="noopener noreferrer">%s</a>.</p>',
	esc_html__( 'Copy both values from the Agent → Integration tab and paste here', 'nivon' ),
	esc_url( 'https://app.nivon.ai' ),
	esc_html__( 'Nivon Dashboard', 'nivon' )
);
}

/**
 * Publish key field.
 */
function nivon_publish_key_field() {
	$options = nivon_get_options();
	printf(
		'<input type="text" id="nivon_publish_key" name="nivon_options[publish_key]" value="%s" class="regular-text code" placeholder="pk_live_..." spellcheck="false" autocomplete="off" />',
		esc_attr( $options['publish_key'] )
	);
}

/**
 * Chatbot ID field.
 */
function nivon_chatbot_id_field() {
	$options = nivon_get_options();
	printf(
		'<input type="text" id="nivon_chatbot_id" name="nivon_options[chatbot_id]" value="%s" class="regular-text code" placeholder="00000000-0000-0000-0000-000000000000" spellcheck="false" autocomplete="off" />',
		esc_attr( $options['chatbot_id'] )
	);
}

/**
 * Clean submitted values.
 *
 * @param mixed $input Raw input.
 * @return array
 */
function nivon_sanitize_options( $input ) {
	$input = is_array( $input ) ? $input : array();

	$publish_key = isset( $input['publish_key'] ) ? trim( sanitize_text_field( $input['publish_key'] ) ) : '';
	$chatbot_id  = isset( $input['chatbot_id'] ) ? trim( sanitize_text_field( $input['chatbot_id'] ) ) : '';

	return array(
		'publish_key' => preg_replace( '/[^A-Za-z0-9_\-]/', '', $publish_key ),
		'chatbot_id'  => preg_replace( '/[^A-Za-z0-9_\-]/', '', $chatbot_id ),
	);
}

/**
 * Options page markup.
 */
function nivon_render_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Nivon Options', 'nivon' ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'nivon_options_group' );
			do_settings_sections( 'nivon-options' );
			submit_button( __( 'Save Changes', 'nivon' ) );
			?>
		</form>
	</div>
	<?php
}

/**
 * Settings link on the Plugins screen.
 *
 * @param array $links Existing links.
 * @return array
 */
function nivon_plugin_action_links( $links ) {
	$link = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'options-general.php?page=nivon-options' ) ),
		esc_html__( 'Settings', 'nivon' )
	);
	array_unshift( $links, $link );

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'nivon_plugin_action_links' );

/* -------------------------------------------------------------------------
 * Front end
 * ---------------------------------------------------------------------- */

/**
 * Load the widget script in the footer.
 */
function nivon_enqueue_widget() {
	$options = nivon_get_options();

	if ( '' === $options['publish_key'] || '' === $options['chatbot_id'] ) {
		return;
	}

	// Null version keeps the query string off the widget URL.
	wp_enqueue_script( 'nivon-widget', NIVON_WIDGET_SRC, array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'nivon_enqueue_widget' );

/**
 * Add the Nivon data attributes and defer to the widget tag.
 *
 * @param string $tag    Script tag markup.
 * @param string $handle Script handle.
 * @return string
 */
function nivon_widget_script_tag( $tag, $handle ) {
	if ( 'nivon-widget' !== $handle ) {
		return $tag;
	}

	$options = nivon_get_options();

	$attributes = sprintf(
		' data-publish-key="%s" data-chatbot-id="%s"',
		esc_attr( $options['publish_key'] ),
		esc_attr( $options['chatbot_id'] )
	);

	if ( false === strpos( $tag, ' defer' ) ) {
		$attributes .= ' defer';
	}

	return str_replace( '<script', '<script' . $attributes, $tag );
}
add_filter( 'script_loader_tag', 'nivon_widget_script_tag', 10, 2 );
