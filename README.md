# Exopite Frontend Notifications
## WordPress Plugin
Display frontend notifications thru hooks with lobibox (PHP & AJAX).

- Author: Joe Szalai
- Version: 20191122
- Plugin URL: https://github.com/JoeSz/Exopite-Frontend-Notifications
- Demo URL: https://www.joeszalai.org/exopite/frontend-notifications/
- Author URL: https://www.joeszalai.org
- License: GNU General Public License v3 or later
- License URI: http://www.gnu.org/licenses/gpl-3.0.html

DESCRIPTION
-----------

This plugin neither has options nor display anything after activation. You have to use hooks to use this plugin.

Display frontend PHP notifications via 'efn_messages' hook and/or AJAX notifications via 'efn_messages_ajax' hook.

AJAX functionality is disabled by default, you can enable with the 'efn_enable_ajax' hook.

The plugin check AJAX messages every 10 sec. You can change this with 'efn_ajax_inerval' hook.

Check for all options and previews: http://lobianijs.com/site/lobibox#notifications

== How to use ==

ONLY PHP
```php
function my_notifications( $messages ) {

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

            // Sort messages
            'priority' => 10,
            'date' => '2000-12-31 23:59:59',

            // Display message in a time frame
            'start' => '2000-12-31 23:59:59',
            'end' => '2022-12-31 23:59:59',
        ),
        // ...
    );

    $messages = array_merge( $messages, $my_messages );

    return $messages;
}
add_filter( 'efn_messages_ajax', 'my_notifications' );
```

AJAX
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
add_filter( 'efn_enable_ajax', '__return_true' );

function my_ajax_callback_function() {

    $element = $_POST['element']; // The options array for the notification.
    $my_id = $_POST['element']['id'];

    // do something with the infos.

}

add_action('wp_ajax_my_ajax_callback_function', 'my_ajax_callback_function');
add_action('wp_ajax_nopriv_my_ajax_callback_function', 'my_ajax_callback_function');
```

SCREENSHOT
----------
![](exopite-frontend-notifications/assets/screenshot-1.jpg)

INSTALLATION
------------

1. [x] Upload `exopite-frontend-notifications` to the `/wp-content/plugins/exopite-frontend-notifications/` directory

2. [x] Activate the plugin through the 'Plugins' menu in WordPress

REQUIREMENTS
------------

Server

* WordPress 4.7+ (May work with earlier versions too)
* PHP 7.0+ (Required)

Browsers

* Modern Browsers
* Firefox, Chrome, Safari, Opera, IE 10+
* Tested on Firefox, Chrome, Edge, IE 11

CHANGELOG
---------

= 20191122 =
* Add priority for messages (for sort)
* Add date for messages (for sort)
* Add start (as ISO date y-m-d H:i:s) for messages
* Add end (as ISO date y-m-d H:i:s) for messages
* Fix some AJAX issues

= 20191111 =
* Update Plugin Update Checker to 4.8

= 20191020 =
* Add readme
* Add screenshot

= 20191017 =
* Initial release

LICENSE DETAILS
---------------
The GPL license of Exopite Frontend Notifications grants you the right to use, study, share (copy), modify and (re)distribute the software, as long as these license terms are retained.

SUPPORT/UPDATES/CONTRIBUTIONS
-----------------------------

If you use my program(s), I would **greatly appreciate it if you kindly give me some suggestions/feedback**. If you solve some issue or fix some bugs or add a new feature, please share with me or mke a pull request. (But I don't have to agree with you or necessarily follow your advice.)

**Before open an issue** please read the readme (if any :) ), use google and your brain to try to solve the issue by yourself. After all, Github is for developers.

My **updates will be irregular**, because if the current stage of the program fulfills all of my needs or I do not encounter any bugs, then I have nothing to do.

**I provide no support.** I wrote these programs for myself. For fun. For free. In my free time. It does not have to work for everyone. However, that does not mean that I do not want to help.

I've always tested my codes very hard, but it's impossible to test all possible scenarios. Most of the problem could be solved by a simple google search in a matter of minutes. I do the same thing if I download and use a plugin and I run into some errors/bugs.

DISCLAMER
---------

NO WARRANTY OF ANY KIND! USE THIS SOFTWARES AND INFORMATIONS AT YOUR OWN RISK!
[READ DISCLAMER!](https://joe.szalai.org/disclaimer/)
License: GNU General Public License v3

[![forthebadge](http://forthebadge.com/images/badges/built-by-developers.svg)](http://forthebadge.com) [![forthebadge](http://forthebadge.com/images/badges/for-you.svg)](http://forthebadge.com)
