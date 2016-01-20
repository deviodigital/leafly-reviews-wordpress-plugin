# Leafly Reviews WordPress Plugin

![Leafly Reviews for WordPress](http://robertdevore.com/wp-content/uploads/2015/10/leafly-reviews-stamp.jpg)

Easily display your dispensaries reviews on your WordPress powered website

I wrote up a more detailed release post if you're interested in finding out a bit more information than what's included in this README.

[View the write up here](http://robertdevore.com/leafly-reviews-wordpress-plugin/)

This plugin is also available from the official WordPress repository.

You can download it through your dashboard, or by going to the [Leafly Reviews plugin page](http://wordpress.org/plugins/leafly-reviews)

### Adding your APP ID and KEY

Once you install this plugin, you'll notice a new options page in your WordPress dashboard under the Settings section, titled "Leafly Reviews".

On this page, you'll be able to add in your APP ID and KEY, which is needed for the plugin to work.

Not sure where to get your APP ID and KEY?

You get them from the [Leafly Developer](https://developer.leafly.com) area, which lets you sign up for an account and create an app.

When you create the app, you'll be given a KEY and ID to use, which is what you'll need to copy over to this plugin's settings page.

**Caching built in**

Leafly gives their API users a limit of 25 hits per day for their **seed** account, or 60 hits per minute for their **bloom** account.

To help your dispensary utilize this plugin without needing to upgrade to bloom, and taking too many hits to your account, I've built in a cache that refreshes once per hour.

There's nothing that you need to do on your end in order to get this to work, it's baked right in to the plugin - pardon the pun :)

### Widget Options
After you install the Leafly Reviews WordPress plugin, you'll be able to add a custom widget to your website's sidebar (or anywhere else that widgets are enabled in your theme).

The widget is colored green, so you'll be able to easily spot it on your widgets page. Drag it into place where ever you'd like it to show, and fill in the options, which you can see to the left.

Here, you can add in your dispensaries URL slug and the amount of reviews you'd like to show (limit: 100). 

You can also select if you want to show the reviewer's avatar, the star rating, individual ratings for meds, service and atmosphere, if the user reviewer would recommend your dispensary and shop there again, and also show the reviewers comments.

### Shortcode Options

A secondary option built into the plugin to display your reviews from Leafly is the shortcode. Sometimes, it might be a better option to show reviews on a page of your website (for instance, the home page), so the shortcode will give you all of the flexibility you need.

Here is the basic shortcode:

`[leaflyreviews slug="denver-relief"]`

You will need to add in your slug, just like the widget options. The shortcode will default to showing 5 reviews, and all of the options given in the widget (avatar, star rating, detailed rating, recommendation, shop again and comments.

If you'd like to remove some of these options from showing, you can add the option to the shortcode with the value of *no*, like this:

`[leaflyreviews slug="denver-relief" limit="5" avatar="no" stars="no" ratings="no" recommend="no" shopagain="no" comments="no" viewall="no"]`

## Screenshots

![Widget display](http://robertdevore.com/wp-content/uploads/2015/10/leafly-reviews-wordpress-plugin-display.jpg) ![Backend widget options](http://robertdevore.com/wp-content/uploads/2015/10/leafly-reviews-wordpress-plugin-widget.jpg)

