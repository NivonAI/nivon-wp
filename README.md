# Nivon for WordPress

Embed your Nivon chatbot on any WordPress site. Paste two values, save, done. No theme files, no code.

## What it does

Adds a settings page under Settings > Nivon Options with two fields: your publish key and your chatbot ID. Once both are saved, the plugin loads the Nivon widget script in your site footer on every front end page.

The tag it produces is the same one shown in the Connect to website tab of your Nivon dashboard:

```html
<script src="https://assets.nivon.ai/agent/widget.min.js"
  data-publish-key="pk_live_..."
  data-chatbot-id="..."
  defer></script>
```

The difference is that the plugin survives theme updates and theme switches, which a snippet pasted into `functions.php` does not.

## Requirements

- WordPress 4.7 or higher
- PHP 7.0 or higher

## Install

From the WordPress admin:

1. Go to Plugins > Add New > Upload Plugin.
2. Upload `nivon.zip` and activate it.
3. Go to Settings > Nivon Options.
4. Paste your publish key and chatbot ID, then click Save Changes.

Or drop the `nivon` folder into `wp-content/plugins/` over SFTP and activate it from the Plugins screen.

## Where to find your keys

Open your chatbot in Nivon, go to the Connect to website tab, and copy the two values from the code sample. The publish key starts with `pk_live_`. The chatbot ID is the UUID next to it.

While you are there, add your WordPress domain to Allowed domains. If the domain is not on that list, the widget will not load, even with correct keys.

## Files

| File | Purpose |
| --- | --- |
| `nivon.php` | Plugin header, settings page, widget loading |
| `uninstall.php` | Deletes the `nivon_options` row when the plugin is deleted |
| `readme.txt` | wordpress.org plugin directory readme |

## How it loads the widget

The script is registered with `wp_enqueue_script` on `wp_enqueue_scripts`, set to print in the footer. The `data-publish-key`, `data-chatbot-id`, and `defer` attributes are added through the `script_loader_tag` filter. Nothing runs if either value is empty.

Because the output is plain static markup, page caching plugins and CDNs handle it without extra configuration.

## Data stored

One option row, `nivon_options`, holding the publish key and chatbot ID. Both are sanitized down to letters, numbers, dashes, and underscores before saving. Deleting the plugin removes the row.

The plugin does not collect analytics, does not phone home, and does not add anything to your database beyond that one row.

## Support

Questions and bug reports: support@nivon.ai

## License

GPL-2.0-or-later. See https://www.gnu.org/licenses/gpl-2.0.html
