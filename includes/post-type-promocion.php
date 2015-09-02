<?php
/**
 * Promocion post type.
 *
 * @package WordPress
 * @subpackage Theme
 */

class Promocion_Post_Type{
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
        add_action( 'init', array( $this, 'post_type_promocion') );
        add_action( 'init', array( $this, 'post_type_promocion_taxonomies' ), 0 );
        add_action( 'contextual_help', array( $this, 'post_type_promocion_contextual_help' ), 10, 3 );

        add_filter( 'post_updated_messages', array( $this, 'post_type_promocion_updated_messages' ) );
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
     * Register a tipster post type
     */
    function post_type_promocion() {
        $labels = array(
            'name'                => _x( 'Promociones', 'post type general name', 'epic' ),
            'singular_name'       => _x( 'Promocion', 'post type singular name', 'epic' ),
            'menu_name'           => _x( 'Promocion', 'admin menu', 'epic' ),
            'name_admin_bar'      => _x( 'Promocion', 'add new on admin bar', 'epic' ),
            'parent_item_colon'   => __( 'Promocion padre:', 'epic' ),
            'all_items'           => __( 'Todas las promociones', 'epic' ),
            'view_item'           => __( 'Ver promocion', 'epic' ),
            'add_new_item'        => __( 'Agregar promocion', 'epic' ),
            'add_new'             => _x( 'Agregar nueva', 'promocion', 'epic' ),
            'edit_item'           => __( 'Editar promocion', 'epic' ),
            'update_item'         => __( 'Actualizar promocion', 'epic' ),
            'search_items'        => __( 'Buscar promociones', 'epic' ),
            'not_found'           => __( 'No se encontraron promociones', 'epic' ),
            'not_found_in_trash'  => __( 'No se encontraron promociones en la papelera', 'epic' ),
        );
        $args = array(
            'label'               => __( 'promocion', 'epic' ),
            'description'         => __( 'Gestiona las promociones y la informacion de las promociones', 'epic' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'thumbnail', ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-admin-generic',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );
        register_post_type( 'promocion', $args );
    }

    /**
     * Register a promocion post type taxonomies
     */
    function post_type_promocion_taxonomies() {
        $labels = array(
            'name'              => _x( 'Categorias de Promociones', 'taxonomy general name', 'epic' ),
            'singular_name'     => _x( 'Categoria de Promocion', 'taxonomy singular name', 'epic' ),
            'search_items'      => __( 'Buscar categorias', 'epic' ),
            'all_items'         => __( 'Todas las categorias', 'epic' ),
            'parent_item'       => __( 'Categoria padre', 'epic' ),
            'parent_item_colon' => __( 'Categoria padre:', 'epic' ),
            'edit_item'         => __( 'Editar categoria', 'epic' ),
            'update_item'       => __( 'Actualizar categoria', 'epic' ),
            'add_new_item'      => __( 'Agregar nueva', 'epic' ),
            'new_item_name'     => __( 'Agregar categoria', 'epic' ),
            'menu_name'         => __( 'Categorias de Promociones', 'epic' ),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
        );
        register_taxonomy( 'promociones', 'promocion', $args );
    }

    /**
     * Display contextual help for Promocion
     *
     * @param $contextual_help
     * @param $screen_id
     * @param $screen
     * @return string
     */
    function post_type_promocion_contextual_help( $contextual_help, $screen_id, $screen ) {
        if ( 'edit-promocion' == $screen->id ) {

            $contextual_help = '<h2>Promociones</h2>
        <p>Se muestran los detalles de los elementos que se muestran en la pagina de detalles de las promociones. Usted puede ver la lista de esos elementos en esta pagina y ordenarlos cronologicamente - el ultimo agregado es el primero.</p>
        <p>Usted puede ver/editar los detalles de cada promocion haciendo clic en su nombre, o puede aplicar acciones usando el menu de opciones y seleccionar multiples elementos.</p>';

        } elseif ( 'promocion' == $screen->id ) {

            $contextual_help = '<h2>Editar promocion</h2>
        <p>Esta pagina le permite ver/modificar los detalles de una promocion. Por favor asegurece de llenar los campos de las cajas disponibles (nombre, imagen, enlace, fecha).</p>';

        }
        return $contextual_help;
    }

    /**
     * Promocion update messages.
     *
     * @param $messages Existing post update messages.
     * @return array Amended post update messages with new CPT update messages.
     */
    function post_type_promocion_updated_messages( $messages ) {
        $post             = get_post();
        $post_type        = get_post_type( $post );
//        $post_type_object = get_post_type_object( $post_type );

        $messages['promocion'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf( __('Promocion actualizada. <a href="%s">Ver promocion</a>', 'epic'), esc_url( get_permalink($post->ID) ) ),
            2  => __( 'Campo personalizado actualizado.', 'epic' ),
            3  => __( 'Campo personalizado eliminado.', 'epic' ),
            4  => __( 'Promocion actualizada.', 'epic' ),
            5  => isset($_GET['revision']) ? sprintf( __('Promocion restaurada desde la revision from %s', 'epic'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( __('Promocion publicada. <a href="%s">Ver promocion</a>', 'epic'), esc_url( get_permalink($post->ID) ) ),
            7  => __( 'Promocion guardada.', 'epic' ),
            8  => sprintf( __('Promocion enviada. <a target="_blank" href="%s">Ver promocion</a>', 'epic'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
            9  => sprintf( __('Promocion planificada para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver promocion</a>', 'epic'), date_i18n( __( 'M j, Y @ G:i', 'epic' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ),
            10 => sprintf( __('Borrador de la promocion actualizada. <a target="_blank" href="%s">Ver promocion</a>', 'epic'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
        );

//        if ( $post_type_object->publicly_queryable ) {
//            $permalink = get_permalink( $post->ID );
//
//            $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View promocion', 'epic' ) );
//            $messages[ $post_type ][1] .= $view_link;
//            $messages[ $post_type ][6] .= $view_link;
//            $messages[ $post_type ][9] .= $view_link;
//
//            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
//            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview promocion', 'epic' ) );
//            $messages[ $post_type ][8]  .= $preview_link;
//            $messages[ $post_type ][10] .= $preview_link;
//        }

        return $messages;
    }
}