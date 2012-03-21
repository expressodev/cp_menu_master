CP Menu Master
==============

Take control of your control panel menus! CP Menu Master allows you to hide channels from the
publish menu for ALL users, show channels as a sub-menu in the edit menu, and even display
control panel menus on mouse-over instead of click. Think of all those seconds you will save!

Please note: This extension does not enforce any kind of security to prevent users publishing
to channels. It simply removes links from the publish menu, to reduce clutter. Smart users
can still publish to those channels by accessing the correct URL.

Requirements
------------

* ExpressionEngine 2.1.5+

Installation
------------

1. Copy the entire `cp_menu_master` folder to `/system/expressionengine/third_party` on your server.
2. Enable the extension under Add-ons > Extensions in your ExpressionEngine control panel.

Updating
--------

When upgrading from a pre-2.0 release, please make sure you replace the entire ``cp_menu_master``
directory, and not simply merge the contents. The old acc, mcp, and upd files are no longer
required. Your pre-2.0 module settings will be migrated to the new version.

Configuration
-------------

Configuration options for this extension can be found under Add-Ons > Extensions in your
ExpressionEngine control panel. Settings apply to all users, even super admins.

Changelog
---------

**2.0.0** *(2011-06-07)*

* Rewritten as an extension to take advantage of new menu hook in EE 2.1.5+

**1.0.1** *(2011-03-25)*

* Added ability to hide channels from the edit menu independently of the publish menu

**1.0.0** *(2011-03-16)*

* Initial release
