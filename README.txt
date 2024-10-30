=== CPTRP ===
Contributors: dvddemattia
Tags: custom post type, post, related post
Requires at least: 4.0
Tested up to: 4.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin that allow you to add a list of related posts for each post, including Custom Post Types.

== Description ==

Free and easy to use plugin that allow you to add a list of related posts for each post, including Custom Post Types.

### Features And Options:
* Add relations between posts.
* Set number of related posts (1-20).
* Custom title and custom template path.
* Working with shortcode [cptrp].
* Simple and free.
* More features coming on updates.

== Installation ==

1. Install the plugin either via the WordPress.org plugin directory, or by uploading the folder to your server (in the /wp-content/plugins/ directory).
2. Activate the CPTRP plugin through the 'Plugins' menu in WordPress.
3. Go to the Settings page to set your options

== Frequently Asked Questions ==

= For which type of posts this plugin works? =

You can choose for which type of posts make it work. In the settings page, select the type of posts to activate them.

= What is the `Number of Fields` field in the Setting page =

'Number of Fields' set the number of fields for selecting related posts that will be displayed in the post page. This number must be included between 1 and 20. However, if for example this value is set on `3` but you do not select the posts for which you want to create a relation, related posts will not appear in your front end page.

= How can I insert a custom title before the related posts list? =

Go to the Settings page and set the title in the `Title` field.

= Which template will be used to display the related posts? =

By default the shortcode will get the template part `content.php`, if post is a standard post. If post is custom post type it will get the template part `content-{custom-post-type}.php`. You can change this behaviour specifying a custom path with the name of the file that should be used.
For example, if you're using CPTRP on a custom post type named `books` but you don't have (or don't want to use) a file named `content-books.php` and so you just want to use `content.php`, then you can specify it in the `Path` input field of the settings page. In this case, supposing that the file content.php is in the `partials` subfolder, you can type `partials/content`.

= Can I have a different content template for each post type? =

Yes, you can have a different content template creating a file `content-{custom-post-type}.php`. This will be taken by default. But if you specify a content template path, then will be used that for all the post type selected. So if you have more than one post type selected be aware that you need to use the same template for all of them.

= I set the options in the Settings page and selected the related posts but still can't see related post in my website. =

You need to insert [cptrp] shortcode where related posts should be displayed.

== Screenshots ==

1. Settings - Required settings are the post types you want to use and the number of fields you need. Optional settings are a custom title and a custom path for the content template.
2. Related posts selection - This metabox will appear in the admin post page for the post types you have activated in the settings page. 

== Changelog ==

= 1.0.0 - 06/05/2016 =
* Initial Public Release