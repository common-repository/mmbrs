=== mmbrs ===
Contributors: benwills
Donate link: http://compassionate.co/
Tags: access, capability, capabilities, content access, member, members, membership, memberships, restrict access, role, roles, shortcode, shortcodes, user, users, user meta, usermeta
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 1.0
License: GPL2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restrict content access using shortcodes; based on roles, capabilities, user meta, and logged in status.



== Description ==

The mmbrs plugin allows you to restrict content access using shortcodes.

Content may be restricted based on role, capability, user meta, and logged-in status.


### What makes mmbrs different:
*	Restriction based on role, capability, user meta, and logged-in status is
	all in one place.
*	You are able to restrict content based on if a user **is='not'**. For
	example, *Show this content if a user is not an administrator.*
*	You can pass multiple variables to the roles and capabilities shortcodes.
	For example, *Show this content to users who are subscribers,
	contributors, or editors.*
*	By default, any content within these four shortcodes is not displayed in
	feeds. You may also set the **showinfeed='yes'** attribute to show the
	content in feeds.
*	It is as lean as I could get it, with logic ordering to cut down on
	processing. Which isn't saying much, but if you've got suggestions, I'm
	open to hearing them.



### The four mmbrs shortcodes are:
* [mmbrs_logged_in]
* [mmbrs_roles]
* [mmbrs_capabilities]
* [mmbrs_user_meta]



### [mmbrs_logged_in]


> [mmbrs_logged_in]Content.[/mmbrs_logged_in]

* *Attribute: none*
    * Shows content to logged in users.


> [mmbrs_logged_in is='not']Content.[/mmbrs_logged_in]

* Attribute: is
    * Optional.
    * **is** only accepts **not**.
    * Shows content to logged out users.
    * Case insensitive.


> [mmbrs_logged_in showinfeed='yes']Content.[/mmbrs_logged_in]

* Attribute: showinfeed
    * Optional.
    * **showinfeed** only accepts **yes**.
    * Shows content in the feed and ignores all other attributes when displaying in the feed. (If it is not being displayed in a feed, all of the attributes take effect.)
    * Case insensitive.



### [mmbrs_roles]


> [mmbrs_roles]Content.[/mmbrs_roles]

* Attribute: none
    * Returns nothing.


> [mmbrs_roles equals='subscriber']Content.[/mmbrs_roles]

* Attribute: equals
    * Required.
    * Accepts multiple. e.g. equals='subscriber,contributor'
    * Shows content to users with the 'subscriber' role.
    * Case sensitive.


> [mmbrs_roles is='not' equals='subscriber']Content.[/mmbrs_roles]

* Attribute: is
    * Optional.
    * Shows content to users who do not have the 'subscriber' role.
    * **is** only accepts **not**.
    * Case insensitive.


> [mmbrs_roles showinfeed='yes']Content.[/mmbrs_roles]

* Attribute: showinfeed
    * Optional.
    * Shows content in the feed and ignores all other attributes when displaying in the feed. (If it is not being displayed in a feed, all of the attributes take effect.)
    * **showinfeed** only accepts **yes**.
    * Case insensitive.



### [mmbrs_capabilities]


> [mmbrs_capabilities]Content.[/mmbrs_capabilities]

* Attribute: none
    * Returns nothing.


> [mmbrs_capabilities can='delete_others_posts']Content.[/mmbrs_capabilities]

* Attribute: can
    * Required.
    * Shows content to users with the 'delete_others_posts' capability.
    * Accepts multiple. e.g. **can='delete_others_posts,edit_others_posts'**
    * Case sensitive.


> [mmbrs_capabilities is='not' can='delete_others_posts']Content.[/mmbrs_capabilities]

* Attribute: is
    * Optional.
    * Shows content to users who do not have the 'delete_others_posts' capability.
    * **is** only accepts **not**.
    * Case insensitive.


> [mmbrs_capabilities showinfeed='yes']Content.[/mmbrs_capabilities]

* Attribute: showinfeed
    * Optional.
    * Shows content in the feed and ignores all other attributes when displaying in the feed. (If it is not being displayed in a feed, all of the attributes take effect.)
    * **showinfeed** only accepts **yes**.
    * Case insensitive.



### [mmbrs_user_meta]


> [mmbrs_user_meta]Content.[/mmbrs_user_meta]

* Attribute: none
    * Returns nothing.


> [mmbrs_user_meta key='first_name'][/mmbrs_user_meta]

* Attribute: key (with no content)
    * Required. (**key** attribute is required. Content is not required.)
    * Shows a logged-in user's **first_name** meta value.
    * Does NOT accept multiple values when content is null/empty.
    * Case sensitive.


> [mmbrs_user_meta key='first_name']Content.[/mmbrs_user_meta]

* Attribute: key (with content)
    * Required. (**key** attribute is required. Content is not required.)
    * Shows content to logged-in user with the **first_name** meta value.
    * Accepts multiple as OR. e.g. **key='first_name,last_name'**
    * Case sensitive.


> [mmbrs_user_meta is='not' key='first_name']Content.[/mmbrs_user_meta]

* Attribute: is
    * Optional.
    * Shows content to users who do not have the **first_name** meta value.
    * **is** only accepts **not**.
    * Case insensitive.


> [mmbrs_user_meta is='not' key='first_name' equals='Ben']Content.[/mmbrs_user_meta]

* Attribute: equals
    * Required.
    * Shows content to users who do not have the **first_name** user meta of 'Ben'.
    * Case sensitive.


> [mmbrs_user_meta showinfeed='yes']Content.[/mmbrs_user_meta]

* Attribute: showinfeed
    * Optional.
    * Shows content in the feed and ignores all other attributes when displaying in the feed. (If it is not being displayed in a feed, all of the attributes take effect.)
    * **showinfeed** only accepts 'yes'.
    * Case insensitive.



#### Other Notes:

For every shortcode, except **[mmbrs_logged_in]**, if a user is not logged in, the shortcode will return nothing. The exception is when using **is='not'** in **[mmbrs_logged_in]**, as in **[mmbrs_logged_in is='not']Content.[/mmbrs_logged_in]**

By default, everything is hidden from feeds unless **showinfeed='yes'** is defined.



#### Motivation for this plugin:

I wrote this plugin to fill some holes, reduce some unnecessary functions, and fix some minor bugs I found in other plugins. I now use this, and only this, to control all shortcode-based content access on my membership-style websites.

The Members plugin checks roles by checking capabilities. You are not supposed to do that. The proper way of checking roles is included here. Notes on this:

* [WordPress current_user_can() Notes](http://codex.wordpress.org/Function_Reference/current_user_can#Notes)

Aside from that, I love and use the Members plugin and suggest you check it out for other role- and capability-based content access management.

I removed a bunch of stuff from the User Meta Shortcodes plugin that I didn't want. If you want the additional functionality, I recommend that plugin.

In the end, I wanted very clear control of in-post content via shortcodes. I wasn't finding anything that was totally stripped down. So I wrote this.



#### Inspiration and Credits:

* [Members Plugin](https://wordpress.org/plugins/members/)
* [User Meta Shortcodes Plugin](https://wordpress.org/plugins/user-meta-shortcodes/)
* [AppThemes User Role Function](http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/)



&nbsp;
== Installation ==

1. Upload **plugin-name.php** to the **/wp-content/plugins/** directory
1. Activate the plugin through the 'Plugins' menu in WordPress



== Frequently Asked Questions ==

None. Yet.



== Screenshots ==

None.



== Changelog ==

= 1.0 =
* 2014.10.01
* Released



== Upgrade Notice ==

None.


