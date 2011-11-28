=== Plugin Name ===
Contributors: baverkungen
Donate link: -
Tags: Movies, imdb, list, top 10
Requires at least: 2.5
Tested up to: 3.2.1
Stable tag: 1.0

Generates a list from Imdb.com MOVIEmeter. Top 10 of the most popular movies right now.

== Description ==

Gives you the list MOVIEmeter that can be found on http://www.imdb.com/chart/. Contains the most popular movies right now. The plugin cache data and the files is put in the cache map that can be found inside the plugin. 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the ImdbScraper map to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place [ImdbScraper] on any page or '<?php do_shortcode([ImdbScraper]) ?> in your templates. 

== Frequently Asked Questions ==

Q: How to put the list in the sidebar?
A: To put it in the sidebar edit your functions.php and add the following code: "add_filter('widget_text', 'do_shortcode');". You can also download and install a plugin called "Shortcode For Sidebar" to do this.

== Changelog ==

= 1.0 =
* First release of the Plugin. Generates a list from the Imdb MOVIEmeter.