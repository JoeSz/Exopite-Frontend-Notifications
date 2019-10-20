=== Plugin Name ===
Donate link: https://www.joeszalai.org
Tags: comments, spam
Requires at least: 4.7
Tested up to: 5.2.4
Stable tag: 4.7
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Display frontend notifications thru hooks with lobibox (PHP & AJAX).

== Description ==

This plugin neither has options nor display anything after activation. You have to use hooks to use this plugin.

Display frontend PHP notifications via 'efn_messages' hook and/or AJAX notifications via 'efn_messages_ajax' hook.

AJAX functionality is disabled by default, you can enable with the 'efn_enable_ajax' hook.

The plugin check AJAX messages every 10 sec. You can change this with 'efn_ajax_inerval' hook.

Check for all options and previews: http://lobianijs.com/site/lobibox#notifications

== How to use ==

function my_ajax_notifications( $messages ) {

    $my_messages = array(
        array(
            'pauseDelayOnHover' => true,
            'continueDelayOnInactiveTab' => false,
            // 'closeOnClick' => false,
            'delay' => false,
            'closable' => false,
            'title' => 'No delay',
            'msg' => 'Test logged_in',
            'roles_users' => 'logged_in',
            // this is optional, to react on user dismiss, only in AJAX available
            'id' => 'some_id',
            'callback' => 'my_ajax_callback_function', // to remove or react on user dismiss
        ),
        // ...
    );

    $messages = array_merge( $messages, $my_messages );

    return $messages;
}
add_filter( 'efn_messages_ajax', 'my_ajax_notifications' );

function my_ajax_callback_function() {

    $element = $_POST['element]; // The options array for the notification.
    $my_id = $_POST['element][id];

    // do something with the infos.

}

add_action('wp_ajax_my_ajax_callback_function', 'my_ajax_callback_function');
add_action('wp_ajax_nopriv_my_ajax_callback_function', 'my_ajax_callback_function');

== Installation ==

1. Upload `exopite-frontend-notifications.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use one of the hooks to display messages

== Screenshots ==

1. Screenshot

== Changelog ==

= 20191020 =
* Add readme
* Add screenshot

= 20191017 =
* Initial release

== Disclaimer ==

All softwares and informations are provided "as is", without warranty of any kind, express or implied, including but not limited to the warranties of merchant-ability, fitness for a particular purpose and non-infringement.

Please read: https://www.joeszalai.org/disclaimer/