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
        private $_filters = array(
            'the_title' => 'title',
            'the_content' => 'content',
        );

        /**
         * Holds the number of form fields for each type
         */
        private $_count = 2;

        /**
         * Start up
         */
        public function __construct() {
            add_action('admin_enqueue_scripts', array($this, 'gsy_content_filter_add_styles'));
            add_action('admin_enqueue_scripts', array($this, 'gsy_content_filter_add_scripts'));
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
            $this->add_filters();
        }

        /**
         * Adding styles for admin page
         */
        public function gsy_content_filter_add_styles() {
            $style_src = plugins_url('../css/style.css', __FILE__);
            wp_enqueue_style('gsy-content-filter-style', $style_src);
        }

        /**
         * Adding scripts for admin page
         */
        public function gsy_content_filter_add_scripts() {
            $script_src = plugins_url('../js/script.js', __FILE__);
            wp_enqueue_script('gsy-content-filter-script', $script_src);
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
            <div id="gsy-content-filter" class="wrap">
                <h2><?php _e('GSY Content Filter', 'gsy-content-filter'); ?></h2>           
                <form method="post" action="options.php" role="form">
                    <?php
                    // This prints out all hidden setting fields
                    settings_fields('gsy_content_filter_group');
                    do_settings_sections('gsy-content-filter');
                    ?>
                    <p>
                        <button class="add-filter">add</button>
                        <button class="remove-all-filters">remove all</button>
                    </p>
                    <?php
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
                    'Filters', // Title
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
            
        }

        /**
         * Get the settings option array and print one of its values
         */
        public function old_word_callback() {
            $arg_list = func_get_args();
            $field_id = 'old_word_' . $arg_list[0];

            printf(
                    '<input type="text" class="old-word" disabled="disabled" id="%1$s" name="gsy_content_filter_options[%1$s]" value="%2$s" />', $field_id, isset($this->_options[$field_id]) ? esc_attr($this->_options[$field_id]) : ''
            );
        }

        /**
         * Get the settings option array and print one of its values
         */
        public function new_word_callback() {
            $arg_list = func_get_args();
            $field_id = 'new_word_' . $arg_list[0];

            printf(
                    '<input type="text" class="new-word" disabled="disabled" id="%1$s" name="gsy_content_filter_options[%1$s]" value="%2$s" />', $field_id, isset($this->_options[$field_id]) ? esc_attr($this->_options[$field_id]) : ''
            );
        }

        public function filter_type_callback() {
            $arg_list = func_get_args();
            $field_id = 'filter_type_' . $arg_list[0];

            $html = '<select class="filter-type" name="gsy_content_filter_options[' . $field_id . '][]" id="' . $field_id . '" disabled="disabled" multiple="multiple">';
            foreach ($this->_filters as $k => $v) {
                $selected = false;

                if (isset($this->_options[$field_id]) && in_array($k, $this->_options[$field_id])) {
                    $selected = true;
                }
                $html .= '<option ' . selected($selected, true, false) . ' value="' . $k . '">' . $v . '</option>';
            }
            $html .= '</select> ';

            echo $html;
        }

        public function add_filters() {
            $this->_options = get_option('gsy_content_filter_options');

            add_filter('the_title', array($this, 'the_title_callback'));
            add_filter('the_content', array($this, 'the_content_callback'));
        }

        public function the_title_callback($content) {
            for ($i = 1; $i <= $this->_count; $i++) {
                if (!empty($this->_options['old_word_' . $i]) && in_array('the_title', $this->_options['filter_type_' . $i])) {
                    $content = str_ireplace($this->_options['old_word_' . $i], $this->_options['new_word_' . $i], $content);
                }
            }

            return $content;
        }

        public function the_content_callback($content) {
            for ($i = 1; $i <= $this->_count; $i++) {
                if (!empty($this->_options['old_word_' . $i]) && in_array('the_content', $this->_options['filter_type_' . $i])) {
                    $content = str_ireplace($this->_options['old_word_' . $i], $this->_options['new_word_' . $i], $content);
                }
            }

            return $content;
        }

    }

}