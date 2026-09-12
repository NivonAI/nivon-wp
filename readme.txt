=== Nivon ===
Contributors: nivonai
Tags: chatbot, ai chatbot, live chat, customer support, ai agent
Requires at least: 4.7
Tested up to: 7.1
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add your Nivon AI support agent to your WordPress site with a publish key and chatbot ID. No theme edits needed.

== Description ==

Nivon is an AI support agent for your website. You train it on your own content in the Nivon app, then use this plugin to put it on your WordPress site.

This plugin adds a settings page with two fields, publish key and chatbot ID. Once both are saved, it loads the Nivon widget script in your site footer using standard WordPress script APIs. Nothing loads until both values are filled in. Because the integration lives in the plugin and not your theme, it keeps working after theme updates and theme switches.

What the agent can do once it is live on your site:

* Answer questions from your documentation, website content, FAQs, and past conversations
* Capture leads from website conversations
* Hand off to your team when a conversation needs a human
* Respond in 95+ languages

You need a Nivon account and a configured chatbot to use this plugin. Sign up at https://app.nivon.ai/

== External Services ==

This plugin connects to Nivon, a third-party AI support service operated by Pimjo, to run the chat agent on your site. The plugin is only useful with this service, and the chat functionality cannot run locally.

**Service:** Nivon - https://nivon.ai/

**What the plugin loads:** When both the publish key and chatbot ID are saved, the plugin loads the Nivon widget script from https://assets.nivon.ai/agent/widget.min.js on the front end of your site. Your publish key and chatbot ID are passed to the script as data attributes so it can identify which chatbot to open. Nothing is loaded if either field is empty.

**What data is sent:** The widget script runs in your visitor's browser and communicates with Nivon's servers. When a visitor opens the chat and sends a message, the following goes to Nivon:

* The message text the visitor types
* Your publish key and chatbot ID, to identify the chatbot
* The page URL and standard browser request data, including IP address and user agent
* Any contact details the visitor chooses to enter, such as name or email, if your chatbot is set up to collect them

**When it is sent:** The widget script loads on front-end page views once the plugin is configured. Message and contact data is sent only when a visitor actively uses the chat.

The plugin itself does not send any data to Nivon from your server, does not collect analytics, and does not transmit anything about your site administrators.

Your chatbot's messages are routed to the AI model provider you select in the Nivon dashboard. This is described in the privacy policy below.

By using this plugin you agree to Nivon's terms and privacy policy:

* Privacy Policy: https://nivon.ai/privacy-policy
* Terms of Service: https://nivon.ai/terms-of-service
* Data Security: https://nivon.ai/data-security

== Installation ==

1. Install the plugin through the Plugins screen in WordPress, or upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the Plugins screen.
3. Go to Settings > Nivon Options.
4. Enter your publish key and chatbot ID from the Nivon dashboard.
5. Click Save Changes.

To find your keys, open your agent at https://app.nivon.ai/, go to the Integration tab, and copy both values from the script snippet. Add your WordPress site domain to the allowed domains list for that chatbot while you are there.

== Frequently Asked Questions ==

= Do I need a Nivon account? =

Yes. The plugin embeds a chatbot you create and train in the Nivon app. It does not work on its own.

= Where do I find my publish key and chatbot ID? =

In the Nivon dashboard, open your agent and go to the Integration tab. Both values are in the script snippet shown there.

= The widget is not showing up =

Check the allowed domains list for your chatbot in Nivon. If your site domain is not on that list, the widget will not load even when the keys are correct. Also confirm both fields are saved in Settings > Nivon Options, since the script does not load if either is empty.

= What does the plugin store on my site? =

One option row named `nivon_options` holding your publish key and chatbot ID. Both are sanitized before saving. Deleting the plugin removes the row.

= Does the plugin slow down my site? =

The widget script is loaded in the footer with the defer attribute, so it does not block page rendering.

== Changelog ==

= 1.0.0 =
* Initial release.
