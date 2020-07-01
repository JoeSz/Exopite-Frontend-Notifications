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

        wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), '4.7', 'all' );
		wp_enqueue_style( 'animate', plugin_dir_url( __FILE__ ) . 'css/animate.css', array(), '3.7.2', 'all' );
		wp_enqueue_style( 'lobibox', plugin_dir_url( __FILE__ ) . 'css/lobibox.min.css', array(), '1.2.4', 'all' );

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exopite-frontend-notifications-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

    	// http://lobianijs.com/site/lobibox
		wp_enqueue_script( 'lobibox', plugin_dir_url( __FILE__ ) . 'js/lobibox.js', array( 'jquery' ), '1.2.4', true );
		wp_enqueue_script( 'lobibox-messageboxes', plugin_dir_url( __FILE__ ) . 'js/messageboxes.js', array( 'jquery', 'lobibox' ), '1.2.4', true );
		wp_enqueue_script( 'lobibox-notifications', plugin_dir_url( __FILE__ ) . 'js/notifications.js', array( 'jquery', 'lobibox' ), '1.2.4', true );

		// Register the script
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-frontend-notifications-public.js', array( 'jquery', 'lobibox', 'lobibox-messageboxes', 'lobibox-notifications' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'efn_messages', $this->footer_get_notifications() );
		wp_localize_script( $this->plugin_name, 'efn_settings', $this->footer_settings() );

		// Enqueued script with localized data.
		wp_enqueue_script( $this->plugin_name );


    }

    public function footer_settings() {

        global $post;

		// Alternatives
		// 1.)
		// // Get the queried object and sanitize it
		// $current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		// // Get the page slug
		// $slug = $current_page->post_name;
		// 2.)
		// $slug = get_post_field( 'post_name', get_post() );

		$slug = ( is_object( $post ) ) ? $post->post_name : '';

        return array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'interval' => apply_filters( 'efn_ajax_inerval', 10000 ),
            'ajax' => ( is_archive() || is_home() ) ? false : apply_filters( 'efn_enable_ajax', false ),
            'post_id' => get_the_ID(),
            'post_slug' => $slug,
        );

    }

    /**
     * @link https://webkul.com/blog/wordpress-add-custom-links-plugin-page/
     */
    public function plugin_row_meta( $links, $file ) {

        if ( EXOPITE_FRONTEND_NOTIFICATIONS_BASENAME == $file ) {
            $row_meta = array(
                'docs'    => '<a href="' . esc_url( 'http://lobianijs.com/site/lobibox#notifications' ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'domain' ) . '" style="color:green;">' . esc_html__( 'Lobibox Notifications Docs', 'exopite-frontend-notifications' ) . '</a>'
            );

            return array_merge( $links, $row_meta );
        }

        return (array) $links;

    }

    /**
     * Test Callback.
     * Do someting here to remove (or not) messages from next round.
     */
    public function test_callback() {

        echo '<pre>';
        var_export( $_POST['element'] );
        echo '</pre>';

        file_put_contents( EXOPITE_FRONTEND_NOTIFICATIONS_PATH . '/test_callback.log', var_export( $_POST['element'], true ) );
        die();
    }

    /**
     * ToDo:
     * - Check if notifications has callback function.
     */
	// http://www.example.com/wp-admin/admin-ajax.php?action=efn_get_notifications
	public function ajax_get_notifications() {

        $messages = apply_filters( 'efn_messages_ajax', array() );

        /**
         * Test array.
         */
		// $messages += array(
		// 	array(
        //         'msg' => 'Test from AJAX',
        //         'id' => 'someid',
        //         'callback' => 'efn_test_callback',
		// 	),
		// );

		$messages = $this->check_notifications( $messages, intval( $_POST['post_id'] ), sanitize_title( $_POST['post_slug'] ) );

		die( json_encode( $messages ) );
	}

	public function get_defaults() {

		/**
		 * Default options for lobibox notifications
		 *
		 * @link http://lobianijs.com/site/lobibox
		 */
		/*
		title: true,                // Title of notification. Do not include it for default title or set custom string. Set this false to disable title
		size: 'normal',             // normal, mini, large
		soundPath: 'src/sounds/',   // The folder path where sounds are located
		soundExt: '.ogg',           // Default extension for all sounds
		showClass: 'zoomIn',        // Show animation class.
		hideClass: 'zoomOut',       // Hide animation class.
		icon: true,                 // Icon of notification. Leave as is for default icon or set custom string
		msg: '',                    // Message of notification
		img: null,                  // Image source string
		closable: true,             // Make notifications closable
		delay: 5000,                // Hide notification after this time (in miliseconds)
		delayIndicator: true,       // Show timer indicator
		closeOnClick: true,         // Close notifications by clicking on them
		width: 400,                 // Width of notification box (auto is full width)
		sound: true,                // Sound of notification. Set this false to disable sound. Leave as is for default sound or set custom soud path
		position: "bottom right"    // Place to show notification. Available options: "top left", "top right", "bottom left", "bottom right"
		iconSource: "bootstrap"     // "bootstrap" or "fontAwesome" the library which will be used for icons
		rounded: false,             // Whether to make notification corners rounded
		messageHeight: 60,          // Notification message maximum height. This is not for notification itself, this is for .lobibox-notify-msg
		pauseDelayOnHover: true,    // When you mouse over on notification, delay will be paused, only if continueDelayOnInactiveTab is false.
		onClickUrl: null,           // The url which will be opened when notification is clicked
		showAfterPrevious: false,   // Set this to true if you want notification not to be shown until previous notification is closed. This is useful for notification queues
		continueDelayOnInactiveTab: true, // Continue delay when browser tab is inactive
		*/

		return array(
			'type' => 'default', // default, warning, info, error, success
			'title' => 'Notification',
			'pauseDelayOnHover' => true,
			'continueDelayOnInactiveTab' => false,
			// 'delay' => false, // int ||false
			'delay' => 10000, // int ||false
			'closable' => true,
			'closeOnClick' => true,
			'width'=> 400,
			'messageHeight' => 60,
			'sound' => false,
			'position' => 'bottom right',
			'onClickUrl' => null,
			'showAfterPrevious' => false,
			'img' => false,
			'icon' => true,
			'iconSource' => 'fontAwesome',
            'size' => 'normal', // normal, mini, large
            'priority' => 10,
            'date' => date( 'Y-m-d' ),
			// 'roles_users' => 'all',
			// 'pages' => 'all',
		);
	}

	/**
	 * ToDos:
	 * - escape all
	 */
	public function check_notifications( $notifications, $post_id = null, $post_slug = null ) {

		$ret = array();

        if ( ! is_array( $notifications ) ) return array();

        $now = new DateTime();

		foreach ( $notifications as $key => $notification ) {
			if ( isset( $notification['msg'] ) ) {

				$notification = array_merge( $this->get_defaults(), $notification );
				$notification['title'] = esc_html( $notification['title'] );
				if ( ! in_array( $notification['type'], array( 'default', 'warning', 'info', 'error', 'success' ) ) ) {
					$notification['type'] = 'normal';
				}
				// $notification['pauseDelayOnHover'] = filter_var( $notification['pauseDelayOnHover'], FILTER_VALIDATE_BOOLEAN );

				$show = true;

				if ( isset( $notification['roles_users'] ) ) {
                    $show = $this->check_roles( $notification['roles_users'] );
				}

				if ( $show && isset( $notification['pages'] ) ) {
					$show = $this->check_pages( $notification['pages'], $post_id, $post_slug );
				}

                if ( isset( $notification['start'] ) ) {

                    $start = DateTime::createFromFormat( 'Y-m-d H:i:s', $notification['start'] );

                    if ( $start && $start > $now ) {
                        $show = false;
                    }

                }

                if ( isset( $notification['end'] ) ) {

                    $end = DateTime::createFromFormat( 'Y-m-d H:i:s', $notification['end'] );

                    if ( $end && $end < $now ) {
                        $show = false;
                    }

                }

				if ( $show ) {
					$ret[] = $notification;
				}

			}
		}

        /**
         * https://stackoverflow.com/questions/2699086/how-to-sort-multi-dimensional-array-by-value/2699159#2699159
         */
        // usort( $ret, function( $a, $b ) {
        //     return $a['priority'] - $b['priority'];
        // });

        /**
         * https://stackoverflow.com/questions/3232965/sort-multidimensional-array-by-multiple-keys/3233009#3233009
         */
        array_multisort(
            array_column( $ret, 'date' ), SORT_DESC,
            array_column( $ret, 'priority' ), SORT_DESC,
            $ret
        );

		return apply_filters( 'efn_messages_notifications', $ret );
	}

	public function check_pages( $pages, $post_id = null, $post_slug = null ) {

		if ( $pages === true || $pages === null || $pages == 'all' ) {
			return true;
		}

		if ( ! is_array( $pages ) ) {

			$pages = preg_replace( '/\s+/', '', $pages );
			$pages = explode( ',', $pages );

		}

        if ( $post_slug === null ) {
            global $post;

            if ( ! empty( $post ) && is_object( $post ) ) {
                $post_slug = $post->post_name;
            }
        }

        if ( $post_id === null ) {
            $post_id = get_the_ID();
        }

		if ( ! $post_slug && ! $post_id ) {
			return false;
		}

		if ( is_archive() || is_home() ) {
			return false;
		}

		if ( in_array( $post_slug, $pages ) || in_array( $post_id, $pages ) ) {
			return true;
		}

		return false;

	}

	public function check_roles( $conditions ) {

		if ( ! isset( $conditions ) || empty( $conditions ) || $conditions === true || $conditions === null || $conditions == 'all' ) {
			return true;
		}

		if ( ! is_array( $conditions ) ) {

			$conditions = preg_replace( '/\s+/', '', $conditions );
			$conditions = explode( ',', $conditions );

		}

		/**
		 * Check first, minimize runtime for regular visitors/guests
		 */
		if ( in_array( 'not_logged_in', $conditions ) && ! is_user_logged_in() ) {
			return true;
		}

		if ( in_array( 'logged_in', $conditions ) && ! is_user_logged_in() ) {
			return false;
		}

        if ( ! is_user_logged_in()  ) {
            return false;
        }

		/**
		 * Check WordPress roles
		 */
		$wp_roles = array(
			'administrator',
			'editor',
			'author',
			'contributor',
			'subscriber',
        );

        $in_roles = array_diff( $conditions, $wp_roles );
        if  ( count( $in_roles ) == 0 && ! is_user_logged_in() ) {
            return false;
        }

		foreach ( $wp_roles as $wp_role ) {

    		if ( in_array( $wp_role, $conditions ) && current_user_can( $wp_role ) ) {
    			return true;
			}

		}

		/**
		 * Check user login name or id
		 */
		$current_user = wp_get_current_user();

		if ( in_array( $current_user->ID, $conditions ) || in_array( $current_user->user_login, $conditions ) ) {

			return true;
		}

		return false;

	}

	public function footer_get_notifications() {

		$messages = apply_filters( 'efn_messages', array() );

        /**
         * Test array.
         */
		// $messages_test = array(
		// 	array(
		// 		'type' => 'warning',
		// 		'title' => 'Custom title <b>BULLEX</b>',
		// 		'msg' => 'Test from start with pauseDelayOnHover <b>HTML TEST</b>',
		// 		'showClass' => 'bounceInDown',  // https://daneden.github.io/animate.css/
		// 		'hideClass' => 'bounceOutDown', // https://daneden.github.io/animate.css/
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => false,
		// 		'continueDelayOnInactiveTab' => false,
		// 		'delay' => 15000,
		// 		'title' => 'Custom title',
		// 		'msg' => 'Test from start2',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test from start3',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test PAGES1',
		// 		'pages' => '1',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test PAGES2',
		// 		'pages' => '2',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test hallo-welt',
		// 		'pages' => 'hallo-welt',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test logged_in',
		// 		'roles_users' => 'logged_in',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test not_logged_in',
		// 		'roles_users' => 'not_logged_in',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test test1',
		// 		'roles_users' => 'test1',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test administrator',
		// 		'roles_users' => 'administrator',
		// 	),
		// 	array(
		// 		'pauseDelayOnHover' => true,
		// 		'continueDelayOnInactiveTab' => false,
		// 		// 'closeOnClick' => false,
		// 		'delay' => false,
		// 		'closable' => false,
		// 		'title' => 'No delay',
		// 		'msg' => 'Test contributor',
		// 		'roles_users' => 'contributor',
		// 	),
		// );

		// $messages = array_merge( $messages, $messages_test );

		$messages = $this->check_notifications( $messages );

		return $messages;

	}


}
