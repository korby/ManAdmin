<?php
/*
 * CONTROLLER FOR ADMIN AREA
 *
 */
class Manadmin_Admin {

    private $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
        add_action( 'admin_menu', array($this, 'menu') );

    }

    public function menu()
    {
        add_menu_page( "Manuel du back office", __( "Manual", "man-admin"), "edit_posts", "manadmin", array($this, 'display'), "
dashicons-welcome-learn-more" );
        add_submenu_page( "manadmin", __("Manual", "man-admin"), __("Read", "man-admin"), "edit_posts", "manadmin", array($this, 'display'));
        add_submenu_page( "manadmin", __("Manual edition"), __("Edit", "man-admin"), "manage_options", "manadmin_edit", array($this, 'edit'));
    }

    public function display()
    {
        // Accessible for administrators, editors, authors and contributors
        if ( !current_user_can( 'edit_posts' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $data = array();
        $data["text"] = $this->manager->get()->text;

        $this->manager->view("view", $data);
    }

    private function format_visit() {
        $excluded_patterns = array("manadmin", __("Stop recording", "man-admin"), __("Record now", "man-admin"), __("Browse in a new window", "man-admin"));
        $cnt = $this->manager->path_visited;
        $cnt = str_replace("Click-",__("Click on "), $cnt);
        $cnt = str_replace("Press-",__("Press "), $cnt);
        $cnt = explode("@", $cnt);
        array_shift($cnt);
        $path = array();
        foreach($cnt as $row) {
            $links = array();
            $match = false;
            foreach($excluded_patterns as $pattern) {
                if(strpos($row, $pattern) !== false) {
                    $match = true;
                }
            }
            if($match) {
                continue;
            }
            foreach(explode(",", $row) as $line) {
                $link = explode("|", $line);
                if(count($link) == 2) {
                    $links[] = "<a href='".$link[1]."'>".$link[0]."</a>";
                } else {
                    $links[] = $link[0];
                }
            }
            $path[] = implode(" > ", $links);
        }


        return implode("<br>",$path)."<br>";
    }
    public function edit()
    {
        // Accessible only for administrators
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $data = array();

        if($_POST["old_version"] != "no") {
            $data["text"] = $this->manager->get($_POST["old_version"])->text;
        } else if($_POST["save"]) {
            $data["text"] = $this->save(array("text" => $_POST["manadmin_content"]));

        } else {
            $data["text"] = $this->manager->get()->text;
        }

        if($_POST["stop_record"]) {
            $data["text"] = $this->format_visit().$data["text"];
            $this->save($data);
        }

        $data["versions"] = $this->manager->get_all();

        $this->manager->view("edit", $data);

    }

    private function save($data) {
        $data["text"] = stripslashes($data["text"]);
        $nowUtc = new \DateTime( 'now',  new \DateTimeZone( 'UTC' ));
        $nowUtc->setTimezone( new \DateTimeZone( 'Europe/Paris' ) );
        $data["datetime"] = $nowUtc->format('Y-m-d H:i:s');
        $this->manager->persist($data);
        return $data["text"];
    }
} 