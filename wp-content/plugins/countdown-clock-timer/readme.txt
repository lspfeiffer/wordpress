=== Countdown Clock Timer ===
Contributors: luisperezphd
Donate link: 
Tags: countdown
Requires at least: 3.3
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A countdown, days, hours, minutes.

== Description ==

A countdown clock timer widget and shortcode from <a href="http://ipadstopwatch.com">ipadstopwatch.com</a>. It works on PC and mobile devices. It adjusts to any size. Very easy to use just specify the date a title and a message for when the day is reached. Great for events like big games, birthdays, new years, etc.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== How it works ==

You can either add it in the widgets screen, or you can use the shortcode:

[countdown date='January 1, 2014' title='New Year Countdown' message='Happy New Year' width='157']

Optionally you can specify a width.

The widget works by pulling in the actual full countdown web application 
from the <a href="http://ipadstopwatch.com">ipadstopwatch.com</a> site.

More specifically it pulls in an HTML file into an iframe.