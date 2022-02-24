=== JPD2 ===

Contributors:Shelob9
Donate link: http://JoshPress.net
Tags: transients, caching
Requires at least: 3.8
Tested up to: 3.8.1
Stable tag: 0.8.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Makes caching WordPress queries via the Transients API easy.

== Description ==

Makes caching the results of a WP_Query, WP_User_Query and WP_Meta_Query, via the transients API easy. Realize increased performance, by caching the results of your queries, with one simple function.

All saved queries are automatically reset when any post is updated.

Basic usage:

* Specify arguments for the query ($args)
* Give the query a name ($name)
* $query = jpd2_better_query( $args, $name );
* Use $query like you would any other WP_Query object.
* See FAQ for other usages

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'JPD2'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `JPD2.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `JPD2.zip`
2. Extract the `JPD2` directory to your computer
3. Upload the `JPD2` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Frequently Asked Questions ==

= What I So Awesome About Transient Cacheing? =
The [Transients API](http://codex.wordpress.org/Transients_API) creates temporary entries into your database. One great use is to store the result of complex queries, which allows WordPress to run one query--to get the transient--instead of many queries, and get the same results.

[This article](http://www.doitwithwp.com/introduction-transients-wordpress/) explains very well how that works. This plugin automates the process for you.

= Is It Safe To Call This Function In My Theme Or Plugin? =
The safest method would be to wrap the call in a check of function_exists().

For example code see: [https://gist.github.com/Shelob9/9425101#file-use_jpd2-php](https://gist.github.com/Shelob9/9425101#file-use_jpd2-php)

This way if the JPD2 plugin is not activated, WordPress will run WP_Query directly, instead of returning a fatal error, which would suck.

= How Long Does The Transient Last? =
The transient lasts up to the default transient expiration time, unless an expiration value is set with the $expire argument.

= How Do I Change The Default Transient Expiration Time? =
Two ways:

1. Define the JPD2_EXP constant in wp-config, or anytime before this plugin is loaded.
2. Use the 'JPD2_expire' action

The action, if used, will override the constant.

= How Do I Use A WP_User_Query or WP_Meta_Query? =
By default arguments are passed to WP_Query. You may use the $type argument. You can set it to 'wp_query', which is the default, 'wp_user_query', or 'wp_meta_query'.

= Why Is This Plugin Called JPD2? =
Because Star Wars.

== Screenshots ==

1. Example usage.

== Changelog ==

= 0.0.1 =
* The first version on WordPress.org.

= 0.0.2 =
* Readme change for better readability in FAQ
* Update plugin URI

= 0.1.0
* Fix conditional logic preventing non WP_Query queries from working.
* Fix args in main function.
* Support for Pods queries.

== Upgrade Notice ==

Nothing to notice for now.
