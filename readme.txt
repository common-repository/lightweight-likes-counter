=== Lightweight Likes Counter ===
Contributors: saifbechan
Author URI: http://saifbechan.com
Tags: social, media, facebook, twitter, googleplus, plusones, like, count
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 0.3

This plugin gets the raw counts of the amount of likes a posts has on Twitter, Facebook and Google+ without the use of JavaScript. SPEEDY!

== Description ==
This plugin gets the raw counts of the amount of likes a posts has on Twitter, Facebook and Google+ without the use of JavaScript.  No need for all the JavaScript on your website anymore. Give the user nicely styled and fast like counts to look at.

If you still want to give the user the option to share the content, you can enable the original like buttons from the website. The script for those button will ONLY be loaded when the user presses the like button.

How does it work?
-------------------
You have to set a time interval on how regular a post can be updated. Before every post is displayed it will check whether the time interval has expired, it will update the counts if needed. If you want to sync all at once there is an option for that.

Benefits !!
-------------------
 ===> Speed up your website
This plugin has major benefits in terms of speed of your website. You don't need three separate JavaScript anymore for your counts. All the data is just picked up from the database. Your website will run much smoother.

 ===> Less stress on the server
This plugin does not check for all the updates every time. It will only update the counts of 1 single post if the time interval has expired. Instead of having to fetch all the data every time, it will just do it whenever it’s needed.

Options:
-------------------
 - Get the Twitter, Facebook, Google+ likes for all your posts
 - Change the time between each update interval
 - Get the counts in raw html or with styles buttons
 - Get the normal like buttons when needed
 
 - Get a combined result option
 
 - Clear all the data created by this plugin
 - Sync all your posts as one
 
Todo
-------------------
* Add sharer information
* Display loader while buttons are loaded
* Add more websites

== Installation ==

1. Upload `lightweight-likes-counter` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

3. Call the function `LWLC_FetchData();` in your loop.

== Frequently Asked Questions ==

= How can I change the way the list is displayed  =

Check the file located in `lightweight-likes-counter/inc/LWLC_template.php`

== Screenshots ==

1. Front view if the styling is enabled

== Changelog ==

= 0.3.1 =
* updated javascript $.getscript

= 0.3 =
* Option to enable like links
* Force update on sync button

= 0.2 =
* Added linkedin
* Using serialized keys
* Made postdata hidden
* Fixed update interval !!important

= 0.1 =
* First beta build

== Upgrade Notice ==

=  0.3.1 =
Loaded scripts will cache now.