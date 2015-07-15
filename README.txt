=== Plugin Name ===
Contributors: wkempferjr, jnobles
Donate link: http://guestaba.com/donate
Tags: hotel, resort, hospitality
Requires at least: 4.0
Tested up to: 4.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Hospitality facilitates the display of information about hotel and resort rooms and meeting spaces.

== Description ==

Upon installation, this plugin creates three custom post types: Room, Amenity Sets, and Pricing Models. A Room post can represent
any lodging or meeting space that can be reserved. An Amenity Set post contains a list of standard room amenities. Pricing models manage
room rates along with their typical seasonal and other calendar-based fluctuation.

Amenity Sets

Amenity Sets provide a way to create reusable lists of standard room amenities. If the amenities are 
mostly the same from one room to the next, just a couple of amenity sets can be created to contain the amenity list for all rooms.
This saves time by not requiring the re-entering of the same list of amenities for all rooms. Also, any room-specific amenities
can be appended to your standard amenity list.  When creating a Room post type, a list of Amenity Sets is
presented in a drop down, one of which can be chosen to represent the amenities for that particular room. 

Pricing Models

Pricing models provide a way to create reusable price group lists for a particular room which may vary based on the seasons, holidays, and local events. Visitors
to your site will be able to see, for example, how room rates vary between the on season and off season. One pricing model can be applied to
multiple rooms, hence reducing the need to reenter a new pricing model for each room. 

Rooms and Room Listings

The room post type adds images and text to the preconfigured Amenity Sets and Pricing Models to create an individual room page. 
Multiple individual room pages are collected and displayed on the Rooms Listing page featuring a thumbnail, pricing information,
an excerpt and a link to the individual room. Text consists of a room description displayed on the individual room page and an 
excerpt displayed on the Rooms Listing page. The active themes archive.php file is used for the default Rooms Listing page. 
Images consist of a featured image that is used for the Rooms Listing page thumbnail and one or more images for the individual page.
Multiple images can be posted with the room, all of which are displayed in a basic slider. The built-in slider uses Slick.js
and can be optionally replaced with a third-party slider. We recommend MetaSlider. By default the plugin uses the active themes
single.php file to create the individual room pages.

The room post template that comes with the plugin will display the room title, an image slider, the full room description, amenities, and prices. 
Three widget areas are provided on the room post page the permit display of reservation and booking forms, guest policies, maps, directions 
and other information that you want your guests to know. 

Plugin Details

The plugin includes two built-in methods for displaying room listings and individual rooms. A list of all rooms, along with a thumbnail, 
name, description, and price range is provided by default via the slug "rooms". For example, to display a room listing, you would go to
the URL http://yourmotel.com/rooms. With each listed room description is a link that points to the room post. Foundation for Sites framework
is used for grid layout and certain css stylings. 

Shortcodes

To maximize flexibility in layout, the plugin includes several shortcodes. Room listings can be displayed on any page of your site. If the single
room page layout does not meet your needs, each component of the a room post--images, description, thumbnail, and rate information--can be
arbitrarily positioned and styled via their respective shortcode.  

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
dashboard Plugins page, or got to Settings->Hospitality. See our [support site] (http://support.guestaba.com/support/home) to find out how
configure the plugin and begin entering information about your rooms and meeting spaces. 


== Frequently Asked Questions ==

See our the most up-to-date version of our [help documentation] (http://support.guestaba.com/support/home)


== Screenshots ==

1. A room and rates listing.
2. A room listing.

== Changelog ==

= 1.0.0 =
* The initial version.

== Upgrade Notice ==

1.0.0 Initial version.

