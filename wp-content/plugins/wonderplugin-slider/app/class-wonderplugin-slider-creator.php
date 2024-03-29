<?php

class WonderPlugin_Slider_Creator {

    private $parent_view, $list_table;

    function __construct($parent) {

        $this->parent_view = $parent;
    }

    function render($id, $config) {
        ?>

        <?php
        $config = str_replace("<", "&lt;", $config);
        $config = str_replace(">", "&gt;", $config);
        ?>

        <h3><?php _e('General Options', 'wonderplugin_slider'); ?></h3>

        <div id="wonderplugin-slider-id" style="display:none;"><?php echo $id; ?></div>
        <div id="wonderplugin-slider-id-config" style="display:none;"><?php echo $config; ?></div>
        <div id="wonderplugin-slider-jsfolder" style="display:none;"><?php echo WONDERPLUGIN_SLIDER_URL . 'engine/'; ?></div>
        <div id="wonderplugin-slider-wp-history-media-uploader" style="display:none;"><?php echo ( function_exists("wp_enqueue_media") ? "0" : "1"); ?></div>

        <div style="margin:0 12px;">
            <table class="wonderplugin-form-table">
                <tr>
                    <th><?php _e('Name', 'wonderplugin_slider'); ?></th>
                    <td><input name="wonderplugin-slider-name" type="text" id="wonderplugin-slider-name" value="My Slider" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><?php _e('Width', 'wonderplugin_slider'); ?> / <?php _e('Height', 'wonderplugin_slider'); ?></th>
                    <td><input name="wonderplugin-slider-width" type="text" id="wonderplugin-slider-width" value="640" class="small-text" /> / <input name="wonderplugin-slider-height" type="text" id="wonderplugin-slider-height" value="300" class="small-text" /></td>
                </tr>
            </table>
        </div>

        <h3><?php _e('Designing', 'wonderplugin_slider'); ?></h3>

        <div style="margin:0 12px;">
            <ul class="wonderplugin-tab-buttons" id="wonderplugin-slider-toolbar">
                <li class="wonderplugin-tab-button step1 wonderplugin-tab-buttons-selected"><?php _e('Images & Videos', 'wonderplugin_slider'); ?></li>
                <li class="wonderplugin-tab-button step2"><?php _e('Skins', 'wonderplugin_slider'); ?></li>
                <li class="wonderplugin-tab-button step3"><?php _e('Options', 'wonderplugin_slider'); ?></li>
                <li class="wonderplugin-tab-button step4"><?php _e('Preview', 'wonderplugin_slider'); ?></li>
                <li class="laststep"><input class="button button-primary" type="button" value="<?php _e('Save & Publish', 'wonderplugin_slider'); ?>"></input></li>
            </ul>

            <ul class="wonderplugin-tabs" id="wonderplugin-slider-tabs">
                <li class="wonderplugin-tab wonderplugin-tab-selected">	

                    <div class="wonderplugin-toolbar">	
                        <input type="button" class="button" id="wonderplugin-add-image" value="<?php _e('Add Image', 'wonderplugin_slider'); ?>" />
                        <input type="button" class="button" id="wonderplugin-add-video" value="<?php _e('Add Video', 'wonderplugin_slider'); ?>" />
                        <input type="button" class="button" id="wonderplugin-add-youtube" value="<?php _e('Add YouTube', 'wonderplugin_slider'); ?>" />
                        <input type="button" class="button" id="wonderplugin-add-vimeo" value="<?php _e('Add Vimeo', 'wonderplugin_slider'); ?>" />
                    </div>

                    <ul class="wonderplugin-table" id="wonderplugin-slider-media-table">
                    </ul>
                    <div style="clear:both;"></div>

                </li>
                <li class="wonderplugin-tab">
                    <form>
                        <fieldset>

                            <?php
                            $skins = array(
                                "classic" => "Classic",
                                "elegant" => "Elegant",
                                "content" => "Content",
                                "contentbox" => "ContentBox",
                                "cube" => "Cube",
                                "events" => "Events",
                                "featurelist" => "FeatureList",
                                "frontpage" => "Frontpage",
                                "mediapage" => "Mediapage",
                                "multirows" => "Multirows",
                                "gallery" => "Gallery",
                                "header" => "Header",
                                "lightbox" => "Lightbox",
                                "navigator" => "Navigator",
                                "numbering" => "Numbering",
                                "pink" => "Pink",
                                "redandblack" => "Red & Black",
                                "rotator" => "Rotator",
                                "showcase" => "Showcase",
                                "simplicity" => "Simplicity",
                                "stylish" => "Stylish",
                                "vertical" => "Vertical",
                                "verticalnumber" => "VerticalNumber",
                                "light" => "Light",
                                "rightthumbs" => "Vertical Thumbnails",
                                "righttabs" => "Vertical Tabs",
                                "righttabsdark" => "Dark Vertical Tabs",
                                "thumbnails" => "Thumbnails Slider",
                                "textnavigation" => "Text Navigation",
                                "simplecontrols" => "Simple Controls",
                                "topcarousel" => "Top Carousel"
                            );

                            foreach ($skins as $key => $value) {
                                ?>
                                <div class="wonderplugin-tab-skin">
                                    <label><input type="radio" name="wonderplugin-slider-skin" value="<?php echo $key; ?>" selected> <?php echo $value; ?> <br /><img class="selected" style="width:300px;" src="<?php echo WONDERPLUGIN_SLIDER_URL; ?>images/<?php echo $key; ?>.jpg" /></label>
                                </div>
                                <?php
                            }
                            ?>

                        </fieldset>
                    </form>
                </li>
                <li class="wonderplugin-tab">

                    <div class="wonderplugin-slider-options">
                        <div class="wonderplugin-slider-options-menu" id="wonderplugin-slider-options-menu">
                            <div class="wonderplugin-slider-options-menu-item wonderplugin-slider-options-menu-item-selected"><?php _e('Slider options', 'wonderplugin_slider'); ?></div>
                            <div class="wonderplugin-slider-options-menu-item"><?php _e('Transition effects', 'wonderplugin_slider'); ?></div>
                            <div class="wonderplugin-slider-options-menu-item"><?php _e('Skin options', 'wonderplugin_slider'); ?></div>
                            <div class="wonderplugin-slider-options-menu-item"><?php _e('Text effect', 'wonderplugin_slider'); ?></div>
                            <div class="wonderplugin-slider-options-menu-item"><?php _e('Lightbox options', 'wonderplugin_slider'); ?></div>
                            <div class="wonderplugin-slider-options-menu-item"><?php _e('Advanced options', 'wonderplugin_slider'); ?></div>
                        </div>

                        <div class="wonderplugin-slider-options-tabs" id="wonderplugin-slider-options-tabs">
                            <div class="wonderplugin-slider-options-tab wonderplugin-slider-options-tab-selected">
                                <table class="wonderplugin-form-table-noborder">
                                    <tr>
                                        <th>Slideshow</th>
                                        <td><label><input name='wonderplugin-slider-autoplay' type='checkbox' id='wonderplugin-slider-autoplay' value='' /> Auto play</label>
                                            <br /><label><input name='wonderplugin-slider-randomplay' type='checkbox' id='wonderplugin-slider-randomplay' value='' /> Random play</label>
                                            <br /><label><input name='wonderplugin-slider-loadimageondemand' type='checkbox' id='wonderplugin-slider-loadimageondemand' value='' /> Load images on demand</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Video</th>
                                        <td><label><input name='wonderplugin-slider-autoplayvideo' type='checkbox' id='wonderplugin-slider-autoplayvideo' value='' /> Auto play video</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Responsive</th>
                                        <td><label><input name='wonderplugin-slider-isresponsive' type='checkbox' id='wonderplugin-slider-isresponsive' value='' /> Create a responsive slider</label><br />
                                            <label><input name='wonderplugin-slider-fullwidth' type='checkbox' id='wonderplugin-slider-fullwidth' value='' /> Create a full width slider</label> &nbsp;&nbsp;
                                            <label><input name='wonderplugin-slider-isfullscreen' type='checkbox' id='wonderplugin-slider-isfullscreen' value='' /> Extend to the parent container height</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Image resize mode</th>
                                        <td><label>
                                                <select name='wonderplugin-slider-scalemode' id='wonderplugin-slider-scalemode'>
                                                    <option value="fit">Resize to fit</option>
                                                    <option value="fill">Resize to fill</option>
                                                </select>
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <th>Text</th>
                                        <td><label><input name='wonderplugin-slider-showtext' type='checkbox' id='wonderplugin-slider-showtext' value='' /> Show text</label></td>
                                    </tr>
                                    <tr>
                                        <th>Timer</th>
                                        <td><label><input name='wonderplugin-slider-showtimer' type='checkbox' id='wonderplugin-slider-showtimer' value='' /> Show a line timer at the bottom of the image when slideshow playing</label></td>
                                    </tr>
                                    <tr>
                                        <th>Loop times ( 0 will loop forever)</th>
                                        <td><label><input name='wonderplugin-slider-loop' type='text' size="10" id='wonderplugin-slider-loop' value='0' /></label></td>
                                    </tr>
                                    <tr>
                                        <th>Slideshow interval (ms)</th>
                                        <td><label><input name='wonderplugin-slider-slideinterval' type='text' size="10" id='wonderplugin-slider-slideinterval' value='8000' /></label></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="wonderplugin-slider-options-tab">
                                <table class="wonderplugin-form-table-noborder">
                                    <tr>
                                        <th><p>Select transition effect</p></th>
                                    <td>
                                        <p><label><input name='wonderplugin-slider-effect-fade' type='checkbox' id='wonderplugin-slider-effect-fade' value='fade' /> Fade</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-crossfade' type='checkbox' id='wonderplugin-slider-effect-crossfade' value='crossfade' /> Crossfade</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-slide' type='checkbox' id='wonderplugin-slider-effect-slide' value='slide' /> Slide</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-elastic' type='checkbox' id='wonderplugin-slider-effect-elastic' value='slide' /> Elastic slide</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-slice' type='checkbox' id='wonderplugin-slider-effect-slice' value='slice' /> Slice</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-blinds' type='checkbox' id='wonderplugin-slider-effect-blinds' value='blinds' /> Blinds</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-threed' type='checkbox' id='wonderplugin-slider-effect-threed' value='threed' /> 3D</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-threedhorizontal' type='checkbox' id='wonderplugin-slider-effect-threedhorizontal' value='threedhorizontal' /> 3D horizontal</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-blocks' type='checkbox' id='wonderplugin-slider-effect-blocks' value='blocks' /> Blocks</label></p>
                                        <p><label><input name='wonderplugin-slider-effect-shuffle' type='checkbox' id='wonderplugin-slider-effect-shuffle' value='shuffle' /> Shuffle</label></p>
                                    </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="wonderplugin-slider-options-tab">
                                <p class="wonderplugin-slider-options-tab-title"><?php _e('Skin option will be restored to its default value if you switch to a new skin in the Skins tab.', 'wonderplugin_gallery'); ?></p>
                                <table class="wonderplugin-form-table-noborder">
                                    <tr>
                                        <th>Slideshow padding</th>
                                        <td>Padding left: <input name='wonderplugin-slider-paddingleft' type='text' size="10" id='wonderplugin-slider-paddingleft' value='0' />
                                            Padding right: <input name='wonderplugin-slider-paddingright' type='text' size="10" id='wonderplugin-slider-paddingright' value='0' />
                                            Padding top: <input name='wonderplugin-slider-paddingtop' type='text' size="10" id='wonderplugin-slider-paddingtop' value='0' />
                                            Padding bottom: <input name='wonderplugin-slider-paddingbottom' type='text' size="10" id='wonderplugin-slider-paddingbottom' value='0' />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Show bottom shadow</th>
                                        <td><label><input name='wonderplugin-slider-showbottomshadow' type='checkbox' id='wonderplugin-slider-showbottomshadow'  /> Show bottom shadow</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Show thumbnail preview</th>
                                        <td><label><input name='wonderplugin-slider-navshowpreview' type='checkbox' id='wonderplugin-slider-navshowpreview'  /> Show thumbnail preview</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Border size</th>
                                        <td><label><input name='wonderplugin-slider-border' type='text' size="10" id='wonderplugin-slider-border' value='0' /></label></td>
                                    </tr>
                                    <tr>
                                        <th>Arrows</th>
                                        <td><label>
                                                <select name='wonderplugin-slider-arrowstyle' id='wonderplugin-slider-arrowstyle'>
                                                    <option value="mouseover">Show on mouseover</option>
                                                    <option value="always">Always show</option>
                                                    <option value="none">Hide</option>
                                                </select>
                                            </label></td>
                                    </tr>
                                    <tr>
                                        <th>Arrow image</th>
                                        <td>
                                            <img id="wonderplugin-slider-displayarrowimage" />
                                            <br />
                                            <label>
                                                <input type="radio" name="wonderplugin-slider-arrowimagemode" value="custom">
                                                <span style="display:inline-block;min-width:240px;">Use own image (absolute URL required):</span>
                                                <input name='wonderplugin-slider-customarrowimage' type='text' class="regular-text" id='wonderplugin-slider-customarrowimage' value='' />
                                            </label>
                                            <br />
                                            <label>
                                                <input type="radio" name="wonderplugin-slider-arrowimagemode" value="defined">
                                                <span style="display:inline-block;min-width:240px;">Select from pre-defined images:</span>
                                                <select name='wonderplugin-slider-arrowimage' id='wonderplugin-slider-arrowimage'>
                                                    <?php
                                                    $arrowimage_list = array("arrows-32-32-0.png", "arrows-32-32-1.png", "arrows-32-32-2.png", "arrows-32-32-3.png", "arrows-32-32-4.png",
                                                        "arrows-36-36-0.png",
                                                        "arrows-36-80-0.png",
                                                        "arrows-48-48-0.png", "arrows-48-48-1.png", "arrows-48-48-2.png", "arrows-48-48-3.png", "arrows-48-48-4.png",
                                                        "arrows-72-72-0.png");
                                                    foreach ($arrowimage_list as $arrowimage)
                                                        echo '<option value="' . $arrowimage . '">' . $arrowimage . '</option>';
                                                    ?>
                                                </select>
                                            </label><br />
                                            <script language="JavaScript">
                                                jQuery(document).ready(function () {
                                                    jQuery("input:radio[name=wonderplugin-slider-arrowimagemode]").click(function () {
                                                        if (jQuery(this).val() == 'custom')
                                                            jQuery("#wonderplugin-slider-displayarrowimage").attr("src", jQuery('#wonderplugin-slider-customarrowimage').val());
                                                        else
                                                            jQuery("#wonderplugin-slider-displayarrowimage").attr("src", "<?php echo WONDERPLUGIN_SLIDER_URL . 'engine/'; ?>" + jQuery('#wonderplugin-slider-arrowimage').val());
                                                    });

                                                    jQuery("#wonderplugin-slider-arrowimage").change(function () {
                                                        if (jQuery("input:radio[name=wonderplugin-slider-arrowimagemode]:checked").val() == 'defined')
                                                            jQuery("#wonderplugin-slider-displayarrowimage").attr("src", "<?php echo WONDERPLUGIN_SLIDER_URL . 'engine/'; ?>" + jQuery(this).val());
                                                        var arrowsize = jQuery(this).val().split("-");
                                                        if (arrowsize.length > 2)
                                                        {
                                                            if (!isNaN(arrowsize[1]))
                                                                jQuery("#wonderplugin-slider-arrowwidth").val(arrowsize[1]);
                                                            if (!isNaN(arrowsize[2]))
                                                                jQuery("#wonderplugin-slider-arrowheight").val(arrowsize[2]);
                                                        }

                                                    });
                                                });
                                            </script>
                                            <label><span style="display:inline-block;min-width:100px;">Width:</span> <input name='wonderplugin-slider-arrowwidth' type='text' size="10" id='wonderplugin-slider-arrowwidth' value='32' /></label>
                                            <label><span style="display:inline-block;min-width:100px;margin-left:36px;">Height:</span> <input name='wonderplugin-slider-arrowheight' type='text' size="10" id='wonderplugin-slider-arrowheight' value='32' /></label><br />
                                            <label><span style="display:inline-block;min-width:100px;">Left/right margin:</span> <input name='wonderplugin-slider-arrowmargin' type='text' size="10" id='wonderplugin-slider-arrowmargin' value='8' /></label>
                                            <label><span style="display:inline-block;min-width:100px;margin-left:36px;">Top (percent):</span> <input name='wonderplugin-slider-arrowtop' type='text' size="10" id='wonderplugin-slider-arrowtop' value='50' /></label>

                                        </td>
                                    </tr>
                                    <tr id="wonderplugin-slider-confignavimage">
                                        <th>Bullets image</th>
                                        <td>
                                            <img id="wonderplugin-slider-displaynavimage" />
                                            <br />
                                            <label>
                                                <input type="radio" name="wonderplugin-slider-navimagemode" value="custom">
                                                <span style="display:inline-block;min-width:240px;">Use own image (absolute URL required):</span>
                                                <input name='wonderplugin-slider-customnavimage' type='text' class="regular-text" id='wonderplugin-slider-customnavimage' value='' />
                                            </label>
                                            <br />
                                            <label>
                                                <input type="radio" name="wonderplugin-slider-navimagemode" value="defined">
                                                <span style="display:inline-block;min-width:240px;">Select from pre-defined images:</span>
                                                <select name='wonderplugin-slider-navimage' id='wonderplugin-slider-navimage'>
                                                    <?php
                                                    $navimage_list = array("bullet-12-12-0.png",
                                                        "bullet-16-16-0.png", "bullet-16-16-1.png", "bullet-16-16-2.png", "bullet-16-16-3.png",
                                                        "bullet-20-20-0.png", "bullet-20-20-1.png",
                                                        "bullet-24-24-0.png", "bullet-24-24-1.png", "bullet-24-24-2.png", "bullet-24-24-3.png", "bullet-24-24-4.png", "bullet-24-24-5.png", "bullet-24-24-6.png");
                                                    foreach ($navimage_list as $navimage)
                                                        echo '<option value="' . $navimage . '">' . $navimage . '</option>';
                                                    ?>
                                                </select>
                                            </label><br />
                                            <script language="JavaScript">
                                                jQuery(document).ready(function () {
                                                    jQuery("input:radio[name=wonderplugin-slider-navimagemode]").click(function () {
                                                        if (jQuery(this).val() == 'custom')
                                                            jQuery("#wonderplugin-slider-displaynavimage").attr("src", jQuery('#wonderplugin-slider-customnavimage').val());
                                                        else
                                                            jQuery("#wonderplugin-slider-displaynavimage").attr("src", "<?php echo WONDERPLUGIN_SLIDER_URL . 'engine/'; ?>" + jQuery('#wonderplugin-slider-navimage').val());
                                                    });

                                                    jQuery("#wonderplugin-slider-navimage").change(function () {
                                                        if (jQuery("input:radio[name=wonderplugin-slider-navimagemode]:checked").val() == 'defined')
                                                            jQuery("#wonderplugin-slider-displaynavimage").attr("src", "<?php echo WONDERPLUGIN_SLIDER_URL . 'engine/'; ?>" + jQuery(this).val());
                                                        var arrowsize = jQuery(this).val().split("-");
                                                        if (arrowsize.length > 2)
                                                        {
                                                            if (!isNaN(arrowsize[1]))
                                                                jQuery("#wonderplugin-slider-navwidth").val(arrowsize[1]);
                                                            if (!isNaN(arrowsize[2]))
                                                                jQuery("#wonderplugin-slider-navheight").val(arrowsize[2]);
                                                        }

                                                    });
                                                });
                                            </script>
                                            <label><span style="display:inline-block;min-width:100px;">Width:</span> <input name='wonderplugin-slider-navwidth' type='text' size="10" id='wonderplugin-slider-navwidth' value='32' /></label>
                                            <label><span style="display:inline-block;min-width:100px;margin-left:36px;">Height:</span> <input name='wonderplugin-slider-navheight' type='text' size="10" id='wonderplugin-slider-navheight' value='32' /></label><br />
                                            <label><span style="display:inline-block;min-width:100px;">Margin X:</span> <input name='wonderplugin-slider-arrowmarginx' type='text' size="10" id='wonderplugin-slider-arrowmarginx' value='8' /></label>
                                            <label><span style="display:inline-block;min-width:100px;margin-left:36px;">Margin Y:</span> <input name='wonderplugin-slider-arrowmarginy' type='text' size="10" id='wonderplugin-slider-arrowmarginy' value='8' /></label><br />
                                            <label><span style="display:inline-block;min-width:100px;">Spacing:</span> <input name='wonderplugin-slider-navspacing' type='text' size="10" id='wonderplugin-slider-navspacing' value='8' /></label>

                                        </td>
                                    </tr>

                                    <tr id="wonderplugin-slider-configplayvideoimage">
                                        <th>Play video button</th>
                                        <td>
                                            <img id="wonderplugin-slider-displayplayvideoimage" />
                                            <br />
                                            <label>
                                                <span style="display:inline-block;min-width:240px;">Select from pre-defined images:</span>
                                                <select name='wonderplugin-slider-playvideoimage' id='wonderplugin-slider-playvideoimage'>
                                                    <?php
                                                    $playvideoimage_list = array("playvideo-64-64-0.png", "playvideo-64-64-1.png", "playvideo-64-64-2.png", "playvideo-64-64-3.png", "playvideo-64-64-4.png", "playvideo-64-64-5.png",
                                                        "playvideo-72-72-0.png");
                                                    foreach ($playvideoimage_list as $playvideoimage)
                                                        echo '<option value="' . $playvideoimage . '">' . $playvideoimage . '</option>';
                                                    ?>
                                                </select>
                                            </label><br />
                                            <script language="JavaScript">
                                                jQuery(document).ready(function () {

                                                    jQuery("#wonderplugin-slider-playvideoimage").change(function () {
                                                        jQuery("#wonderplugin-slider-displayplayvideoimage").attr("src", "<?php echo WONDERPLUGIN_SLIDER_URL . 'engine/'; ?>" + jQuery(this).val());
                                                        var arrowsize = jQuery(this).val().split("-");
                                                        if (arrowsize.length > 2)
                                                        {
                                                            if (!isNaN(arrowsize[1]))
                                                                jQuery("#wonderplugin-slider-playvideoimagewidth").val(arrowsize[1]);
                                                            if (!isNaN(arrowsize[2]))
                                                                jQuery("#wonderplugin-slider-playvideoimageheight").val(arrowsize[2]);
                                                        }
                                                    });
                                                });
                                            </script>
                                            <label><span style="display:inline-block;min-width:100px;">Width:</span> <input name='wonderplugin-slider-playvideoimagewidth' type='text' size="10" id='wonderplugin-slider-playvideoimagewidth' value='32' /></label>
                                            <label><span style="display:inline-block;min-width:100px;margin-left:36px;">Height:</span> <input name='wonderplugin-slider-playvideoimageheight' type='text' size="10" id='wonderplugin-slider-playvideoimageheight' value='32' /></label><br />										
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="wonderplugin-slider-options-tab">
                                <table class="wonderplugin-form-table-noborder">
                                    <tr>
                                        <th>Select a pre-defined text effect</th>
                                        <td><label>
                                                <select name='wonderplugin-slider-textformat' id='wonderplugin-slider-textformat'>
                                                    <?php
                                                    $textformat_list = array('Bottom bar', 'Bottom left', 'Center text', 'Center box', 'Left text', 'Color box', 'Blue box', 'Red box', 'Navy box', 'Pink box', 'Light box', 'Grey box', 'Color box right align', 'Red title', 'White title', 'Yellow title', 'Underneath center', 'Underneath left', 'None');
                                                    foreach ($textformat_list as $textformat)
                                                        echo '<option value="' . $textformat . '">' . $textformat . '</option>';
                                                    ?>
                                                </select>
                                            </label></td>
                                    </tr>

                                    <tr>
                                        <th></th>
                                        <td>
                                            <p>* The following options will be restored to the default value if you change text effect in the above drop-down list.</p>
                                            <div class='wonderplugin-slider-texteffect-static'>
                                                <label><input name='wonderplugin-slider-textautohide' type='checkbox' id='wonderplugin-slider-textautohide' value='' /> Auto hide text</label>
                                            </div>

                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Text CSS</th>
                                        <td><label><textarea name="wonderplugin-slider-textcss" id="wonderplugin-slider-textcss" rows="2" class="large-text code"></textarea></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Text background CSS</th>
                                        <td><label><textarea name="wonderplugin-slider-textbgcss" id="wonderplugin-slider-textbgcss" rows="2" class="large-text code"></textarea></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Title CSS</th>
                                        <td><label><textarea name="wonderplugin-slider-titlecss" id="wonderplugin-slider-titlecss" rows="2" class="large-text code"></textarea></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Description CSS</th>
                                        <td><label><textarea name="wonderplugin-slider-descriptioncss" id="wonderplugin-slider-descriptioncss" rows="2" class="large-text code"></textarea></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Position</th>
                                        <td>
                                            <div class='wonderplugin-slider-texteffect-static'>
                                                <select name='wonderplugin-slider-textpositionstatic' id='wonderplugin-slider-textpositionstatic'>
                                                    <option value="top">top</option>
                                                    <option value="bottom">bottom</option>
                                                    <option value="topoutside">topoutside</option>
                                                    <option value="bottomoutside">bottomoutside</option>
                                                </select>
                                            </div>
                                            <div  class='wonderplugin-slider-texteffect-dynamic'>
                                                <label><input name='wonderplugin-slider-textpositiondynamic-topleft' type='checkbox' id='wonderplugin-slider-textpositiondynamic-topleft' value='topleft' /> topleft</label> 
                                                <label><input name='wonderplugin-slider-textpositiondynamic-topright' type='checkbox' id='wonderplugin-slider-textpositiondynamic-topright' value='topright' /> topright</label> 
                                                <label><input name='wonderplugin-slider-textpositiondynamic-bottomleft' type='checkbox' id='wonderplugin-slider-textpositiondynamic-bottomleft' value='bottomleft' /> bottomleft</label> 
                                                <label><input name='wonderplugin-slider-textpositiondynamic-bottomright' type='checkbox' id='wonderplugin-slider-textpositiondynamic-bottomright' value='bottomright' /> bottomright</label>
                                                <label><input name='wonderplugin-slider-textpositiondynamic-topcenter' type='checkbox' id='wonderplugin-slider-textpositiondynamic-topcenter' value='topcenter' /> topcenter</label>
                                                <label><input name='wonderplugin-slider-textpositiondynamic-bottomcenter' type='checkbox' id='wonderplugin-slider-textpositiondynamic-bottomcenter' value='bottomcenter' /> bottomcenter</label>
                                                <label><input name='wonderplugin-slider-textpositiondynamic-centercenter' type='checkbox' id='wonderplugin-slider-textpositiondynamic-centercenter' value='centercenter' /> centercenter</label>
                                            </div>
                                        </td>
                                    </tr>

                                </table>
                            </div>

                            <div class="wonderplugin-slider-options-tab">
                                <table class="wonderplugin-form-table-noborder">
                                    <tr>
                                        <th>Responsive</th>
                                        <td><label><input name='wonderplugin-slider-lightboxresponsive' type='checkbox' id='wonderplugin-slider-lightboxresponsive'  /> Responsive</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Thumbnails</th>
                                        <td><label><input name='wonderplugin-slider-lightboxshownavigation' type='checkbox' id='wonderplugin-slider-lightboxshownavigation'  /> Show thumbnails</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <td><label>Size: <input name="wonderplugin-slider-lightboxthumbwidth" type="text" id="wonderplugin-slider-lightboxthumbwidth" value="96" class="small-text" /> x <input name="wonderplugin-slider-lightboxthumbheight" type="text" id="wonderplugin-slider-lightboxthumbheight" value="72" class="small-text" /></label> 
                                            <label>Top margin: <input name="wonderplugin-slider-lightboxthumbtopmargin" type="text" id="wonderplugin-slider-lightboxthumbtopmargin" value="12" class="small-text" /> Bottom margin: <input name="wonderplugin-slider-lightboxthumbbottommargin" type="text" id="wonderplugin-slider-lightboxthumbbottommargin" value="12" class="small-text" /></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Maximum text bar height</th>
                                        <td><label><input name="wonderplugin-slider-lightboxbarheight" type="text" id="wonderplugin-slider-lightboxbarheight" value="64" class="small-text" /></label>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row">Title</th>
                                        <td><label><input name="wonderplugin-slider-lightboxshowtitle" type="checkbox" id="wonderplugin-slider-lightboxshowtitle" /> Show title</label></td>
                                    </tr>

                                    <tr>
                                        <th>Title CSS</th>
                                        <td><label><textarea name="wonderplugin-slider-lightboxtitlebottomcss" id="wonderplugin-slider-lightboxtitlebottomcss" rows="2" class="large-text code"></textarea></label>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row">Description</th>
                                        <td><label><input name="wonderplugin-slider-lightboxshowdescription" type="checkbox" id="wonderplugin-slider-lightboxshowdescription" /> Show description</label></td>
                                    </tr>

                                    <tr>
                                        <th>Description CSS</th>
                                        <td><label><textarea name="wonderplugin-slider-lightboxdescriptionbottomcss" id="wonderplugin-slider-lightboxdescriptionbottomcss" rows="2" class="large-text code"></textarea></label>
                                        </td>
                                    </tr>

                                </table>
                            </div>						

                            <div class="wonderplugin-slider-options-tab">
                                <table class="wonderplugin-form-table-noborder">
                                    <tr>
                                        <th>Custom CSS</th>
                                        <td><textarea name='wonderplugin-slider-custom-css' id='wonderplugin-slider-custom-css' value='' class='large-text' rows="10"></textarea></td>
                                    </tr>
                                    <tr>
                                        <th>Advanced Options</th>
                                        <td><textarea name='wonderplugin-slider-data-options' id='wonderplugin-slider-data-options' value='' class='large-text' rows="10"></textarea></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="clear:both;"></div>

                </li>
                <li class="wonderplugin-tab">
                    <div id="wonderplugin-slider-preview-tab">
                        <div id="wonderplugin-slider-preview-container">
                        </div>
                    </div>
                </li>
                <li class="wonderplugin-tab">
                    <div id="wonderplugin-slider-publish-loading"></div>
                    <div id="wonderplugin-slider-publish-information"></div>
                </li>
            </ul>
        </div>

        <?php
    }

    function get_list_data() {
        return array();
    }

}
