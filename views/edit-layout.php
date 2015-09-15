<?php
?>
<div class="wrap">
    <form name="post" method="post" id="post">
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">

<div style="position: relative;" id="post-body-content">
    <div id="postdivrich" class="postarea<?php if ( false ) { echo ' wp-editor-expand'; } ?>">

            <?php
            wp_editor( $data["text"], 'manadmin_content', array(
                    '_content_editor_dfw' => false,
                    'drag_drop_upload' => true,
                    'tabfocus_elements' => 'content-html,save-post',
                    'editor_height' => 800,
                    'tinymce' => array(
                        'resize' => false,
                        'wp_autoresize_on' => false,
                        'add_unload_trigger' => false,
                    ),
                ) );
            ?>

    </div>
</div>


<!--// START POSTBOX CONTAINER //-->
<div id="postbox-container-1" class="postbox-container">
    <div style="" id="side-sortables" class="meta-box-sortables ui-sortable"><div id="submitdiv" class="postbox ">
        <h3 class="hndle ui-sortable-handle"><span><?php echo __("Edit", "man-admin"); ?></span></h3>
        <div class="inside">
            <div class="submitbox" id="submitpost">
                <div id="major-publishing-actions">
                    <div id="publishing-action">
                        <span class="spinner"></span>
                        <input name="original_publish" id="original_publish" value="Publier" type="hidden">
                        <input name="save" id="publish" class="button button-primary button-large" value="<?php echo __("Save", "man-admin"); ?>" type="submit">
                    </div>
                    <div class="clear"></div>
                </div>

                <div id="misc-publishing-actions">
                    <div class="misc-pub-section misc-pub-post-status"><label for="post_status"><?php echo __("Load old version", "man-admin"); ?></label>
                        <div>
                            <select name="old_version">
                                <option value="no"><?php echo __("Select", "man-admin"); ?></option>
                                <?php
                                foreach ($data["versions"] as $key => $val) {
                                    $date = new \DateTime( $val["datetime"]);
                                    echo "<option value='".$val["id"]."'>".$date->format('d-m-Y H:i:s')."</options>";
                                }
                                ?>
                            </select>
                            <input class="button" value="<?php echo __("Load", "man-admin"); ?>" type="submit">
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<div id="postbox-container-2" class="postbox-container">
    <div style="" id="side-sortables" class="meta-box-sortables ui-sortable"><div id="submitdiv" class="postbox ">
            <h3 class="hndle ui-sortable-handle"><span><?php echo __("Automatic content", "man-admin"); ?></span></h3>
            <div class="inside">
                <div class="submitbox" id="submitpost">
                    <div id="major-publishing-actions">
                        <div id="publishing-action">
                            <span class="spinner"></span>
                            <input name="original_publish" id="original_publish" value="Publier" type="hidden">
                            <?php
                            if($_COOKIE["visited"]) {
                                $value = __("Stop recording", "man-admin");
                                $name = "stop_record";
                            }
                            else {
                                $value = __("Record now", "man-admin");
                                $name = "start_record";
                            }
                            ?>
                            <input name="<?php echo $name ?>" id="publish" class="button button-primary button-large" value="<?php echo $value; ?>" type="submit">
                            <?php
                            if($_COOKIE["visited"]) {
                                echo "<div><a target='_blank' href='".get_admin_url()."'>".__("Browse in a new window", "man-admin")."</a></div>";
                            }
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div id="misc-publishing-actions">
                        <div class="misc-pub-section misc-pub-post-status">
                                <?php echo __("Here you can open a new window and record all your actions in the manual text area", "man-admin"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--// STOP POSTBOX CONTAINER //-->



    <br class="clear">
</div>
</div>
    </form>
</div>