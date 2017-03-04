<?php
 
if(! class_exists('Instagram_Judo'))
{
    class Instagram_Judo {
 
        // general constants
        //
        CONST PLUGIN_SLUG   = 'instagram-judo';
 
        // setting constants
        CONST USER_ID      = 'ij_instagram_user_id';
        CONST ACCESS_TOKEN = 'ij_instagram_access_token';
        CONST IMAGE_COUNT  = 'ij_default_image_count';
 
        // shortcodes
        //
        CONST SHORTCODE = 'instagram-judo';
 
        // class vars
        //
        public $theme;
 
        function __construct()
        {
            add_action( 'admin_menu', array( $this, 'init_options_page' ) );
            add_action( 'admin_init', array($this, 'init_settings') );
            add_action( 'admin_enqueue_scripts', array($this, 'load_back_assets') );

            if( !is_admin() )
            {
                add_action( 'wp', array($this, 'load_front_assets') );
            }
 
            add_shortcode( Instagram_Judo::SHORTCODE, array($this, 'get_shortcode') );
        }

        function init_settings()
        {
            register_setting( 
                'general-settings',      // $option_group 
                'ij_instagram_user_id', // $option_name 
                'intval'               // $sanitize_callback 
            );

            register_setting( 
                'general-settings',          // $option_group 
                'ij_instagram_access_token', // $option_name 
                'sanitize_text_field'        // $sanitize_callback 
            );

            register_setting( 
                'general-settings',       // $option_group 
                'ij_default_image_count', // $option_name 
                'intval'                 // $sanitize_callback 
            );

            add_settings_section( 
                'ij_general_settings', // $id 
                'General Settings', // $title 
                array($this, 'render_section'), // $callback 
                'instagram_judo_settings' // $page 
            );


            add_settings_field( 
                Instagram_Judo::USER_ID, // $id 
                'Instagram User ID', // $title 
                array($this, 'render_input'), // $callback 
                'instagram_judo_settings', // $page 
                'ij_general_settings', // $section 
                array(
                    'label_for' => Instagram_Judo::USER_ID,
                    'type'      => 'text',
                    'id'        => Instagram_Judo::USER_ID,
                    'value'     => get_option(Instagram_Judo::USER_ID),
                    'after'    => '<p><em><a href="http://www.ershad7.com/InstagramUserID/" target="_blank">Find your User ID by Username.</a></em></p>'
                )
            );

            add_settings_field( 
                Instagram_Judo::ACCESS_TOKEN, // $id 
                'Instagram Access Token', // $title 
                array($this, 'render_input'), // $callback 
                'instagram_judo_settings', // $page 
                'ij_general_settings', // $section 
                array(
                    'label_for' => Instagram_Judo::ACCESS_TOKEN,
                    'type'      => 'text',
                    'id'        => Instagram_Judo::ACCESS_TOKEN,
                    'value'     => get_option(Instagram_Judo::ACCESS_TOKEN),
                    'after'    => '<p><em><a href="http://jelled.com/instagram/access-token" target="_blank">How to generate an access token.</a></em></p>'
                )
            );

            add_settings_field( 
                Instagram_Judo::IMAGE_COUNT, // $id 
                'Default Image Count', // $title 
                array($this, 'render_input'), // $callback 
                'instagram_judo_settings', // $page 
                'ij_general_settings', // $section 
                array(
                    'label_for' => Instagram_Judo::IMAGE_COUNT,
                    'type'      => 'number',
                    'id'        => Instagram_Judo::IMAGE_COUNT,
                    'value'     => get_option(Instagram_Judo::IMAGE_COUNT),
                    'after'    => '<p><em>This is the default number of images returned by the shortcode.</em></p>'                    
                )
            );                     
        }

        function render_input($args)
        {
            if( !array_key_exists('value', $args) ) $args['value'] = '';
            if( !array_key_exists('type', $args) ) $args['type'] = 'text';
            if( !array_key_exists('id', $args) ) $args['id'] = '';
            if( !array_key_exists('before', $args) ) $args['before'] = '';
            if( !array_key_exists('after', $args) ) $args['after'] = '';

            $template = '%s<input type="%s" id="%s" name="%s" value="%s" />%s';

            echo sprintf(
                $template, 
                $args['before'], 
                $args['type'], 
                $args['id'], 
                $args['id'], 
                $args['value'], 
                $args['after']
            );

        }

        function render_section()
        {
            echo 'Setup InstagramJudo settings here. See important information in the right column';
        }   

        function init_options_page()
        {
            add_options_page( 
                'Instagram Judo', //$page_title
                'Instagram Judo', // $menu_title 
                'manage_options', // $capability 
                'instagram_judo_settings', // $menu_slug 
                array($this, 'render_options_page')
            );  
        }

        function render_options_page()
        { ?>
            <div class="ij_row">
                <div class="ij_page-col ij_page-left">
                    <form method="POST" action="options.php">
                    <?php 
                        settings_fields( 'general-settings' );
                        do_settings_sections( 'instagram_judo_settings' );
                        submit_button();
                    ?>
                    </form>
                </div>
                <div class="ij_page-col ij_page-right">
                    <div class="ij_info-instructions--setup">
                        <h3>Setup Instructions</h3>
                        <ol>
                            <li>Sign in to Instagram and Setup your Instagram App via the <a href="https://www.instagram.com/developer/register/" target="_blank">Instagram Developer Portal</a>. Note: If you plan to only show the latest 20 images in your feed (and no more) then you can set just leave your app in Sandbox mode.</li>
                            <li>
                                <a href="http://www.ershad7.com/InstagramUserID/" target="_blank">Find your Instagram User ID using your Username.</a> Enter this value into the setting field on this page.
                            </li>
                            <li>
                                <a href="http://jelled.com/instagram/access-token" target="_blank">Generate an access token.</a> Enter this value into the setting field on this page.
                            </li>
                            <li>Specify the default number of images a shortcode should return (this can be overriden on each shortcode via attributes).</li>
                            <li>Add the shortcode where you wish it to appear (see Usage Instructions below).</li>
                        </ol>
                    </div>
                    <div class="ij_info-instructions--usage">
                        <h3>Usage Instructions</h3>
                        <p>Use the shortcode <code>[instagram-judo]</code> to display a feed of instagram images.</p>
                        <p>
                            <strong>Parameters:</strong><br>
                            <dl>
                                <dt>count</dt>
                                <dd>Specify the amount of images to return. Uses default image count value if blank.</dd>
                                <dt>theme</dt>
                                <dd>Default: <code>'default'</code> [string]. Specify which theme the shortcode's feed should use.
                                <br>
                                Available Options: <code>'default'</code>, <code>'none'</code>.
                                </dd>

                            </dl>
                        </p>
                    </div>
                    <div class="ij_info-instructions--important">
                        <h3>Important Information</h3>
                        <ol>
                            <li>If your Instagram App (which you had to setup to get your Access Token) is in Sandbox mode, you will be limited to 20 images displayed in a single feed.</li>
                        </ol>
                    </div>
                </div>
            </div>
        <?php }

 
        function load_front_assets()
        {
            global $post;
            $shortcode_found = ( has_shortcode($post->post_content, 'instagram-judo') ) ? true : false;

            if( $shortcode_found )
            {  
                wp_enqueue_style('ij-shortcode', WP_CONTENT_URL . '/plugins/' . Instagram_Judo::PLUGIN_SLUG . '/assets/css/ij-front.min.css'); 
                wp_enqueue_script('ij-instagram', WP_CONTENT_URL . '/plugins/' . Instagram_Judo::PLUGIN_SLUG . '/assets/js/instagram.min.js', array('jquery'), '0.3.1', true);
                wp_enqueue_script('ij-front', WP_CONTENT_URL . '/plugins/' . Instagram_Judo::PLUGIN_SLUG . '/assets/js/ij-front.js', array('jquery', 'ij-instagram', 'masonry'), '1.0', true);
            }
        }

        function load_back_assets($hook)
        {

            if( $hook == 'settings_page_instagram_judo_settings' )
            {
                wp_enqueue_style('ij-admin', WP_CONTENT_URL . '/plugins/' . Instagram_Judo::PLUGIN_SLUG . '/assets/css/ij-admin.min.css');
                wp_enqueue_script('ij-admin-scripts', WP_CONTENT_URL . '/plugins/' . Instagram_Judo::PLUGIN_SLUG . '/assets/js/ij-admin.min.js', array('jquery'));
            }
        }

        function get_shortcode($atts)
        {
            $options = shortcode_atts(array(
                'count'   => get_option(Instagram_Judo::IMAGE_COUNT),
                'theme'   => 'default',
                'columns' => 4
            ), $atts);
 
            $markup = '
                <div class="instagram-judo">
                    <input type="hidden" name="%s" value="%s" />
                    <input type="hidden" name="%s" value="%s" />
                    <input type="hidden" name="%s" value="%s" />
                    <input type="hidden" name="%s" value="%s" />
                    <input type="hidden" name="%s" value="%s" />
                    <div class="instagram-judo-feed clearfix" data-columns="%s">
                        <div class="feed-column-width"></div>
                    </div>
                    %s
                </div>
            ';

            $after = '';
            switch ($options['theme']) 
            {
                
                default:
                    wp_enqueue_script('masonry');
                    break;
            }

            $this->theme = $options['theme'];
 
            printf(
                $markup,
                Instagram_Judo::USER_ID,
                get_option(Instagram_Judo::USER_ID),
                Instagram_Judo::ACCESS_TOKEN,
                get_option(Instagram_Judo::ACCESS_TOKEN),
                Instagram_Judo::IMAGE_COUNT,
                $options['count'],
                'ij_sc_theme',
                $options['theme'],
                'ij_feed_columns',
                $options['columns'],
                $options['columns'],
                $after
            );
        }
    }
}