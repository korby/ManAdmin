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
        $cnt = explode("@", $cnt);
        array_shift($cnt);
        $path = array();
        foreach($cnt as $row) {

            $match = false;
            foreach($excluded_patterns as $pattern) {
                if(strpos($row, $pattern) !== false) {
                    $match = true;
                }
            }
            if($match) {
                continue;
            }

            if (strpos($row, "Click-") === 0) {
                $path[] = $this->format_a($row);
            } else if (strpos($row, "Press-") === 0) {
                $path[] = $this->format_press($row);
            } else if (strpos($row, "Select-") === 0) {
                $path[] = $this->format_select($row);
            }

        }

        $visited = implode("<br>",$path);

        return $visited."<br>";
    }

    private function format_a ($row) {
        // pattern row = Click-Atext|Ahref or Click-AParenttext,Atext|Ahref
        $row = str_replace("Click-", "", $row);
        $links = array();
        foreach(explode(",", $row) as $line) {
            $link = explode("|", $line);
            if(count($link) == 2) {
                $links[] = $this->bold_text("<a target='_blank' href='".$link[1]."'>".$link[0]."</a>");
            } else {
                $links[] = $this->bold_text($link[0]);
            }
        }
        $string_formatted = __("Click on", "man-admin")." ".implode(" > ", $links);

        return $string_formatted;
    }

    private function format_select ($row) {
        // pattern row = Select-DefaultOPtionName|[OptionSelectedValue]
        $name = substr($row, strpos($row, "Select-") + 7, strpos($row, "|") -8);
        $choice = substr($row, strpos($row, "[") + 1, strpos($row, "]") - strpos($row, "[") -1);
        $string_formatted = __("In the drop-down list" , "man-admin")." ".$this->bold_text($name)." ".__("select " , "man-admin")." ".$this->bold_text($choice);

        return $string_formatted;
    }

    private function format_press ($row) {
        // pattern row = Press-ButtonName
        $name = substr($row, strpos($row, "Press-") + 6, strlen($row));
        $string_formatted = __("Press" , "man-admin")." ".$this->bold_text($name);

        return $string_formatted;
    }

    private function bold_text ($string) {
        return "<strong><i>".$string."</i></strong>";
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