<?php

if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
    die( md5_file( __FILE__ ) );
}
/**
 * Define PHP_INT_MIN - Missing on some systems
 */
@define( 'WPAUTOLOAD_DIR', dirname( __FILE__ ) );
if ( ! defined( 'PHP_INT_MIN' ) ) {
    if ( defined( 'PHP_INT_MAX' ) ) {
        define( 'PHP_INT_MIN', (int) (- 1 - PHP_INT_MAX) );
    }
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Check PHP version
if ( version_compare( PHP_VERSION, '5.3.0' ) < 0 ) {
    require_once realpath( dirname( __FILE__ ) . '/lib/-functions/admin_fail_notices.php' );
    return add_action( 'admin_notices', 'plg_wpautoloader_admin_notice_php_version' );
}
// Load WP Autoloader
require_once realpath( dirname( __FILE__ ) . '/lib/WPAutoloader/AutoLoad.php' );
// Calls \WPAutoloader\AutoLoad::Hook(); to avoid fatal error on systems < PHP 5.3
call_user_func( '\WPAutoloader\AutoLoad::Hook' );
