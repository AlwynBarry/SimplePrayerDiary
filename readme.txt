=== Simple Prayer Diary ===
Contributors: Alwyn Barry, from code by mpol
Tags: simple prayer diary, prayer diary, prayer, prayer item
Requires at least: 4.1
Tested up to: 6.1.1
Stable tag: 1.0.0
License: GPLv2 or later

Simple Prayer Diary is a dated prayer reminder list for Wordpress, for people who just want something simple to use.

== Description ==

Simple Prayer Diary is a dated prayer reminder list for people who just want something simple to use in Wordpress.
The goal is to provide a simple way to show a list of items to pray for by upcoming date to your visitors.


Current features include:

* Shortcode with a list of future prayer items.
* Widget to display future prayer items.
* Simple and clean admin interface that integrates seamlessly into WordPress admin.
* Admin page to quickly add a prayer item.
* Localization. Own languages can be added very easily through [GlotPress](https://translate.wordpress.org/projects/wp-plugins/super-simple-event-calendar).

= Support =

If you have a problem or a feature request, please send a message to the author.


= Demo =

Currently there is no demo site


= Contributions =

This plugin is really only marginally changed and expanded from the Super Simple Event Calendar of M. Pol


== Installation ==

= Installation =

* Install the plugin through the admin page "Plugins".
* Alternatively, unpack and upload the contents of the zipfile to your '/wp-content/plugins/' directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* Place the shortcode '[simple_prayer_diary]' or '[simple_prayer_month_diary]' in a page.
* Add new Prayer Items through the Quick Add in the admin menu.

= How to add Prayer Items and format them =

The prayer item is dated using the date in the Quick Add meta box.  This is used for the title field and the publish date for the Prayer Item.
Use the content field for the prayer reminder details. You can make this as complex or as simple as you wish.
You can optionally add a category for each Prayer Item (managed through the Categories entry in the Prayer Items Admin menu).

IMPORTANT: If you are re-using or editing an old prayer item, you will have to keep the Title and Publish Date in sync - this is
only set automatically when you use the Quick Edit entry form when initially creating the Prayer Item.  In the Title and Publishing
date set the date to the end date and end time of the prayer item.

= License =

The plugin itself is released under the GNU General Public License. A copy of this license can be found at the license homepage or in the simple-prayer-diary.php file at the top.


== Frequently Asked Questions ==

= I only want to show prayer reminders in the simple list from a category. =

You can use a shortcode parameter for showing events only from certain categories:

	[simple_prayer_diary category="Youth,Children"]

= I want to limit the number of events in the shortcode. =

You can use a shortcode parameter for showing a limited number of prayer items after today's date:

	[simple_prayer_diary posts_per_page="5"]

I want to show a whole month of prayer items:

	[simple_prayer_month_diary]

== Screenshots ==

None as yet


== Changelog ==

= 1.0.0 =
* 2022-11-21
* Added a whole month view short code, and provided responsive CSS for the month table.
* Simplified the Quick Edit so that the title is automatically the date, and the publish date is set automatically from the date too

* 2022-11-03
* Modifications throughout to change it from an events calendar to a prayer diary
* Changed the layout from table-based to DIV based, with appropriate CSS to format it


= All below are from super-simple-events-calendar =

= 1.4.2 =
* 2022-06-05
* Fix error when saving the page with shortcode.

= 1.4.1 =
* 2022-04-15
* Support posts_per_page parameter in shortcode for simple list.
* Support season in widget as well.

= 1.4.0 =
* 2022-01-10
* Support season parameter in shortcode for simple list.

= 1.3.3 =
* 2021-11-13
* Revert previous update, it acts funky in practice.

= 1.3.2 =
* 2021-11-12
* Use date/hour in WP_Query too, in case future events fail to get their status changed on roll-over.

= 1.3.1 =
* 2021-08-20
* Only show edit link when appropriate.
* Some updates from phpcs and wpcs.

= 1.3.0 =
* 2021-03-25
* Use admin page with quick edit instead of dashboard widget, more focused this way.

= 1.2.0 =
* 2021-03-23
* Add dashboard widget to quickly add an event.

= 1.1.3 =
* 2020-04-10
* Fix wrong usage of get_the_ID().

= 1.1.2 =
* 2020-04-10
* Fix undefined error.

= 1.1.1 =
* 2020-04-10
* Add term classes for season to each event post.

= 1.1.0 =
* 2020-03-23
* Update and add classes for html elements.

= 1.0.3 =
* 2019-12-18
* Remove ':' character from displays.

= 1.0.2 =
* 2019-01-31
* Better dashicon.

= 1.0.1 =
* 2018-09-23
* Use 'nl2br()' on the content.

= 1.0.0 =
* 2018-08-23
* Initial release.
