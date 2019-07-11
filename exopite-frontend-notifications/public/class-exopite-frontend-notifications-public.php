<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.joeszalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Frontend_Notifications
 * @subpackage Exopite_Frontend_Notifications/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Exopite_Frontend_Notifications
 * @subpackage Exopite_Frontend_Notifications/public
 * @author     Joe Szalai <contact@joeszalai.org>
 */
class Exopite_Frontend_Notifications_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Frontend_Notifications_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Frontend_Notifications_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'lobibox', plugin_dir_url( __FILE__ ) . 'css/lobibox.min.css', array(), '1.2.4', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exopite-frontend-notifications-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Frontend_Notifications_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Frontend_Notifications_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// http://lobianijs.com/site/lobibox
		wp_enqueue_script( 'lobibox', plugin_dir_url( __FILE__ ) . 'js/lobibox.js', array( 'jquery' ), '1.2.4', true );
		wp_enqueue_script( 'lobibox-messageboxes', plugin_dir_url( __FILE__ ) . 'js/messageboxes.js', array( 'jquery', 'lobibox' ), '1.2.4', true );
		wp_enqueue_script( 'lobibox-notifications', plugin_dir_url( __FILE__ ) . 'js/notifications.js', array( 'jquery', 'lobibox' ), '1.2.4', true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-frontend-notifications-public.js', array( 'jquery', 'lobibox', 'lobibox-messageboxes', 'lobibox-notifications' ), $this->version, true );

	}

}
