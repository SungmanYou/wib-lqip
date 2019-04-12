=== WIB - LQIP Plugin ===
Tags: wordpress, php, lqip
License: MIT
License URI: https://opensource.org/licenses/MIT

Simple Wordpress LQIP Plugin.


== Description ==

Simple Wordpress LQIP Plugin with PNG to JPG conversion.

This plugin generates two LQIPs with different sizes for all images in Media Library.
1. 50 x 0 (Original ratio)
2. 50 x 50 (Square - cropped)

It's for personal usage. Please be careful using it. Not tested 100%.



== Installation ==

1. Download the repository & copy to the your plugin directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Done.



== Hooks ==

1. Filter - wib_lqip_png_to_jpg_quality
Image quality option for converting PNG to JPG. 
Value must be between 1 ~ 100. (Default - 100)

2. Filter - wib_lqip_placeholder_quality
Image quality option for LQIP. 
Value must be between 1 ~ 100. (Default - 25)
You really don't need to set it too low.
With the default value, the size of the image will be 1KB approximately.



== Changelog ==

= 1.0 =
* Initial version.



== Upgrade Notice ==

= 1.0 =
Initial version.
