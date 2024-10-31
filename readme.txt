=== OneStory Video Interviews ===
Contributors: OneStoryInterviews
Tags: admin, shortcode, widget, crowdsource, video, onestory, interview, story, stories, social cause, social good, social change, community, video recorder, video player, video post, video gallery, video upload, embedded video, webcam, camera, documentary, vimeo, youtube
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 1.1.3
Header tagline: Promote OneStory.com Interviews using our shortcodes to collect videos and embed them into Posts or 
License:     GPL3
License URI: http://opensource.org/licenses/GPL-3.0

== Description ==
Help promote one or more Video Interviews from [OneStory](https://www.onestory.com) by collecting videos and/or embedding them into a Post or Page via [Wordpress shortcodes](https://en.support.wordpress.com/shortcodes/) generated and rendered by this plugin. Your visitors will be able to record videos and publish them to a OneStory Interview, embed a OneStory Interview video gallery, or a OneStory video player on any Post or Page. 

= Special Features =
The plugin includes three shortcodes for embedding OneStory components:

1. A OneStory video recorder
2. A OneStory video player
3. A OneStory Interview video gallery

As the site admin you may see a list of where your shortcodes are being used with links to quickly edit or view them. 

The shortcodes can be manually configured or generated using our shortcode generators in the admin side. Select the options for a shortcode, copy the shortcode generated, and paste it into your Post's or Page's content.

The OneStory Video Interviews plugin can generate and parse the following shortcodes:

`[onestory-share interviewslug='chosen-interview-slug', uielement='embed', align='centre']`
- *Embeds a video recorder for a specific OneStory Interview on a Post or Page*

`[onestory-share interviewslug='chosen-interview-slug', uielement='button', align='centre']`
- *Similarly embeds a button that opens the video recorder in a new window.*

`[onestory-gallery interviewslug='chosen-interview-slug', limit='6', align='centre', width='600', height='400']` 
- *Embeds a gallery of thumbnails for the videos from a specific OneStory Interview.*

`[onestory-player storyslug='chosen-story-slug', align='centre', width='600', height='400']`
- *Embeds a video player with a specific OneStory video loaded.*

Let us know if you need features like localization support for other languages and a shortcode option for embedding people's "hearted" stories as a gallery. Or if you have any issues or improvements email wordpress@onestory.com

== Installation ==

Extract onestory_plugin.zip to your /wp-content/plugins/ directory.
In the admin panel under Plugins activate OneStory Video Interviews.

Your WordPress site needs to be hosted on a secured protocol (https) when embedding our video player and gallery on a Post or Page. It may still work otherwise but it will throw an error on the Browser's Console.

Deactivating or uninstalling this plugin will not change the content of your Posts and Pages that include the OneStory Shortcodes. The results may be unpredicatable on those Posts and Pages.

Need help? Contact wordpress@onestory.com

== Frequently Asked Questions ==
We haven't received questions yet but here are some potential questions that may become frequently asked.

= Is this plugin free to use? =
Yes.

= Do I need a OneStory account? =
No.

= Does it work on all Browsers? =
This plugin has been tested on the latest versions of Chrome, Safari, Firefox, and IE9+.

== Screenshots ==

1. What's the quickest way to install the OneStory Interview Videos Plugin?
2. How to add a Story video player to a Post?
3. How to add a Share Your Story button and recorder to a Post?
4. How to add an Interview gallery to a Post?

== Changelog ==
= 1.0.0 =
* First release of the OneStory Interview Videos Plugin

= 1.0.1 =
* Minor style fixes in the admin and public sides. Simplified shortcode.

= 1.0.2 =
* Interviews and Stories RSS being queried with no limits.

= 1.0.3 =
* Fixed dynamic gallery height based on limit.

= 1.0.4 =
* Added ability to change the Share button's text and colors.

= 1.0.5 =
* Added height option and increased the limit for the video gallery.

= 1.0.6 = 
* Improved the look of the shortcode generator pages. Added width input to gallery shortcode.

= 1.0.7 =
* Fix: Share shortcode generator now only lists active Interviews.

= 1.0.8 =
* Fix: domain url.

= 1.0.9 =
* Fix: Added Interview slug to share button.

= 1.1.0 =
* Fix: Embedded gallery is now fluid.
* Feature: Added option to paginate large embedded galleries. 

= 1.1.1 =
* Improvement: Added height option to work with pagination.

= 1.1.2 =
* Handle invalid configuration of shortcodes.

= 1.1.3 =
* Fixed Video player embed, dimensions were incorrect.

== Upgrade Notice ==
Fixed Video Plyaer embed dimensions.

== Translations ==
* English - default

== Additional Info ==
[OneStory](https://www.onestory.com) is a Storytelling platform to crowdsource video interviews helping tell the story of your organization, your cause, your community or your family.

We empower people to collect and maintain a visual history for future generations. 

Find out more [about us](https://www.onestory.com/learn/about).
