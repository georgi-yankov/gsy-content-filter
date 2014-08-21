<?php
if (!class_exists('GSY_Content_Filter')) {

    class GSY_Content_Filter {

        /**
         * Holds the values to be used in the fields callbacks
         */
        private $_options;

        /**
         * Holds all possible filters to be added
         */
        private $_filters;

        /**
         * Holds the number of form fields for each type
         */
        private $_count = 2;

        /**
         * Start up
         */
        public function __construct() {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
            $this->add_filters();
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
            $this->_options = get_option('gsy_content_filter_options');
            ?>
            <div class="wrap">
                <h2><?php _e('GSY Content Filter', 'gsy-content-filter'); ?></h2>           
                <form method="post" action="options.php" role="form">
                    <?php
                    // This prints out all hidden setting fields
                    settings_fields('gsy_content_filter_group');
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
                    'gsy_content_filter_group', // Option group
                    'gsy_content_filter_options', // Option name
                    array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                    'gsy_content_filter_section', // ID
                    'Settings', // Title
                    array($this, 'print_section_info'), // Callback
                    'gsy-content-filter' // Page
            );

            for ($i = 1; $i <= $this->_count; $i++) {
                add_settings_field(
                        'old_word_' . $i, // ID
                        'Old Word ' . $i, // Title 
                        array($this, 'old_word_callback'), // Callback
                        'gsy-content-filter', // Page
                        'gsy_content_filter_section', // Section
                        $i // Additional argument
                );

                add_settings_field(
                        'new_word_' . $i, // ID
                        'New Word ' . $i, // Title
                        array($this, 'new_word_callback'), // Callback
                        'gsy-content-filter', // Page 
                        'gsy_content_filter_section', // Section
                        $i // Additional argument
                );

                add_settings_field(
                        'filter_type_' . $i, // ID
                        'Filter Type ' . $i, // Title
                        array($this, 'filter_type_callback'), // Callback
                        'gsy-content-filter', // Page 
                        'gsy_content_filter_section', // Section
                        $i // Additional argument
                );
            }
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize($input) {
//            $sanitized_input = array();
//
//            if (isset($input['old_word'])) {
//                $sanitized_input['old_word'] = sanitize_text_field($input['old_word']);
//            }
//
//            if (isset($input['new_word'])) {
//                $sanitized_input['new_word'] = sanitize_text_field($input['new_word']);
//            }
//
//            if (isset($input['filter_type'])) {
//
//                if (array_key_exists($input['filter_type'], $this->_filters)) {
//                    $sanitized_input['filter_type'] = $input['filter_type'];
//                } else {
//                    $sanitized_input['filter_type'] = 'the_title'; // TO DO: get default value dynamically, 'the_title' is hard coded now
//                }
//            }
//
//            return $sanitized_input;
            
            
            return $input;
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
        public function old_word_callback() {
            $arg_list = func_get_args();
            $field_id = 'old_word_' . $arg_list[0];

            printf(
                    '<input type="text" id="%1$s" name="gsy_content_filter_options[%1$s]" value="%2$s" />', $field_id, isset($this->_options[$field_id]) ? esc_attr($this->_options[$field_id]) : ''
            );
        }

        /**
         * Get the settings option array and print one of its values
         */
        public function new_word_callback() {
            $arg_list = func_get_args();
            $field_id = 'new_word_' . $arg_list[0];

            printf(
                    '<input type="text" id="%1$s" name="gsy_content_filter_options[%1$s]" value="%2$s" />', $field_id, isset($this->_options[$field_id]) ? esc_attr($this->_options[$field_id]) : ''
            );
        }

        public function filter_type_callback() {
            $arg_list = func_get_args();
            $field_id = 'filter_type_' . $arg_list[0];

            $html = '<select name="gsy_content_filter_options[' . $field_id . ']" id="' . $field_id . '">';
            foreach ($this->_filters as $k => $v) {
                $selected = false;
                if ($k == $this->_options[$field_id]) {
                    $selected = true;
                }
                $html .= '<option ' . selected($selected, true, false) . ' value="' . esc_attr($k) . '">' . $v . '</option>';
            }
            $html .= '</select> ';

            echo $html;
        }

        public function do_filtering($content) {
            if (isset($this->_options['filter_type'])) {

                $old_word = $this->_options['old_word'];
                $new_word = $this->_options['new_word'];

                $content = str_ireplace($old_word, $new_word, $content);

                return $content;
            }
        }

        public function add_filters() {
            $this->_filters = array(
                'the_title' => 'title',
                'the_content' => 'content',
                'the_excerpt' => 'excerpt',
                'the_tags' => 'tags',
            );
            $this->_options = get_option('gsy_content_filter_options');

//            add_filter($this->_options['filter_type'], array($this, 'do_filtering'));
        }

    }

}