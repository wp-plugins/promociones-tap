<?php
/**
 * Represents the view for the plugin information page.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Promociones_TAP
 * @author    Alain Sanchez <luka.ghost@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.linkedin.com/in/mrbrazzi/
 * @copyright 2014 Alain Sanchez
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <p><?php printf(__('Este plugin permite visualizar las promociones/publicidad de los asociados de <a href="%s">todoapuestas.org</a>', Promociones_TAP::get_instance()->get_plugin_slug()),'http://www.todoapuestas.org')?></p>

</div>
