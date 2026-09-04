# Install Nivon on WordPress

There are two ways to add your Nivon chatbot to a WordPress site. The plugin is the recommended one for most people. The manual snippet is there if you would rather not add another plugin.

Either way, you need two values from your Nivon dashboard: a publish key and a chatbot ID.

## Get your keys

1. Open the chatbot you want to install.
2. Go to the Connect to website tab.
3. Copy the publish key. It starts with `pk_live_`.
4. Copy the chatbot ID. It is the long string next to it.
5. Under Allowed domains, add the domain of your WordPress site, for example `example.com`.

The allowed domains step matters. It stops other sites from loading your chatbot on their pages, which means your own site has to be on the list too.

## Option 1: the WordPress plugin

Recommended. Takes about a minute and keeps working when you change themes.

### Install it

1. In your WordPress admin, go to Plugins > Add New > Upload Plugin.
2. Choose `nivon.zip` and click Install Now.
3. Click Activate Plugin.

If your host blocks uploads from the admin, extract the zip and copy the `nivon` folder into `wp-content/plugins/` over SFTP, then activate it from the Plugins screen.

### Connect it

1. Go to Settings > Nivon Options.
2. Paste your publish key into the Publish key field.
3. Paste your chatbot ID into the Chatbot ID field.
4. Click Save Changes.

Open your site in a new tab. The chat bubble appears in the corner within a second or two. If you do not see it, check the troubleshooting section below.

### Remove it

Deactivate and delete the plugin from the Plugins screen. Deleting it also removes the saved keys from your database.

## Option 2: paste the snippet yourself

Use this if you already have a code snippets plugin, or your site is managed by a developer who prefers to keep the tag in version control.

Copy the WordPress tab code from the Connect to website screen:

```php
// functions.php
function add_nivon_chatbot() {
  echo '<script src="https://assets.nivon.ai/agent/widget.min.js"
    data-publish-key="pk_live_your_key"
    data-chatbot-id="your_chatbot_id"
    defer></script>';
}
add_action('wp_footer', 'add_nivon_chatbot');
```

Add it to your child theme's `functions.php`, or to a snippets plugin such as WPCode.

One warning: if you put this in a parent theme's `functions.php`, the next theme update wipes it and your chatbot quietly disappears. Use a child theme or a snippets plugin.

## Check that it worked

1. Open your site in a private window, so you are not logged in.
2. Look for the chat bubble in the bottom corner.
3. Send a test message and confirm you get a reply.
4. In Nivon, open the Conversations tab. Your test should be there.

## Troubleshooting

**No bubble on the page**

Open your browser console with F12 and reload. If you see a message about a rejected domain, your site is not in the Allowed domains list in Nivon. Add it and reload.

**Still no bubble after saving keys**

Clear your caching plugin, then clear your CDN cache if you use Cloudflare or similar. Cached HTML from before the install will not contain the tag.

**The bubble shows for logged out visitors but not for you**

Some optimization plugins skip scripts for logged in users. Check the exclusion settings in your caching or script optimization plugin and allow `assets.nivon.ai`.

**The bubble appears twice**

You have the plugin installed and the manual snippet in place. Remove one of them.

**It worked, then stopped**

Two common causes. Your theme was updated and overwrote a snippet in `functions.php`, or your publish key was rotated in Nivon. Check the key in Settings > Nivon Options against the current one in your dashboard.

## Compatibility notes

The widget loads with `defer` in the footer, so it does not block your page from rendering and does not affect your Core Web Vitals score in any meaningful way.

It works with page caching, WooCommerce, Elementor, Divi, and multisite. On multisite, each site keeps its own keys, so you can point different subsites at different chatbots.

## Frequently asked questions

**Is the publish key a secret?**

No. It is meant to be visible in your page source, the same as a Google Analytics ID. Allowed domains is what protects it. Do not confuse it with a secret API key, which never belongs in front end code.

**Can I hide the chatbot on certain pages?**

Not from the plugin settings in this version. If you need it now, exclude pages in your caching or scripts plugin, or use the manual snippet inside a conditional such as `if ( ! is_page( 'checkout' ) )`.

**Can I run two chatbots on one site?**

Not with the plugin. It stores one key and one ID. Use the manual snippet if you need conditional loading of different bots.

**Do I need to reinstall when I retrain the chatbot?**

No. Training happens in Nivon and takes effect right away. The keys stay the same.

## Get help

Email support@nivon.ai with your site URL and chatbot ID. If the widget is not appearing, a screenshot of your browser console helps us answer on the first reply.
