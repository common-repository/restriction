=== Restriction ===
Contributors: tooltipsorg
Author URI: https://tooltips.org/
Tags: restriction
Requires at least: 4.0
Tested up to: 6.4.3
Stable tag: 1.0.9
Requires PHP: 5.4
License: GPLv3 or later

Restric wordpress site, or wordpress posts, or restricted content section in posts to non-member users

== Description ==

Restriction is a wordpress restrict plugin to restric wordpress site, or wordpress posts, or restricted content section in posts to non-member users.
 
When non-member users view your wordpress pages, they will be redirected to your register page or landing page, you can set up the URL of register page or landing page in "Restriction Global Setting" panel

In "Restriction Global Setting" panel, you can set up opened pages that will opened for guest, guest can view these pages and will not be directed to landing page.

In "Restriction Global Setting" panel, you can enable "page lLevel protect" option, if you enabled this option, in page / post editor, you will find "Members only for this page?" meta box at the right top of the wordpress standard editor.
If you checked "Allow everyone to access the page" checkbox in meta box, the post will be opened to all guest users
By this way, you do not need enter page URLs to ppened Pages panel always.

If you want to open your wordpress site to guest users, you can one click to open your site via "Temporarily Turn Off All Featrures" option 

If you just want to restrict a few private message, you can use shortcode [restriction]hi, this is private content[/restriction]

Also you can restirct post private content based on users via restriction shortcode, you can use it like this: 
[restriction roles='editor,administrator']hi, we are private content[/restriction]
or 
[restriction roles='editor']hi, we are private content[/restriction]

And you can redirect users which have no permission to view the wordpress restricted content to a customized URL with this way: 
[restriction roles='editor,administrator,admin' link='https://tooltips.org/shop/']hi, we are private content[/restriction]

If you do not want to restrict post private content with user roles, just use restricted shortcode like this:
[restriction]hi, we are private content[/restriction]


== Installation ==

1:Upload the restriction plugin to your blog
2:Activate it 
3:Finish setup in restriction menu

== How To Use ==
Once installed the restriction plugin, please click restriction menu in back end, we have detailed step by step notes for each option
If you just want to restrict a few private message, you can use shortcode [restriction]hi, this is private content[/restriction] 

== Screenshots ==

== Download ==
http://www.wordpress.org/

== Changelog ==
= 1.0.9 =
>[Support Custom Login Link Redirection for Restricted Content Access and enhanced code security](https://tooltips.org/support-customize-the-login-link-wordpress-restricted-content-plugin-by-wordpress-tooltips-1-0-9-released/)

= 1.0.5 =
Now you can restirct post content based on users via restriction shortcode, you can use it like this: 
[restriction roles='editor,administrator']hi, we are private content[/restriction]
or 
[restriction roles='editor']hi, we are private content[/restriction]
If you do not want to restrict post content with user roles, just use restricted shortcode like this:
[restriction]hi, we are private content[/restriction]

= 1.0.0 =
Initial release
