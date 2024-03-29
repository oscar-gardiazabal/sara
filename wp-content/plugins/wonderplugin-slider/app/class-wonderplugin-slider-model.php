<?php

require_once 'wonderplugin-slider-functions.php';

class WonderPlugin_Slider_Model {

    private $controller;

    function __construct($controller) {

        $this->controller = $controller;
    }

    function get_upload_path() {

        $uploads = wp_upload_dir();
        return $uploads['basedir'] . '/wonderplugin-slider/';
    }

    function get_upload_url() {

        $uploads = wp_upload_dir();
        return $uploads['baseurl'] . '/wonderplugin-slider/';
    }

    function eacape_html_quotes($str) {

        $result = str_replace("\'", "&#39;", $str);
        $result = str_replace('\"', '&quot;', $result);
        $result = str_replace("'", "&#39;", $result);
        $result = str_replace('"', '&quot;', $result);
        return $result;
    }

    function generate_body_code($id, $has_wrapper) {

        global $wpdb;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $ret = "";
        $item_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        if ($item_row != null) {
            $data = json_decode($item_row->data);

            if (isset($data->customcss) && strlen($data->customcss) > 0) {
                $customcss = str_replace("\r", " ", $data->customcss);
                $customcss = str_replace("\n", " ", $customcss);
                $customcss = str_replace("SLIDERID", $id, $customcss);
                $ret .= '<style type="text/css">' . $customcss . '</style>';
            }

            $ret .= '<div class="wonderpluginslider-container" id="wonderpluginslider-container-' . $id . '" style="';

            if ((isset($data->fullwidth) && strtolower($data->fullwidth) === 'true') || (isset($data->isresponsive) && strtolower($data->isresponsive) === 'true' && !isset($data->fullwidth))) {
                $ret .= 'max-width:100%;';

                if (isset($data->isfullscreen) && strtolower($data->isfullscreen) === 'true') {
                    if ($has_wrapper)
                        $ret .= 'height:500px;';
                    else
                        $ret .= 'height:100%;';
                }
            }
            else {
                $ret .= 'max-width:' . $data->width . 'px;';
            }

            if ($has_wrapper)
                $ret .= 'margin:0 auto 180px;';
            else
                $ret .= 'margin:0 auto;';

            if (isset($data->paddingleft))
                $ret .= 'padding-left:' . $data->paddingleft . 'px;';

            if (isset($data->paddingright))
                $ret .= 'padding-right:' . $data->paddingright . 'px;';

            if (isset($data->paddingtop))
                $ret .= 'padding-top:' . $data->paddingtop . 'px;';

            if (isset($data->paddingbottom))
                $ret .= 'padding-bottom:' . $data->paddingbottom . 'px;';

            $ret .= '">';

            // div data tag
            $ret .= '<div class="wonderpluginslider" id="wonderpluginslider-' . $id . '" data-sliderid="' . $id . '" data-width="' . $data->width . '" data-height="' . $data->height . '" data-skin="' . $data->skin . '"';

            if (isset($data->dataoptions) && strlen($data->dataoptions) > 0) {
                $ret .= ' ' . stripslashes($data->dataoptions);
            }

            $boolOptions = array('autoplay', 'randomplay', 'loadimageondemand', 'autoplayvideo', 'isresponsive', 'fullwidth', 'isfullscreen', 'showtext', 'showtimer', 'showbottomshadow', 'navshowpreview', 'textautohide',
                'lightboxresponsive', 'lightboxshownavigation', 'lightboxshowtitle', 'lightboxshowdescription');
            foreach ($boolOptions as $key) {
                if (isset($data->{$key}))
                    $ret .= ' data-' . $key . '="' . ((strtolower($data->{$key}) === 'true') ? 'true' : 'false') . '"';
            }

            $valOptions = array('scalemode', 'arrowstyle', 'transition', 'loop', 'border', 'slideinterval',
                'arrowimage', 'arrowwidth', 'arrowheight', 'arrowtop', 'arrowmargin',
                'navimage', 'navwidth', 'navheight', 'navspacing', 'navmarginx', 'navmarginy',
                'playvideoimage', 'playvideoimagewidth', 'playvideoimageheight', 'lightboxthumbwidth', 'lightboxthumbheight', 'lightboxthumbtopmargin', 'lightboxthumbbottommargin', 'lightboxbarheight', 'lightboxtitlebottomcss', 'lightboxdescriptionbottomcss',
                'textformat', 'textpositionstatic', 'textpositiondynamic', 'paddingleft', 'paddingright', 'paddingtop', 'paddingbottom');

            foreach ($valOptions as $key) {
                if (isset($data->{$key}))
                    $ret .= ' data-' . $key . '="' . $data->{$key} . '"';
            }

            $cssOptions = array('textcss', 'textbgcss', 'titlecss', 'descriptioncss');
            foreach ($cssOptions as $key) {
                if (isset($data->{$key}))
                    $ret .= ' data-' . $key . '="' . $this->eacape_html_quotes($data->{$key}) . '"';
            }

            $ret .= ' data-jsfolder="' . WONDERPLUGIN_SLIDER_URL . 'engine/"';
            $ret .= ' style="display:none;" >';

            if (isset($data->slides) && count($data->slides) > 0) {
                $ret .= '<ul class="amazingslider-slides" style="display:none;">';

                $index = 0;
                foreach ($data->slides as $slide) {
                    $boolOptions = array('lightbox', 'lightboxsize');
                    foreach ($boolOptions as $key) {
                        if (isset($slide->{$key}))
                            $slide->{$key} = ((strtolower($slide->{$key}) === 'true') ? true : false);
                    }

                    $ret .= '<li>';


                    //TROLL START MODIFICATION CODE
                    //if ($slide->lightbox) {
                    if (true) {
                    //TROLL END MODIFICATION CODE

                        $ret .= '<a href="';
                        if ($slide->type == 0) {
                            $ret .= $slide->image;
                        } else if ($slide->type == 1) {
                            $ret .= $slide->mp4;
                            if ($slide->webm)
                                $ret .= '" data-webm="' . $slide->webm;
                        }
                        else if ($slide->type == 2 || $slide->type == 3) {
                            $ret .= $slide->video;
                        }

                        if ($slide->lightboxsize)
                            $ret .= '" data-width="' . $slide->lightboxwidth . '" data-height="' . $slide->lightboxheight;

                        if ($slide->description && strlen($slide->description) > 0)
                            $ret .= '" data-description="' . $this->eacape_html_quotes($slide->description);

                        $ret .= '" class="html5lightbox">';
                    }
                    else if ($slide->weblink && strlen($slide->weblink) > 0) {
                        $ret .= '<a href="' . $slide->weblink . '"';
                        if ($slide->linktarget && strlen($slide->linktarget) > 0)
                            $ret .= ' target="' . $slide->linktarget . '"';
                        $ret .= '>';
                    }

                    if ($index > 0 && isset($data->loadimageondemand) && strtolower($data->loadimageondemand) === 'true')
                        $ret .= '<img data-src="';
                    else
                        $ret .= '<img src="';
                    $ret .= $slide->image . '"';
                    $ret .= ' alt="' . $this->eacape_html_quotes($slide->title) . '"';
                    $ret .= ' data-description="' . $this->eacape_html_quotes($slide->description) . '"';
                    $ret .= ' />';

                    if ($slide->lightbox || ($slide->weblink && strlen($slide->weblink) > 0)) {
                        $ret .= '</a>';
                    }

                    if (!$slide->lightbox) {
                        if ($slide->type == 1) {
                            $ret .= '<video preload="none" src="' . $slide->mp4 . '"';
                            if ($slide->webm)
                                $ret .= ' data-webm="' . $slide->webm . '"';
                            $ret .= '></video>';
                        }
                        else if ($slide->type == 2 || $slide->type == 3) {
                            $ret .= '<video preload="none" src="' . $slide->video . '"></video>';
                        }
                    }


                    if ($slide->button && strlen($slide->button) > 0) {
                        if ($slide->buttonlink && strlen($slide->buttonlink) > 0) {
                            $ret .= '<a href="' . $slide->buttonlink . '"';
                            if ($slide->buttonlinktarget && strlen($slide->buttonlinktarget) > 0)
                                $ret .= ' target="' . $slide->buttonlinktarget . '"';
                            $ret .= '>';
                        }

                        $ret .= '<button class="' . $slide->buttoncss . '">' . $slide->button . '</button>';

                        if ($slide->buttonlink && strlen($slide->buttonlink) > 0) {
                            $ret .= '</a>';
                        }
                    }

                    $ret .= '</li>';

                    $index++;
                }
                $ret .= '</ul>';

                $ret .= '<ul class="amazingslider-thumbnails" style="display:none;">';
                foreach ($data->slides as $slide) {
                    $ret .= '<li><img src="' . $slide->thumbnail . '"';
                    $ret .= ' alt="' . $this->eacape_html_quotes($slide->title) . '"';
                    $ret .= ' title="' . $this->eacape_html_quotes($slide->description) . '"';
                    $ret .= ' /></li>';
                }
                $ret .= '</ul>';
            }
            if ('F' == 'F')
                $ret .= '<div class="wonderplugin-engine"><a href="http://www.wonderplugin.com/wordpress-slider/" title="' . get_option('wonderplugin-slider-engine') . '">' . get_option('wonderplugin-slider-engine') . '</a></div>';
            $ret .= '</div>';

            $ret .= '</div>';
        }
        else {
            $ret = '<p>The specified slider id does not exist.</p>';
        }
        return $ret;
    }

    function delete_item($id) {

        global $wpdb;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $ret = $wpdb->query($wpdb->prepare(
                        "
				DELETE FROM $table_name WHERE id=%s
				", $id
        ));

        return $ret;
    }

    function clone_item($id) {

        global $wpdb, $user_ID;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $cloned_id = -1;

        $item_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        if ($item_row != null) {
            $time = current_time('mysql');
            $authorid = $user_ID;

            $ret = $wpdb->query($wpdb->prepare(
                            "
					INSERT INTO $table_name (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					", $item_row->name, $item_row->data, $time, $authorid
            ));

            if ($ret)
                $cloned_id = $wpdb->insert_id;
        }

        return $cloned_id;
    }

    function is_db_table_exists() {

        global $wpdb;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        return ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name );
    }

    function is_id_exist($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $slider_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        return ($slider_row != null);
    }

    function create_db_table() {

        global $wpdb;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $charset = '';
        if (!empty($wpdb->charset))
            $charset = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset .= " COLLATE $wpdb->collate";

        $sql = "CREATE TABLE $table_name (
		id INT(11) NOT NULL AUTO_INCREMENT,
		name tinytext DEFAULT '' NOT NULL,
		data MEDIUMTEXT DEFAULT '' NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		authorid tinytext NOT NULL,
		PRIMARY KEY  (id)
		) $charset;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function save_item($item) {

        if (!$this->is_db_table_exists())
            $this->create_db_table();

        global $wpdb, $user_ID;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $id = $item["id"];
        $name = $item["name"];

        unset($item["id"]);
        $data = json_encode($item);

        $time = current_time('mysql');
        $authorid = $user_ID;

        if (($id > 0) && $this->is_id_exist($id)) {
            $ret = $wpdb->query($wpdb->prepare(
                            "
					UPDATE $table_name
					SET name=%s, data=%s, time=%s, authorid=%s
					WHERE id=%d
					", $name, $data, $time, $authorid, $id
            ));

            if (!$ret) {
                return array(
                    "success" => false,
                    "id" => $id,
                    "message" => "Cannot update the slider in database"
                );
            }
        } else {
            $ret = $wpdb->query($wpdb->prepare(
                            "
					INSERT INTO $table_name (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					", $name, $data, $time, $authorid
            ));

            if (!$ret) {
                return array(
                    "success" => false,
                    "id" => -1,
                    "message" => "Cannot insert the slider to database"
                );
            }

            $id = $wpdb->insert_id;
        }

        return array(
            "success" => true,
            "id" => intval($id),
            "message" => "Slider published!"
        );
    }

    function get_list_data() {

        global $wpdb;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $rows = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        $ret = array();

        if ($rows) {
            foreach ($rows as $row) {
                $ret[] = array(
                    "id" => $row['id'],
                    'name' => $row['name'],
                    'data' => $row['data'],
                    'time' => $row['time'],
                    'author' => $row['authorid']
                );
            }
        }

        return $ret;
    }

    function get_item_data($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wonderplugin_slider";

        $ret = "";
        $item_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        if ($item_row != null) {
            $ret = $item_row->data;
        }

        return $ret;
    }

    function get_userrole() {

        $userrole = get_option('wonderplugin_slider_userrole');
        if ($userrole == false) {
            update_option('wonderplugin_slider_userrole', 'manage_options');
            $userrole = 'manage_options';
        }

        return $userrole;
    }

    function save_settings($options) {

        if (!isset($options) || !isset($options['userrole']))
            $userrole = 'manage_options';
        else if ($options['userrole'] == "Editor")
            $userrole = 'moderate_comments';
        else if ($options['userrole'] == "Author")
            $userrole = 'upload_files';
        else
            $userrole = 'manage_options';
        update_option('wonderplugin_slider_userrole', $userrole);
    }

}
