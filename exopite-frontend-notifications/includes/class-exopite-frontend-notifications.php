<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.joeszalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Frontend_Notifications
 * @subpackage Exopite_Frontend_Notifications/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Exopite_Frontend_Notifications
 * @subpackage Exopite_Frontend_Notifications/includes
 * @author     Joe Szalai <contact@joeszalai.org>
 */
class Exopite_Frontend_Notifications {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Exopite_Frontend_Notifications_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'EXOPITE_FRONTEND_NOTIFICATIONS_VERSION' ) ) {
			$this->version = EXOPITE_FRONTEND_NOTIFICATIONS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'exopite-frontend-notifications';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Exopite_Frontend_Notifications_Loader. Orchestrates the hooks of the plugin.
	 * - Exopite_Frontend_Notifications_i18n. Defines internationalization functionality.
	 * - Exopite_Frontend_Notifications_Admin. Defines all hooks for the admin area.
	 * - Exopite_Frontend_Notifications_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-exopite-frontend-notifications-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-exopite-frontend-notifications-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-exopite-frontend-notifications-public.php';

		$this->loader = new Exopite_Frontend_Notifications_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Exopite_Frontend_Notifications_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Exopite_Frontend_Notifications_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Exopite_Frontend_Notifications_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		/**
		 * The wp_ajax_ is telling wordpress to use ajax and the prefix_ajax_first is the hook name to use in JavaScript or in URL.
		 *
		 * Call AJAX function via URL: https://www.yourwebsite.com/wp-admin/admin-ajax.php?action=ajax_first&post_id=23&other_param=something
		 * http://www.test1.localhost/wp-admin/admin-ajax.php?action=efn_get_notifications
		 *
		 * The ajax_first is the callback function.
		 * wp_ajax_ is for authenticated users
		 * wp_ajax_nopriv_ is for NOT authenticated users
		 */
		$this->loader->add_action( 'wp_ajax_efn_get_notifications', $plugin_public, 'ajax_get_notifications' );
		$this->loader->add_action( 'wp_ajax_nopriv_efn_get_notifications', $plugin_public, 'ajax_get_notifications' );

        $this->loader->add_action( 'wp_ajax_efn_dismiss_notifications', $plugin_public, 'ajax_dismiss_notifications' );

        /**
         * @link https://webkul.com/blog/wordpress-add-custom-links-plugin-page/
         */
        $this->loader->add_filter( 'plugin_row_meta', $plugin_public, 'plugin_row_meta', 10, 2 );

        /**
         * Demonstrate a test callback to e.g. remove AJAX notification on user click.
         */
		// $this->loader->add_action( 'wp_ajax_efn_test_callback', $plugin_public, 'test_callback' );
		// $this->loader->add_action( 'wp_ajax_nopriv_efn_test_callback', $plugin_public, 'test_callback' );

		$this->loader->add_action( 'wp_footer', $this->public, 'display_messages_in_wp_footer', 10 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Exopite_Frontend_Notifications_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
