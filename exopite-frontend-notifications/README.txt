=== Plugin Name ===
Donate link: https://www.joeszalai.org
Tags: comments, spam
Requires at least: 4.8
Tested up to: 5.3
Stable tag: 4.8
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

```php
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
```

```php
function my_ajax_callback_function() {

    $element = $_POST['element]; // The options array for the notification.
    $my_id = $_POST['element][id];

    // do something with the infos.

}

add_action('wp_ajax_my_ajax_callback_function', 'my_ajax_callback_function');
add_action('wp_ajax_nopriv_my_ajax_callback_function', 'my_ajax_callback_function');
```

== Installation ==

1. Upload `exopite-frontend-notifications.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use one of the hooks to display messages

== Screenshots ==

1. Screenshot

== Changelog ==

= 20191111 =
* Update Plugin Update Checker to 4.8

= 20191020 =
* Add readme
* Add screenshot

= 20191017 =
* Initial release

== SUPPORT/UPDATES ==

If you use my program(s), I would **greatly appreciate it if you kindly give me some suggestions/feedback**. If you solve some issue or fix some bugs or add a new feature, please share with me or mke a pull request. (But I don't have to agree with you or necessarily follow your advice.)<br/>
**Before open an issue** please read the readme (if any :) ), use google and your brain to try to solve the issue by yourself. After all, Github is for developers.<br/>
My **updates will be irregular**, because if the current stage of the program fulfills all of my needs or I do not encounter any bugs, then I have nothing to do.<br/>
**I provide no support.** I wrote these programs for myself. For fun. For free. In my free time. It does not have to work for everyone. However, that does not mean that I do not want to help.<br/>
I've always tested my codes very hard, but it's impossible to test all possible scenarios. Most of the problem could be solved by a simple google search in a matter of minutes. I do the same thing if I download and use a plugin and I run into some errors/bugs.

== Disclaimer ==

All softwares and informations are provided "as is", without warranty of any kind, express or implied, including but not limited to the warranties of merchant-ability, fitness for a particular purpose and non-infringement.

Please read: https://www.joeszalai.org/disclaimer/
