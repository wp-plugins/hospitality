=== Plugin Name ===
Contributors: wkempferjr, jnobles
Donate link: http://guestaba.com/donate
Tags: hotel, resort, hospitality
Requires at least: 4.0
Tested up to: 4.2.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Hospitality Plugin and Shortcodes simplify creating and editing rooms, amenities and prices.

== Description ==

The hospitality plugin is intended to be used by hotels, motels, resorts, and other businesses to
manage the display of information about rooms and meeting spaces. The management of room amenities and
pricing is simplified by centralizing their administration in custom post types. The plugin provides built-in room listing
and room detail pages that will work with most themes (CSS changes may be required for some themes).
The room detail page also provides widget areas that permit you to integrate reservation forms,
directions, guest policies, or anything else you might want. For even greater flexibility, the plugin
provides a set of shortcodes that allow you integrate room content elements into your own custom page
layout.

** Developer Focused Release ** "Out of the Box" usage may not work with many themes. Use of shortcodes is currently
the best way to use the Hospitality Plugin. Default, non-shortcode usage works well with Vantage theme from Siteorigin.com.

[Documentation for the shorcodes](http://support.guestaba.com/support/solutions/folders/5000260018 "Hospitality Plugin Docs and Support Portal")

[Main Page for the Guestaba Support Portal is here](http://support.guestaba.com "Guestaba Support Page"). Create an account here to receive updates.

= Latest Updates =
You can know change the currency symbol for prices in the plugin options panel.

= Shortcodes =

To maximize flexibility in layout, the plugin includes several shortcodes. Room listings can be displayed on any page of your site simply by inserting the room listing shortcode, which is **[room_listing]**. If the single
room page layout does not meet your needs, each component of the a room post--images, description, thumbnail, and rate information--can be
arbitrarily positioned and styled via their respective shortcode.


= Custom Post Types =

The plugin creates three custom post types: Room, Amenity Sets, and Pricing Models. A Room post can represent
any lodging or meeting space that can be reserved. An Amenity Set post contains a list of standard room amenities. Pricing models manage
room rates along with their typical seasonal and other calendar-based fluctuation.

=  Amenity Sets =

Amenity Sets provide a way to create reusable lists of standard room amenities. I

= Pricing Models =

Pricing models provide a way to create reusable price group lists for a particular room which may vary based on the seasons, holidays, and local events.

= Rooms and Room Listings =

The room post type adds images and text to the preconfigured Amenity Sets and Pricing Models to create an individual room page.
Multiple individual room pages are collected and displayed on the Rooms Listing page featuring a thumbnail, pricing information,
an excerpt and a link to the individual room.

The room post template that comes with the plugin will display the room title, an image slider, the full room description, amenities, and prices.
Three widget areas are provided on the room post page the permit display of reservation and booking forms, guest policies, maps, directions
and other information that you want your guests to know.

== Widget Areas ==

The room detail page provides three sidebars (or widget areas). The Room First, Second, and Third widget areas, are
positioned respectively at the top, middle, and bottom area of the page. This is useful for entering and displaying
content that is common to all rooms. Maps, directions, guest policies, and reservation forms are examples of what you
could integrate into the plugin via the widget areas.

== Additional Features ==
A built-in slider makes it easy to upload and display images for each room. Or, if you already have a favorite slider,
it can be easily integrated with the hospitality plugin. 
  

== Installation ==

From the Wordpress Dashboard Plugins->Add New page:

1. Search for "Hospitality". Find Hospality from Guestaba in the listing and clic its "Install Now". 
1. Click the "Activate Now" once the plugin is downloaded and installed.

or 

1. Download the plugin to a local folder on your computer from the WordPress plugin repository.
1. On the Plugin->Add New page, click the "Upload Plugin" button, navigate to wherever you have downloaded plugin zip file,
and then select it to start the upload. 
1.  Click the "Activate Now" link to activate.

The plugin can also by installed by unzipping the hospitality.zip file to your wp-content/plugins folder. 

Once the plugin is installed, you can find its settings page by clicking the "action" link found in the Hospitality listing in the 
dashboard Plugins page, or got to Settings->Hospitality. See our [support site](http://support.guestaba.com/support/home) to find out how
configure the plugin and begin entering information about your rooms and meeting spaces. 


== Frequently Asked Questions ==

See our the most up-to-date version of our [help documentation](http://support.guestaba.com/support/home)


== Screenshots ==

1. A room and rates listing.
2. Adding a new room: name and description fields.
3. Adding a new room: amenties and pricing model fields.
4. Adding a new room: image slider and related fields.
5. Adding amenity set listing.
6. Adding a new amenity set.

== Changelog ==

= 1.0.0 =
* The initial version.

= 1.0.1 =
* Corrected markdown errors in readme.txt.
* Corrected location of screenshots.

== Upgrade Notice ==

= 1.0.0 = 
Initial version.

= 1.0.1 =  
Administrative fixes. No code changes.

= 1.0.2 =
Added support for user-specified currency symbol.
Misc administrative code for handling plugin upgrades.
