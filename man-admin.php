<?php
/*
Plugin Name: ManAdmin
Description: A simple back office usage's manual area, accessible by users and editable by administrators. Compatibility WP >= 3.8
Version: 0.5
Author: André Cianfarani
License: GPL2
*/


require plugin_dir_path( __FILE__ ).'/class.manadmin.php';
$man_admin = new Manadmin();

if($_POST["stop_record"]) {
    $man_admin->path_visited = $_COOKIE["visited"];
    setcookie('visited', '', time() - 3600);
    unset($_COOKIE["visited"]);
} else if($_POST["start_record"]) {
    setcookie('visited', 'on');
    $_COOKIE["visited"] = "on";
}


register_activation_hook(__FILE__, array('Manadmin', 'activate'));
register_uninstall_hook(__FILE__, array('Manadmin', 'uninstall'));

function man_admin_text_domain() {
    load_plugin_textdomain( 'man-admin', plugin_dir_path( __FILE__ ) . 'languages', dirname(plugin_basename(__FILE__)).'/languages');
}

add_action( 'init', 'man_admin_text_domain' );

function manadmin_customizer_live_preview()
{
    wp_enqueue_script(
        'man-admin-themecustomizer',			//Give the script an ID
        plugin_dir_url(__FILE__).'/recorder.js', array(), '1.2.0', true
    );
}
if($_COOKIE["visited"]){
    // register script for website
    wp_enqueue_script( 'script-name', plugin_dir_url(__FILE__) . '/recorder.js', array(), '1.0.0', true );
    // register script for theme customizer which have his own frame
    add_action( 'customize_controls_enqueue_scripts', 'manadmin_customizer_live_preview' );
}
