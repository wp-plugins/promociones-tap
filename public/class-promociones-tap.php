<?php
/**
 * Plugin Name.
 *
 * @package   Promociones_TAP
 * @author    Alain Sanchez <luka.ghost@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.linkedin.com/in/mrbrazzi/
 * @copyright 2014 Alain Sanchez
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-promociones-tap-admin.php`
 *
 * @package Promociones_TAP
 * @author  Your Name <email@example.com>
 */
class Promociones_TAP {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "promociones-tap" to the name of your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'promociones-tap';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		/* Define custom functionality.
         * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         *
         * add_action ( 'hook_name', 'your_function_name', [priority], [accepted_args] );
         *
         * add_filter ( 'hook_name', 'your_filter', [priority], [accepted_args] );
         */
//        if((is_front_page() && is_home()) || (is_front_page()) ){
            // Load public-facing style sheet and JavaScript.
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_footer', array($this, 'enqueue_scripts_footer'), 30 );
//        }
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

					restore_current_blog();

				}

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-modal', plugins_url( 'assets/css/modal.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_slug . '-jquery.cookie', plugins_url( 'assets/js/jquery.cookie.js', __FILE__ ), array( 'jquery' ), '1.4.1', true );
        wp_enqueue_script( $this->plugin_slug . '-modal', plugins_url( 'assets/js/modal.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
	}

	/**
     * Add scripts to footer
     *
     * @since     1.0.0
     */
    public function enqueue_scripts_footer() {
        if((is_front_page() && is_home()) || (is_front_page()) ):
            global $wpdb;

            $tzstring = cmb_Meta_Box::timezone_string();
            $offset = cmb_Meta_Box::timezone_offset( $tzstring, true );
            if ( substr( $tzstring, 0, 3 ) === 'UTC' )
                $tzstring = timezone_name_from_abbr( '', $offset, 0 );
            date_default_timezone_set($tzstring);

            $query_post_type_promocion = "SELECT p.* FROM $wpdb->posts AS p".
                " INNER JOIN $wpdb->postmeta pm ON  p.ID = pm.post_id".
                " WHERE p.post_type = 'promocion'".
                " AND pm.meta_key = '_promocion_date_end'".
                " AND pm.meta_value > ".strtotime("now").
                " ORDER BY p.post_date DESC".
                " LIMIT 1";
            $post_type_promocion = $wpdb->get_results($query_post_type_promocion, OBJECT);

            if($post_type_promocion):
                global $post;
                foreach($post_type_promocion as $post):
                    setup_postdata($post); ?>
                    <div class="modal fade promocionModal" id="promocion-<?php the_ID(); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
                                    <?php the_title('<h4 class="modal-title">', '</h4>'); ?>
                                </div>
                                <div class="modal-body">
                                    <p class="text-center"><?php
                                        $options = Promociones_TAP_Options_Framework::get_instance();

                                        $width = get_post_meta($post->ID, '_promocion_width', true);
                                        $width = empty($width) ? $options->of_get_option('promocion_tap_image_width'): $width;
                                        $height = get_post_meta($post->ID, '_promocion_height', true);
                                        $height = empty($height) ? $options->of_get_option('promocion_tap_image_height') : $height;
                                        if(!has_image_size('promocion-'.$width.'x'.$height)){
                                            add_image_size('promocion-'.$width.'x'.$height, $width, $height);
                                        }

                                        $url = get_post_meta($post->ID, '_promocion_link', true);
                                        $target = get_post_meta($post->ID, '_promocion_link_target', true); ?>
                                        <a href="<?php echo esc_url($url)?>" title="<?php echo $post->post_title; ?>" target="<?php echo $target; ?>">
                                            <?php if(has_post_thumbnail($post->ID)):?>
                                                <?php the_post_thumbnail('promocion-'.$width.'x'.$height, array('alt'=>$post->post_title, 'title'=>$post->post_title, 'class' => 'img-responsive'));?>
                                            <?php else:?>
                                                <img src="<?php echo $options->of_get_option('promocion_tap_image_default', plugins_url( 'assets/img/promocion.jpg', __FILE__ ))?>" class="img-responsive" alt="<?php echo $post->post_title; ?>" title="<?php echo $post->post_title; ?>" style="width: <?php echo $width ?>px; height: <?php echo $height ?>px;">
                                            <?php endif;?>
                                        </a>
                                    </p>
                                    <?php the_content(); ?>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn default" type="button">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div> <?php
                endforeach;
            endif; ?>

            <script type="text/javascript">
                var TAP_Promociones = function(){
                    return {
                        init: function(){
                            function create_cookie(cookie_name){
                                jQuery.cookie(cookie_name, 1, { expires: 5, path: '/' });
                            }

                            var cookie = '_tap_promocion';
                            var cookie_value = jQuery.cookie(cookie);
                            if(cookie_value == null || !cookie_value){
                                jQuery('.promocionModal').modal();
                                create_cookie(cookie);
                            }
                        }
                    }
                }();

                (function ( $ ) {
                    "use strict";

                    $(function () {

                        TAP_Promociones.init();

                    });

                }(jQuery));
            </script>
<?php   endif;
    }
}
