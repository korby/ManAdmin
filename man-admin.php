<?php
/*
Plugin Name: ManAdmin
Description: A simple back office usage's manual area, accessible by users and editable by administrators. Compatibility WP >= 3.8
Version: 0.1
Author: André Cianfarani
License: GPL2
*/
require plugin_dir_path( __FILE__ ).'/class.manadmin.php';
new Manadmin();

register_activation_hook(__FILE__, array('Manadmin', 'activate'));
register_uninstall_hook(__FILE__, array('Manadmin', 'uninstall'));

function man_admin_text_domain() {
    load_plugin_textdomain( 'man-admin', plugin_dir_path( __FILE__ ) . 'languages', basename( dirname( __FILE__ ) ) . '/languages');
}


add_action( 'init', 'man_admin_text_domain' );