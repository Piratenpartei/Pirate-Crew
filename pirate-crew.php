<?php
/*
Plugin Name: Pirate Crew
Plugin URI: http://github.com/Piratenpartei/Pirate-Crew
Description: Defines crew (people) list and cards for websites in pirate style 
Version: 1.0.2
Author: xwolf
Author URI: http://www.xwolf.de
License: GPL
*/

if (!defined('ABSPATH')) {
    exit;
}


if (!class_exists('Pirate_Crew')): 
/*-----------------------------------------------------------------------------------*/
/* Sets up main class
/*-----------------------------------------------------------------------------------*/
    class Pirate_Crew {
        private static $instance = null;
        private $text_domain = 'pirate-crew';
        private $settings;
        
        public static function get_instance() {
            if (null == self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct()
        {
            $this->settings = array(
                'plugin_path'       => plugin_dir_path(__FILE__),
                'plugin_url'        => plugin_dir_url(__FILE__),
                'plugin_base'       => dirname(plugin_basename(__FILE__)),
                'plugin_file'       => __FILE__,
                'plugin_version'    => '1.0.2',
                'text_domain'       => 'pirate-crew'
            );
            $this->pirate_crew_load_textdomain();
            $this->pirate_crew_start();
            $this->pirate_crew_backend();
        }

        /*--------------------------------------------------------------------*/
        /* Load Textdomain
        /*--------------------------------------------------------------------*/
        public function pirate_crew_load_textdomain() {
            load_plugin_textdomain($this->text_domain, false, $this->settings['plugin_base'] . '/language');
        }

        /*--------------------------------------------------------------------*/
        /* Main
        /*--------------------------------------------------------------------*/        
        public function pirate_crew_start() {
            add_action('init', array( $this, 'create_member_support' ));
            add_action('init', array( $this, 'pirate_crew_image_size' ));
            add_shortcode('crew', array( $this, 'pirate_crew_shortcodes' ));
            add_action('wp_enqueue_scripts', array( $this, 'embed_front_script_styles' ));
        }

        /*--------------------------------------------------------------------*/
        /* Define Image Size for crew member thumbnail
        /*--------------------------------------------------------------------*/
        public function pirate_crew_image_size(){
            if ( function_exists( 'add_image_size' ) ) {
                add_image_size('pirate_crew', 500, 500, true);
            }
        }
       /*--------------------------------------------------------------------*/
        /* Defines Shortcodes
        /*--------------------------------------------------------------------*/
        public function pirate_crew_shortcodes($atts) {
            extract(shortcode_atts(array(
                'id' => false
            ), $atts));
            $options = $this->get_options('pirate_crew', $id);
            if (!$options) {
                return '<div class="pirate-crew-error">' . __('Crew not found', $this->text_domain) . '</div>';
            }
            if (empty($options['memberlist'])) {
                return '<div class="pirate-crew-error">' . __('No members found', $this->text_domain) . '</div>';
            }
            $template = $this->settings['plugin_path'] . 'templates/' . $options['team-style'] . '.php';
            if (file_exists($template)) {
                ob_start();
                $teamargs = array(
                    'orderby' => 'post__in',
                    'post_type' => 'pirate_crew_member',
                    'post__in' => $options['memberlist'],
                    'posts_per_page' => -1 ,
                );
                $team     = new WP_Query($teamargs);
                include $template;
                wp_reset_postdata();
                return ob_get_clean();
            }
        }
        /**
         * Register front scripts
         *  @since    1.0.0
         */
        public function embed_front_script_styles() {
            wp_enqueue_script('pirate-crew', plugins_url('js/team.min.js', $this->settings['plugin_file']), array('jquery'), $this->settings['plugin_version'], true); 
            wp_enqueue_style('pirate-crew', plugins_url('css/team.min.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
        }
        /**
         *  Create custom post type
         *  @since    1.0.0
         */
        public function create_member_support() {
            // Create pirate_crew_member post type
            if (post_type_exists("pirate_crew_member")) {
                return;
            }
            $singular =  __('Pirate Crew Member', $this->text_domain);
            $plural =  __('Pirate Crews Members', $this->text_domain);
            $labels = array(
                'name'              =>  __('Pirate Crew', $this->text_domain),
                'singular_name'     => __('Crew Member', $this->text_domain),
                'menu_name'         => __('Pirate Crew', $this->text_domain),
                'add_new'           => __('Add New Member', $this->text_domain),
                'add_new_item'      => sprintf(__('Add %s', $this->text_domain), $singular),
                'new_item'          => sprintf(__('New %s', $this->text_domain), $singular),
                'edit_item'         => sprintf(__('Edit %s', $this->text_domain), $singular),
                'view_item'         => sprintf(__('View %s', $this->text_domain), $singular),
                'all_items'         => sprintf(__('Members', $this->text_domain)),
                'search_items'      => sprintf(__('Search %s', $this->text_domain), $plural),
                'not_found'         => sprintf(__('No %s found', $this->text_domain), $plural),
                'not_found_in_trash' => sprintf(__('No %s found in trash', $this->text_domain), $plural)
            );
            $cp_args = array(
                'labels'        => $labels,
                'description'   => sprintf(__('This is where you can create and manage %s.', $this->text_domain), __('Crew Members', $this->text_domain)),
                'publicly_queryable' => false,
                'show_ui'        => true,
                'show_in_menu'  => true,
                'capability_type' => 'post',
                'supports'  => array('title','editor', 'thumbnail' ),
                'menu_icon' => 'dashicons-admin-users'
            );
            register_post_type('pirate_crew_member', $cp_args);
            if (post_type_exists("pirate_crew")) {
                return;
            }
            $singular =  __('Group', $this->text_domain);
            $plural =  __('Groups', $this->text_domain);
            $labels = array(
                'name'              => __('Pirate Crew Group', $this->text_domain),
                'singular_name'     => __('Pirate Crew Group', $this->text_domain),
                'menu_name'         => __('Pirate Crew Group', $this->text_domain),
                'add_new'           => __('Add Group', $this->text_domain),
                'add_new_item'      => sprintf(__('Add %s', $this->text_domain), $singular),
                'new_item'          => sprintf(__('New %s', $this->text_domain), $singular),
                'edit_item'         => sprintf(__('Edit %s', $this->text_domain), $singular),
                'view_item'         => sprintf(__('View %s', $this->text_domain), $singular),
                'all_items'         => sprintf(__('Groups', $this->text_domain)),
                'search_items'      => sprintf(__('Search %s', $this->text_domain), $plural),
                'not_found'         => sprintf(__('No %s found', $this->text_domain), $plural),
                'not_found_in_trash' => sprintf(__('No %s found in trash', $this->text_domain), $plural)
            );
            $cp_args = array(
                'labels'            => $labels,
                'description'       => sprintf(__('This is where you can create and manage %s.', $this->text_domain), __('Crew', $this->text_domain)),
                'show_ui'           => true,
                "show_in_menu"      => 'edit.php?post_type=pirate_crew_member',
                'capability_type'   => 'post',
                'supports'          => array('title')
            );
            register_post_type('pirate_crew', $cp_args);
        }
        /**
         * Initiate admin functions.
         * @since 1.0
         */
        public function pirate_crew_backend() {
            if (is_admin()) {
                add_action('add_meta_boxes', array( $this, 'register_metaboxes' ));
                add_action('save_post', array( $this, 'save_metabox_data' ), 10, 3);
                add_action('admin_init', array( $this, 'meta_box_scripts' ));
                add_action('admin_menu', array( $this, 'add_submenu_items' ), 12);
                add_action('edit_form_after_title', array( $this, 'shortcode_preview' ));
                add_filter('manage_pirate_crew_member_posts_columns' , array( $this, 'custom_columns_member' ));
                add_action('manage_pirate_crew_member_posts_custom_column' , array( $this, 'custom_columns_member_data' ) , 10, 2 );
                add_filter('manage_pirate_crew_posts_columns' , array( $this, 'custom_columns_team' ));
                add_action('manage_pirate_crew_posts_custom_column' , array( $this, 'custom_columns_team_data' ) , 10, 2 );  
                add_filter('admin_post_thumbnail_size',  array($this,'custom_admin_thumb_size'));
            }
        }
        /**
         * Custom thumbnail size for pirate_crew_member
         * @since 1.0
         */
        function custom_admin_thumb_size($thumb_size){
            global $post_type,$post;
            if($post_type == 'pirate_crew_member'){
                $thumb_size = "pirate_crew";
            }
            return $thumb_size; 
        }
       
        /**
         * Custom column on member table.
         * @since 1.0
         */
        function custom_columns_member($columns){
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __('Name',$this->text_domain),
                'featured_image' => __('Photo',$this->text_domain),
                'designation' => __('Designation',$this->text_domain),
                'date' => 'Date'
             );
            return $columns;
        }
        /**
         * Custom member table data.
         * @since 1.0
         */
        function custom_columns_member_data($column,$post_ID){
            $options = $this->get_options('pirate_crew_member',$post_ID );
            switch ( $column ) {
            case 'featured_image':
                echo the_post_thumbnail( 'thumbnail' );
                break;
            case 'designation':
                echo $options['pirate-crew-designation'];
                break;
            }
        }
        /**
         * Custom member column for team.
         * @since 1.0
         */
        function custom_columns_team($columns){
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __('Name',$this->text_domain),
                'members' => __('Members',$this->text_domain),
                'preset' => __('Preset',$this->text_domain),
                'style' => __('Style',$this->text_domain),
                'shortcode' =>__('Shortcode',$this->text_domain)
             );
            return $columns;
        }
        /**
         * Custom member column data for team.
         * @since 1.0
         */
        function custom_columns_team_data($column,$post_ID){
            $options = $this->get_options('pirate_crew',$post_ID );
            $post = get_post( $post_ID );
            switch ( $column ) {
            case 'members':
                echo count($options['memberlist']);
                break;
            case 'preset':
                echo $options['team-style'];
                break;
            case 'style':
                echo $options['preset'];
                break;
            case 'shortcode':
                printf('<code>[crew id="%s"]</code>',$post_ID);
                break;
            }
        }
        /**
         * Shortcode preview on team edit page
         * @since 1.0
         */
        public function shortcode_preview($post) {
            if ('pirate_crew' == $post->post_type && 'publish' == $post->post_status) {
                printf('<p>%1$s: <code>[crew id="%2$s"]</code><button id="copy-picrew" type="button" data-clipboard-text="[crew id=&quot;%2$s&quot;]" class="button">%3$s</button></p>', __("Shortcode", $this->text_domain), $post->ID, __("Copy", $this->text_domain));
            }
            return;
        }
        /**
         * Loads meta box helper scripts
         * since 1.0
         */
        public function meta_box_scripts() {
            global $pagenow, $typenow, $post;
            if (empty($typenow) && !empty($_GET['post'])) {
                $post    = get_post($_GET['post']);
                $typenow = $post->post_type;
            }
            if (($pagenow == 'post-new.php' or $pagenow == 'post.php') and ($typenow == 'pirate_crew_member' or $typenow == 'pirate_crew')) {
                wp_enqueue_style('pirate-crew-admin', plugins_url('css/admin.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
                wp_enqueue_script('team-meta-box', plugins_url('js/team-admin.js', $this->settings['plugin_file']), array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), $this->settings['plugin_version']);
                wp_enqueue_script('select2', plugins_url('js/select2.min.js', $this->settings['plugin_file']), array( 'jquery' ), $this->settings['plugin_version']);
                wp_enqueue_style('select2', plugins_url('css/select2.min.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
                wp_enqueue_style('pirate-crew-icomoon-css', plugins_url('css/icomoon.css', $this->settings['plugin_file']), false, $this->settings['plugin_version'], 'all');
            }
            
        }
        /**
         * Adding submenu items
         *  @since    1.0.0
         */
        public function add_submenu_items() {
            add_submenu_page('edit.php?post_type=pirate_crew_member', __('Add Group', $this->text_domain), __('Add Team', $this->text_domain), 'manage_options', 'post-new.php?post_type=pirate_crew');
        }
        /**
         * Register meta box
         *  @since    1.0.0
         */
        public function register_metaboxes()  {
            add_meta_box('member_details', __('Member Details', $this->text_domain), array( $this, 'member_details_meta' ), 'pirate_crew_member');
            add_meta_box('team_details', __('Group Details', $this->text_domain), array( $this, 'team_details_meta' ), 'pirate_crew', 'normal', 'high');
        }
       
        /**
         * Meta box display callback - Member details.
         * @since    1.0.0
         * @param WP_Post $post Current post object.
         */
        public function member_details_meta($post)
        {
            wp_nonce_field(basename(__FILE__), 'pirate_crew_meta_details');
            $pirate_crew_contact = get_post_meta($post->ID, 'pirate_crew_contact', true);
            $pirate_crew_social  = get_post_meta($post->ID, 'pirate_crew_social', true);
            $socialicons  = array('mail', 'link', 
                'google-plus', 'google-plus2',
                'hangouts', 'google-drive', 'facebook', 
                'facebook2', 'instagram', 'whatsapp', 
                'twitter', 'youtube', 'vimeo', 'vimeo2', 
                'flickr', 'flickr2', 'dribbble', 'behance',
                'behance2', 'dropbox', 'wordpress', 'blogger', 
                'tumblr', 'tumblr2', 'skype', 'linkedin2', 
                'linkedin', 'stackoverflow', 'pinterest2', 'pinterest', 'foursquare',
                'github', 'flattr', 'xing', 'xing2', 'stumbleupon', 'stumbleupon2',
                'delicious', 'lastfm', 'lastfm2', 'hackernews', 'reddit', 'soundcloud', 
                'soundcloud2', 'yahoo', 'blogger2', 'ello', 'wordpress2', 'steam', 'steam2',
                '500px', 'deviantart', 'twitch', 'feed', 'feed2', 'sina-weibo', 'renren', 
                'vk', 'vine', 'telegram', 'spotify', 'mail2', 'mail3');
            include $this->settings['plugin_path'] . 'includes/member-details.php';
        }
        /**
         * Meta box display callback - Team details.
         * @since    1.0.0
         * @param WP_Post $post Current post object.
         */
        public function team_details_meta($post)
        {
            wp_nonce_field(basename(__FILE__), 'pirate_crew_meta_details');
            $args         = array(
                'post_type' => 'pirate_crew_member',
                'posts_per_page' => -1
            );
            $members      = new WP_Query($args);
            $options      = $this->get_options('pirate_crew', $post->ID);
            $defaultimage = $this->settings['plugin_url'] . 'images/default-user.png';
            include $this->settings['plugin_path'] . 'includes/team-details.php';
        }
        /** 
         * Save metabox
         * @param  Int $post_id id of the post
         * @param  Object $post Post Object
         * @since 1.0
         */
        public function save_metabox_data($post_id, $post) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (!isset($_POST['pirate_crew_meta_details']) || !wp_verify_nonce($_POST['pirate_crew_meta_details'], basename(__FILE__))) {
                return $post_id;
            }
            $post_type = get_post_type_object($post->post_type);
            if (!current_user_can($post_type->cap->edit_post, $post_id)) {
                return $post_id;
            }
            if ($post->post_type == 'pirate_crew_member') {
                $team_repeater = array(
                    'pirate_crew_contact' => array(
                        'label' => 'pirate-crew-label',
                        'content' => 'pirate-crew-content'
                    ),
                    'pirate_crew_social' => array(
                        'icon' => 'pirate-crew-icon',
                        'link' => 'pirate-crew-link'
                    )
                );
                $team_meta = array(
                    'pirate-crew-designation',
                    'pirate-crew-short-desc'
                );
                foreach ($team_repeater as $key => $value) {
                    $olddata = get_post_meta($post_id, $key, true);
                    $newdata = $item = array();
                    foreach ($value as $k => $v) {
                        $item[$k] = $_POST[$v];
                    }
                    $count = count(reset($item));
                    for ($i = 0; $i < $count; $i++) {
                        foreach ($value as $k => $v) {
                            if ($item[$k][$i] != '') {
                                $newdata[$i][$k] = stripslashes(strip_tags($item[$k][$i]));
                            }
                        }
                    }
                    if (!empty($newdata) && $newdata != $olddata) {
                        update_post_meta($post_id, $key, $newdata);
                    } elseif (empty($newdata) && $olddata) {
                        delete_post_meta($post_id, $key, $olddata);
                    }
                    
                }
            } elseif ($post->post_type == 'pirate_crew') {
                $team_meta = array('memberlist', 'team-style', 'preset', 'columns');
            }
            foreach ($team_meta as $meta_key) {
                $olddata = get_post_meta($post_id, $meta_key, true);
                $newdata = array();
                if (isset($_POST[$meta_key])) {
                    if (is_array($_POST[$meta_key])) {
                        $newdata = $_POST[$meta_key];
                    } else {
                        $newdata = stripslashes(strip_tags($_POST[$meta_key]));
                    }
                    if (!empty($newdata) && $newdata != $olddata) {
                        update_post_meta($post_id, $meta_key, $newdata);
                    } elseif (empty($newdata) && $olddata) {
                        delete_post_meta($post_id, $meta_key, $olddata);
                    }
                } else {
                    delete_post_meta($post_id, $meta_key, $olddata);
                }
            }
        }
        /**
         * Dropdown Builder
         * @since   1.0
         */
        public function selectbuilder($name, $options, $selected = "", $selecttext = "", $class = "", $optionvalue = 'value')
        {
            if (is_array($options)):
                $select_html = "<select name=\"$name\" id=\"$name\" class=\"$class\">";
                if ($selecttext) {
                    $select_html .= '<option value="">' . $selecttext . '</option>';
                }
                foreach ($options as $key => $option) {
                    if ($optionvalue == 'value') {
                        $value = $option;
                    } else {
                        $value = $key;
                    }
                    $select_html .= "<option value=\"$value\"";
                    if ($value == $selected) {
                        $select_html .= ' selected="selected"';
                    }
                    $select_html .= ">$option</option>\n";
                }
                $select_html .= '</select>';
                echo $select_html;
            else:
            endif;
        }
        /**
         * Get options
         * @param  String $postype Post type slug
         * @param  Int $post_id ID of post
         * @since   1.0
         */
        public function get_options($postype, $post_id){
            $post = get_post($post_id);
            
            if (!$post) {
                return false;
            }
            
            $metakeys['pirate_crew_member'] = array(
                'pirate_crew_contact',
                'pirate_crew_social',
                'pirate-crew-designation',
                'pirate-crew-short-desc'
            );
            $metakeys['pirate_crew']        = array(
                'memberlist',
                'team-style',
                'preset',
                'columns',
            );
            $options['pirate_crew_member']  = array(
                'pirate_crew_contact' => array(),
                'pirate_crew_social' => array(),
                'pirate-crew-designation' => '',
                'pirate-crew-short-desc' => ''
            );
            $options['pirate_crew']         = array(
                'memberlist' => array(),
                'team-style' => 'cards',
                'preset' => '',
                'columns' => '',
            );
            foreach ($metakeys[$postype] as $key => $value) {
                $metavalue = get_post_meta($post_id, $value, true);
                if ($metavalue) {
                    $options[$postype][$value] = $metavalue;
                }
            }
            return $options[$postype];
        }
        /**
         * Get team thumbnail
         * @param  Int $team_id  Post id of tram
         * @param  string $thumbnail thumbnail size
         * @since   1.0
         */
        public function pirate_team_get_thumbnail($team_id, $thumbnail = "pirate_crew")
        {
            $defaultimage = $this->settings['plugin_url'] . 'images/default-member.jpg';
            $member_image = get_post_thumbnail_id($team_id);
            if ($member_image) {
                $member_image_url = wp_get_attachment_image_src($member_image, $thumbnail, true);
                $member_image_url = $member_image_url[0];
            } else {
                $member_image_url = $defaultimage;
            }
            return $member_image_url;
        }
        /**
         * Item stle generator
         * @since   1.0
         */
        public function item_style($options, $custom = "")
        {
            $style = array(
                $options['team-style'] . '-style',
                $options['preset'],
                'grid-' . $options['columns'] . '-col',
                $custom
            );
            return implode(' ', $style);
        }
        /**
         * Class generator
         * @param  Array $class classnames
         * @since   1.0
         */
        public function addclass($class)
        {
            return implode(' ', $class);
        }
        /**
         * ID generator
         * @param  Array $id 
         * @since   1.0
         */
        public function add_id($id)
        {
            return implode('-', $id);
        }
        /**
         * Print the meta data after checking it's existence
         * @since   1.0
         */
        public function checkprint($template, $value, $return = false)
        {
            if ($value) {
                if ($return) {
                    return sprintf($template, $value);
                } else {
                    echo sprintf($template, $value);
                }
                
            }
        }
    }
function pirate_crew_activation(){   
    Pirate_Crew::get_instance();
}

// Plugin activation hook
add_action( 'plugins_loaded', 'pirate_crew_activation' );
endif;  