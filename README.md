CP Menu Master
==============

Take control of your control panel menus! CP Menu Master allows you to hide channels from the
publish and edit menus for ALL users, and display control panel menus on mouse-over instead of
click. Think of all those seconds you will save!

Please note: This extension does not enforce any kind of security to prevent users publishing
to channels. It simply removes links from the publish menu, to reduce clutter. Smart users
can still publish to those channels by accessing the correct URL.

Configuration
-------------

Configuration options for this extension can be found under Add-Ons > Extensions in your
ExpressionEngine control panel. Settings apply to all users, even super admins.

Updating
--------

When upgrading from a pre-2.0 release, please make sure you replace the entire cp_menu_master
directory, and not simply merge the contents. The old acc, mcp, and upd files are no longer
required. Your pre-2.0 module settings will be migrated to the new version.

Changelog
---------

### 2.2.0
*March 28, 2012*

* Removed "Display Content > Edit as a submenu" option to support EE 2.4+

### 2.1.0
*August 18, 2011*

* Added "Hide Filter by Channel on Edit Channel Entries page" option

### 2.0.0
*June 7, 2011*

* Rewritten as an extension to take advantage of new menu hook in EE 2.1.5+

### 1.0.1
*March 25, 2011*

* Added ability to hide channels from the edit menu independently of the publish menu

### 1.0.0
*March 16, 2011*

* Initial release
