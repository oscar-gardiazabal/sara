<?php

/**
 * @package Disable_Directory_Listings
 * @author Scott Reilly
 * @version 2.0
 */
/*
  Plugin Name: Disable Directory Listings
  Version: 2.0
  Plugin URI: http://coffee2code.com/wp-plugins/disable-directory-listings/
  Author: Scott Reilly
  Author URI: http://coffee2code.com
  Text Domain: disable-directory-listings
  Description: Prevent virtual directory listing services from listing the contents of directories, and/or show a page in place of a directory's listing.


  Compatible with WordPress 3.0+, 3.1+, 3.2+.

  =>> Read the accompanying readme.txt file for instructions and documentation.
  =>> Also, visit the plugin's homepage for additional information and updates.
  =>> Or visit: http://coffee2code.com/wp-plugins/disable-directory-listings/

  TODO:
 * Detect if virtual dir listing is enabled (for Apache at least), if possible
 * Utility to scan file system for exposed directories
 * When .htaccess is not writable, compare the current .htaccess with what would have been written by the plugin.
  Only when the two don't match should the plugin's option page show a message indicating the rewrite rules are
  out of date
 * Support IIS7 as much as WP does (?)
 */

/*
  Copyright (c) 2006-2011 by Scott Reilly (aka coffee2code)

  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
  files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
  modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
  Software is furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
  OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
  LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
  IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 */

if (!class_exists('c2c_DisableDirectoryListings')) :

    require_once( 'c2c-plugin.php' );

    class c2c_DisableDirectoryListings extends C2C_Plugin_026 {

        public static $instance;
        private static $being_deactivated = false;

        /**
         * Constructor
         */
        public function __construct() {
            $this->c2c_DisableDirectoryListings();
        }

        public function c2c_DisableDirectoryListings() {
            // Be a singleton
            if (!is_null(self::$instance))
                return;

            $this->C2C_Plugin_026('2.0', 'disable-directory-listings', 'c2c', __FILE__, array());
            register_activation_hook(__FILE__, array(__CLASS__, 'activation'));
            self::$instance = $this;
        }

        /**
         * Handles activation tasks, such as registering the uninstall hook.
         *
         * @since 2.0
         *
         * @return void
         */
        public function activation() {
            register_uninstall_hook(__FILE__, array(__CLASS__, 'uninstall'));
        }

        /**
         * Handles uninstallation tasks, such as deleting plugin options.
         *
         * This can be overridden.
         *
         * @since 2.0
         *
         * @return void
         */
        public function uninstall() {
            delete_option('c2c_disable_directory_listings');
            self::$being_deactivated = true;
            self::regenerate_rewrite_rules();
        }

        /**
         * Initializes the plugin's config data array.
         *
         * @since 2.0
         *
         * @return void
         */
        public function load_config() {
            $this->name = __('Disable Directory Listings', $this->textdomain);
            $this->menu_name = __('Disable Directory Listings', $this->textdomain);
            $this->config = array(
                'disable_all_listings' => array('input' => 'checkbox', 'default' => true,
                    'label' => __('Disable listing for all directories?', $this->textdomain),
                    'help' => __('If set, this does not necessarily obviate the setting below; if this option is set and a given directory is not explicitly listed below, the web server will still acknowledge that the directory exists (though it won\'t list its contents) and the visitor will NOT see a themed 404 error page nor will a Page with the same name as the directory be shown instead.', $this->textdomain)),
                'disabled_directories' => array('input' => 'inline_textarea', 'datatype' => 'array',
                    'default' => array('wp-includes', 'wp-content', 'wp-content/plugins', 'wp-content/themes'),
                    'label' => __('Disable listing for these directories', $this->textdomain),
                    'no_wrap' => true, 'input_attributes' => 'rows="8"',
                    'help' => __('List only one directory per line, leading and trailing forward slashes are not necessary', $this->textdomain))
            );
        }

        /**
         * Override the plugin framework's register_filters() to actually actions against filters.
         *
         * @since 2.0
         *
         * @return void
         */
        public function register_filters() {
            add_filter('mod_rewrite_rules', array(&$this, 'disable_dir_listing'));
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('admin_init', array(&$this, 'maybe_regenerate_rewrite_rules'), 1);
            add_action('admin_notices', array(__CLASS__, 'display_activation_notice')); // On plugins page
            add_action('admin_notices', array(&$this, 'regenerate_rewrite_rules_notice'));
            // Uncomment the next line (by removing the // characters at the beginning of the line) to allow
            //		disabled directories to redirect to the site's main page  (NOT INTENDED FOR USE YET)
            //add_filter('generate_rewrite_rules', array(&$this, 'redirect_disabled_dir_listings_to_home'));
        }

        /**
         * On activation, set transient and settings.
         *
         * @since 2.0
         *
         */
        public function install() {
            set_transient('c2c_ddl_activated', 'show', 10);
            $options = $this->get_options();
            update_option($this->admin_options_name, $options);
            self::regenerate_rewrite_rules();
        }

        /**
         * On deactivation, regenerate rewrite rules to remove any added by plugin
         *
         * @since 2.0
         *
         */
        public function deactivate() {
            self::$being_deactivated = true;
            self::regenerate_rewrite_rules();
        }

        /**
         * Regenerates rewrite rules if the plugin was just activated or its settings were changed.
         *
         * @since 2.0
         *
         */
        public function maybe_regenerate_rewrite_rules() {
            if (get_transient('c2c_ddl_activated') || $this->is_submitting_form())
                self::regenerate_rewrite_rules();
        }

        /**
         * Outputs notice on activation if site's .htaccess is not currently writable.
         *
         * @since 2.0
         *
         * @return void (Text is echoed.)
         */
        public static function display_activation_notice() {
            if (get_transient('c2c_ddl_activated') && !self::can_write_htaccess()) {
                $msg = sprintf(__('<strong>NOTE:</strong> Your .htaccess file is not currently writable. Please manually update your .htaccess file in order to gain directory listing protection (via <a href="%s" title="Settings - Permalinks">here</a>)', 'disable-directory-listings'), admin_url('options-permalink.php'));
                echo "<div id='message' class='updated fade'><p>$msg</p></div>";
            }
        }

        /**
         * Outputs status message indicating rewrite rules were regenerated or need
         * to be manually generated.
         *
         * @since 2.0
         */
        public function regenerate_rewrite_rules_notice() {
            if (!( $this->is_plugin_admin_page() && get_transient('c2c_ddl_regenerated') ))
                return;

            self::regenerate_rewrite_rules();

            $permalinks_page = "<a href='" . admin_url('options-permalink.php') . "'>Settings &raquo; Permalinks</a>";

            if (get_transient('c2c_ddl_regenerated'))
                $msg = __('Rewrite rules have also been regenerated.', $this->textdomain);
            else
                $msg = sprintf(__('Go to %s to see how to manually update your rewrite rules.', $this->textdomain), $permalinks_page);

            echo "<div id='message' class='updated fade'><p>$msg</p></div>";
        }

        /**
         * Outputs the text above the setting form
         *
         * @since 2.0
         *
         * @return void (Text will be echoed.)
         */
        public function options_page_description() {
            $options = $this->get_options();
            parent::options_page_description(__('Disable Directory Listings', $this->textdomain));

            $permalinks_page = "<a href='" . admin_url('options-permalink.php') . "'>Settings &raquo; Permalinks</a>";

            if (!self::can_write_htaccess()) {
                echo '<p>' . sprintf(__('<strong>NOTE:</strong> Your .htaccess file is not currently writable so any updates you make to the settings on this page will require you to manually update your .htaccess file in order to regenerate the rewrite rules. After saving your changes, go to the %s admin page to learn more and obtain the necessary rewrite rules.', $this->textdomain), $permalinks_page) . "</p>\n";

                echo '<p><strong><em>' . __('If you have not regenerated your rewrite rules since activating the plugin you must do so! You must also do so when deactivating this plugin.', $this->textdomain) . "</em></strong></p>\n";
            }

            _e('Prevent virtual directory listing services from listing the contents of directories, and/or show a page in place of a directory\'s listing.', $this->textdomain);
        }

        /**
         * Forces regeneration of rewrite rules.
         */
        public function regenerate_rewrite_rules() {
            if (!self::can_write_htaccess()) {
                set_transient('c2c_ddl_regenerated', false, 10);
                return false;
            }
            flush_rewrite_rules(true);
            set_transient('c2c_ddl_regenerated', true, 10);
            return true;
        }

        /**
         * Checks if the site's .htaccess file is writable.
         *
         * @return bool Boolean indicating true (.htaccess file is writable) or false (it isn't)
         */
        public function can_write_htaccess() {
            // The checks in this function were all lifted from wp-admin/options-permalink.php
            global $wp_rewrite;
            $permalink_structure = get_option('permalink_structure');
            $home_path = get_home_path();

            if (iis7_supports_permalinks()) {
                // Not trying to support IIS7 at the moment.
                $writable = false;
            } else {
                if ((!file_exists($home_path . '.htaccess') && is_writable($home_path) ) || is_writable($home_path . '.htaccess'))
                    $writable = true;
                else
                    $writable = false;
            }

            if ($wp_rewrite->using_index_permalinks())
                $usingpi = true;
            else
                $usingpi = false;

            return ( $permalink_structure && !$usingpi && !$writable ) ? false : true;
        }

        /**
         * Amends rewrite rules to explicitly redirect disabled directories to site's root.
         *
         * @param WP_Rewrite The rewrite object
         */
        public function redirect_disabled_dir_listings_to_home($wp_rewrite) {
            if (self::$being_deactivated)
                return;

            $options = $this->get_options();
            $disable_rules = array();
            foreach ($options['disabled_directories'] as $dir)
                $disable_rules[$dir . '/?'] = '';

            $wp_rewrite->rules = $disable_rules + $wp_rewrite->rules;
        }

        /**
         * Amends rewrite rules to disable directory listings.
         *
         * @param string $rules Current rewrite rules
         * @return string Amended rewrite rules
         */
        public function disable_dir_listing($rules = '') {
            if (self::$being_deactivated)
                return $rules;

            $options = $this->get_options();

            $site_root = parse_url(get_option('siteurl'));
            if (isset($site_root['path']))
                $site_root = trailingslashit($site_root['path']);
            else
                $site_root = '/';

            $home_root = parse_url(home_url());
            if (isset($home_root['path']))
                $home_root = trailingslashit($home_root['path']);
            else
                $home_root = '/';

            $rules .= "<IfModule mod_rewrite.c>\n";
            $rules .= "RewriteEngine On\n";
            $rules .= "RewriteBase $home_root\n";
            if ($options['disable_all_listings'])
                $rules .= "IndexIgnore *\n";
            foreach ($options['disabled_directories'] as $dir)
                $rules .= 'RewriteRule ^' . $dir . '/?$' . " $site_root [QSA,L]\n";

            $rules .= "</IfModule>\n";
            return $rules;
        }

    }

    // end c2c_DisableDirectoryListings
// NOTICE: The 'disable_directory_listings' global is deprecated and will be removed in the plugin's version 2.1.
// Instead, use: c2c_DisableDirectoryListings::$instance
    $GLOBALS['disable_directory_listings'] = new c2c_DisableDirectoryListings();

endif; // end if !class_exists()
?>