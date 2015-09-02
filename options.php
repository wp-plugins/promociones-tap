<?php

class Promociones_TAP_Options{
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
        add_action('optionsframework_custom_scripts', array($this, 'optionsframework_custom_scripts'));
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
     * A unique identifier is defined to store the options in the database and reference them from the theme.
     * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
     * If the identifier changes, it'll appear as if the options have been reset.
     */
    function optionsframework_option_name() {

        // This gets the theme name from the stylesheet
        $plugin_name = Promociones_TAP_Options_Framework::get_instance()->get_plugin_slug();

        $optionsframework_settings = get_option( 'promociones_tap_options_framework' );
        $optionsframework_settings['id'] = $plugin_name;
        update_option( 'promociones_tap_options_framework', $optionsframework_settings );
    }

    /**
     * Defines an array of options that will be used to generate the settings page and be saved in the database.
     * When creating the 'id' fields, make sure to use all lowercase and no spaces.
     *
     * If you are making your theme translatable, you should replace 'options_framework_theme'
     * with the actual text domain for your theme.  Read more:
     * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
     */
    function optionsframework_options() {

        $options = array();

        $options[] = array(
            'name' => __('Configuraciones', $this->plugin_slug),
            'type' => 'heading');

        $options[] = array(
            'name' => __('Imagen por defecto', $this->plugin_slug),
            'desc' => __('Seleccionar la imagen a utilizar por defecto para las promociones que no se les haya definido.', $this->plugin_slug),
            'id' => 'promocion_tap_image_default',
            'std' => plugins_url( 'assets/banner-772x250.png', __FILE__ ),
            'type' => 'upload'
        );

        $options[] = array(
            'name' => __('Ancho  de las imagenes', $this->plugin_slug),
            'desc' => __('Escribir el ancho por defecto para las imagenes de las promociones.', $this->plugin_slug),
            'id' => 'promocion_tap_image_width',
            'std' => '772',
            'class' => 'mini',
            'type' => 'text'
        );

        $options[] = array(
            'name' => __('Alto de las imagenes', $this->plugin_slug),
            'desc' => __('Escribir el alto por defecto para las imagenes de las promociones.', $this->plugin_slug),
            'id' => 'promocion_tap_image_height',
            'std' => '250',
            'class' => 'mini',
            'type' => 'text'
        );


        return $options;
    }

    /**
     * Custom scripts.
     */
    function optionsframework_custom_scripts() { ?>

        <script type="text/javascript">
            (function ( $ ) {
                "use strict";

                $(function () {

                });

            }(jQuery));
        </script>

    <?php
    }
}

