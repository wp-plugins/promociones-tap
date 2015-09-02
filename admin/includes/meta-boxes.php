<?php


/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Promociones_TAP
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */
class Promociones_TAP_Meta_Boxes_Post_Type {
    private $plugin_slug = null;

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {
        $plugin = Promociones_TAP::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        add_filter( 'cmb_meta_boxes', array( $this, 'post_type_promocion_metabox' ) );
        add_action( 'init', array( $this, 'cmb_initialize_cmb_meta_boxes' ), 9999 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
            return;
        } */

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Define the metabox and field configurations for post-type pick.
     *
     * @param  array $meta_boxes
     * @return array
     */
    function post_type_promocion_metabox( array $meta_boxes ) {
        global $post;
        // Start with an underscore to hide fields from custom fields list
        $prefix = '_promocion_';

        $meta_boxes['promocion_datos_adicionales'] = array(
            'id'         => 'promocion_datos_adicionales',
            'title'      => __( 'Datos adicionales', $this->plugin_slug ),
            'pages'      => array( 'promocion' ), // Tells CMB to use user_meta vs post_meta
            'show_names' => true,
            'cmb_styles' => true, // Show cmb bundled styles.. not needed on user profile page
            'fields'     => array(
                array(
                    'name'        => __('Ancho de la imagen', $this->plugin_slug),
                    'desc'        => __('Escribir el ancho de la imagen de la promocion.', $this->plugin_slug),
                    'id'          => $prefix . 'width',
                    'type'        => 'text_small',
                ),
                array(
                    'name'        => __('Alto de la imagen', $this->plugin_slug),
                    'desc'        => __('Escribir el alto de la imagen de la promocion.', $this->plugin_slug),
                    'id'          => $prefix . 'height',
                    'type'        => 'text_small',
                ),
                array(
                    'name'        => __('Fecha de finalizacion', $this->plugin_slug),
                    'desc'        => __('Seleccionar/escribir la fecha de finalizacion de la promocion.<br>Indicar utilizando el formato dd/mm/YYYY', $this->plugin_slug),
                    'id'          => $prefix . 'date_end',
                    'type'        => 'text_date_timestamp',
                    'date_format' => 'd/m/Y'
                ),
                array(
                    'name' => __('Enlace', $this->plugin_slug),
                    'desc' => __( 'Escribir la url asociada a la promocion', $this->plugin_slug ),
                    'id'   => $prefix . 'link',
                    'type' => 'text_url'
                ),
                array(
                    'name'    => __('Acceso al enlace', $this->plugin_slug),
                    'desc'    => __('Seleccione el metodo de acceso al enlace', $this->plugin_slug),
                    'id'      => $prefix . 'link_target',
                    'type'    => 'select',
                    'options' => array(
                        '_self'  => __('Ver en la misma pestana/ventana', $this->plugin_slug),
                        '_blank' => __('Ver en pestana/ventana nueva', $this->plugin_slug),
                    ),
                )

            )
        );

        return $meta_boxes;
    }

    /**
     * Initialize the metabox class.
     */
    function cmb_initialize_cmb_meta_boxes() {

        if ( ! class_exists( 'cmb_Meta_Box' ) )
            require_once dirname(__FILE__). '/cmb/init.php';

    }

    function enqueue_scripts(){
        $screen = get_current_screen();
        switch($screen->id){
            case "promocion":
                wp_enqueue_script( $this->plugin_slug . '-admin-metabox-script', plugins_url( 'assets/js/meta-boxes-promocion.js', dirname(__FILE__) ), array( 'jquery' ), Promociones_TAP::VERSION, true );
                break;
            default:
                break;
        }
    }
}








