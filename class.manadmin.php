<?php
/*
 *
 * MODEL
 *
 */

class Manadmin {

    public $admin;
    public $path_visited;

    public function __construct()
    {
        require plugin_dir_path( __FILE__ ).'/class.manadmin-admin.php';
        $this->admin = new Manadmin_Admin($this);

    }

    public function activate()
    {
        global $wpdb;

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}manadmin (id INT AUTO_INCREMENT PRIMARY KEY, text LONGTEXT NOT NULL, datetime DATETIME NOT NULL);");
    }

    public static function uninstall()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}manadmin;");
    }

    private function clean() {
        global $wpdb;
        $max = 500;
        $res = $wpdb->get_row("select max(id) as last_id from {$wpdb->prefix}manadmin");

        if($res->last_id > $max) {
            $range = $res->last_id - $max;
            $wpdb->query("delete from {$wpdb->prefix}manadmin where id < ".$range);
        }
    }

    public function persist($data)
    {
        global $wpdb;
        $this->clean();
        $wpdb->insert("{$wpdb->prefix}manadmin", $data);
    }

    public function get($id = "")
    {
        global $wpdb;
        if ("" != $id) {
            $res = $wpdb->get_row("select * from {$wpdb->prefix}manadmin where id=".$id);
        } else {
            $res = $wpdb->get_row("select * from {$wpdb->prefix}manadmin order by datetime DESC");
        }

        return $res;
    }

    public function get_all()
    {
        global $wpdb;
        $res = $wpdb->get_results("select * from {$wpdb->prefix}manadmin order by datetime DESC", $output = ARRAY_A);
        return $res;
    }

    public function view($template, array $data = array())
    {
        global $menu;
        global $submenu;
        $tpl_dir = 'views/';
        $extension = '-layout.php';
        require $tpl_dir.$template.$extension;
    }


} 