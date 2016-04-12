<?php
/*
 *	JavaScript Wordpress editor *
 *		
 *	Usage:
 *		server side(WP):
 *			js_wp_editor( $settings );
 *		client side(jQuery):
 *			$('textarea').wp_editor( options );
 */
function js_wp_editor( $settings = array() ) {
	if ( ! class_exists( '_WP_Editors' ) )
		require( ABSPATH . WPINC . '/class-wp-editor.php' );
	$set = _WP_Editors::parse_settings( 'apid', $settings );
	if ( !current_user_can( 'upload_files' ) )
		$set['media_buttons'] = false;
	if ( $set['media_buttons'] ) {
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script('media-upload');
		$post = get_post();
		if ( ! $post && ! empty( $GLOBALS['post_ID'] ) )
			$post = $GLOBALS['post_ID'];
		wp_enqueue_media( array(
			'post' => $post
		) );
	}
	_WP_Editors::editor_settings( 'apid', $set );
	$ap_vars = array(
		'url' => get_home_url(),
		'includes_url' => includes_url()
	);
	wp_register_script( 'ap_wpeditor_init', ASSETS_DIR . 'js_min/js_wp_editor.min.js', array( 'jquery' ), '1.1', true );
	wp_localize_script( 'ap_wpeditor_init', 'ap_vars', $ap_vars );
	wp_enqueue_script( 'ap_wpeditor_init' );
}