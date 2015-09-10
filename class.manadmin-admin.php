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

    public function edit()
    {
        // Accessible only for administrators
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $data = array();

        if($_POST["old_version"] != "no") {
            $data["text"] = $this->manager->get($_POST["old_version"])->text;
        }
        else if($_POST["manadmin_content"]) {
            $data["text"] = $_POST["manadmin_content"];
            $nowUtc = new \DateTime( 'now',  new \DateTimeZone( 'UTC' ));
            $nowUtc->setTimezone( new \DateTimeZone( 'Europe/Paris' ) );
            $data["datetime"] = $nowUtc->format('Y-m-d H:i:s');
            $this->manager->persist($data);
        } else {
            $data["text"] = $this->manager->get()->text;
        }

        $data["versions"] = $this->manager->get_all();

        $this->manager->view("edit", $data);

    }
} 