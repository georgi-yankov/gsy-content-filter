<?php
if (!class_exists('GSY_Content_Filter')) {

    class GSY_Content_Filter {

        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Start up
         */
        public function __construct() {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
        }

        /**
         * Add options page
         */
        public function add_plugin_page() {
            // This page will be under "Settings"
            add_options_page('GSY Content Filter', 'GSY Content Filter', 'manage_options', 'gsy-content-filter', array($this, 'create_admin_page'));
        }

        /**
         * Options page callback
         */
        public function create_admin_page() {
            // Set class property
            $this->options = get_option('gsy-content-filter-options');
            ?>
            <div class="wrap">
                <h2><?php _e('GSY Content Filter', 'gsy-content-filter'); ?></h2>           
                <form method="post" action="options.php" role="form">
                    <?php
                    // This prints out all hidden setting fields
                    settings_fields('gsy-content-filter-group');
                    do_settings_sections('gsy-content-filter');
                    submit_button();
                    ?>
                </form>
            </div>
            <?php
        }

        /**
         * Register and add settings
         */
        public function page_init() {
            register_setting(
                    'gsy-content-filter-group', // Option group
                    'gsy-content-filter-options', // Option name
                    array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                    'setting_section_id', // ID
                    'Settings', // Title
                    array($this, 'print_section_info'), // Callback
                    'gsy-content-filter' // Page
            );

            add_settings_field(
                    'id_number', // ID
                    'ID Number', // Title 
                    array($this, 'id_number_callback'), // Callback
                    'gsy-content-filter', // Page
                    'setting_section_id' // Section           
            );

            add_settings_field(
                    'title', // ID
                    'Title', // Title
                    array($this, 'title_callback'), // Callback
                    'gsy-content-filter', // Page 
                    'setting_section_id' // Section
            );
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize($input) {
            $new_input = array();
            if (isset($input['id_number'])) {
                $new_input['id_number'] = absint($input['id_number']);
            }

            if (isset($input['title'])) {
                $new_input['title'] = sanitize_text_field($input['title']);
            }

            return $new_input;
        }

        /**
         * Print the Section text
         */
        public function print_section_info() {
            print 'Enter your settings below:';
        }

        /**
         * Get the settings option array and print one of its values
         */
        public function id_number_callback() {
            printf(
                    '<input type="text" id="id_number" name="gsy-content-filter-options[id_number]" value="%s" />', isset($this->options['id_number']) ? esc_attr($this->options['id_number']) : ''
            );
        }

        /**
         * Get the settings option array and print one of its values
         */
        public function title_callback() {
            printf(
                    '<input type="text" id="title" name="gsy-content-filter-options[title]" value="%s" />', isset($this->options['title']) ? esc_attr($this->options['title']) : ''
            );
        }

    }

}