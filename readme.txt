=== Sheet to Table Live Sync for Google Sheet ===
Contributors: codersaiful
Donate link: https://donate.stripe.com/4gw2bB2Pzdjd8mYfYZ
Tags: stoc sync with google sheet, google sheet sync, bulk edit product
Requires at least: 4.0.0
Tested up to: 6.6.1
Stable tag: 1.0.2
Requires PHP: 6.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sync Google Sheets live on WordPress. Lightning-fast, cached tables using shortcodes or the intuitive Dashboard interface.

== Description ==
Elevate your WordPress site's functionality with our innovative plugin, Sheet to Table Live Sync for Google Sheet. Seamlessly synchronize Google Sheets in real-time and enjoy the benefits of a cached feature that ensures lightning-fast table loading directly from your site – no constant syncing with Google servers required.

**Key Features:**

* **Live Google Sheet Sync:** Stay connected with your Google Sheets effortlessly. Sync in real-time to ensure your WordPress tables are always up-to-date.
* **Smart Caching for Instant Loading:** Optimize performance with our intelligent caching feature. Your tables load instantly from your site, eliminating the need for continuous syncing with Google servers. Experience unparalleled speed and efficiency.
* **Intuitive Shortcodes:** Easy implementation with customization options.
* **Dashboard Ease:** Create and customize tables effortlessly from your WordPress dashboard.

**Easy Shortcode Integration:**

Effortlessly display your Google Sheet data on your WordPress site using our simple shortcodes. Choose from two convenient methods

**Method 1: (Dashboard Deligh)** 

* Navigate to your WordPress Dashboard > Sheet to Table > Add New.
* Enter your Google Sheet URL in the 'Sheet URL' field.
* Customize settings and preview your table.
* Publish your post to generate a shortcode like [STWT_Sheet_Table id='2560' name='Table Title'].
* id: it's post id of that table.
* name: it's optional. To identify shortcode. No need for Display Sheet table.

**Method 2: (Shortcode Magic)** 

* Use the shortcode [STWT_Sheet_Table sheet_url='google_sheet_url' refresh='86400'].
* Replace 'google_sheet_url' with your Google Sheet URL.
* Set the refresh attribute to determine how frequently your table syncs with Google servers (in seconds).


**How to Get Started:**
* Install the "Sheet to Table Live Sync for Google Sheet" plugin.
* Connect your Google Sheets effortlessly.
* Choose your preferred shortcode integration method.
* Enjoy real-time updates and lightning-fast table loading on your WordPress site.

**Why Choose**

* **Efficiency:** No more delays in data updates. Our plugin ensures real-time synchronization with Google Sheets.
* **Speed:** Experience blazing-fast table loading directly from your site, thanks to our smart caching feature.
* **User-Friendly:** Intuitive shortcodes and an easy-to-navigate dashboard make integrating and customizing tables a breeze.

Ensure a smooth setup process by following these steps to connect your WordPress site with Google Sheets using our plugin.
Please follow, following steps:

* [Tutorial - Setup Guideline](https://codeastrology.com/sheet-to-table-live-sync-from-google-sheet/) - Checkout and follow step by step guideline. How can connect.
* [Link Google Sheet](https://docs.google.com/spreadsheets/) - Create new spreadsheet or use existing. In the plugin settings, find the section to link your Google Sheet. Provide the required Google Sheet URL and configure additional settings as needed.


**Credits**

We believe in giving credit where it's due. Our plugin utilizes the following third-party resources to enhance its functionality:

* DataTables: Effortlessly display dynamic data, enhancing your site's functionality, Thanks to [DataTables](https://datatables.net/)
* Fontello Icons: We express our gratitude to [Fontello](https://github.com/fontello/fontello#developers-api) for providing the icon font used in our plugin's user interface. Beautiful and customizable icons make the user experience more visually appealing.
* Google Sheet API: A big thank you to the [Google Sheets API](https://developers.google.com/sheets/api/reference/rest) for enabling seamless integration between our plugin and Google Sheets. This API plays a pivotal role in automating the synchronization of product data.
* [WordPress](https://wordpress.org/): We are indebted to the WordPress platform for providing the framework that powers our plugin. Its open-source nature and extensive community support make it an ideal environment for creating powerful and customizable solutions.

These entities have significantly contributed to the functionality, aesthetics, and overall success of our plugin. We are proud to acknowledge and appreciate their role in making our plugin a reality.

**Important Links**

* [Share your sheet PUBLIC](https://support.google.com/docs/answer/2494822?hl=en)
* [Google Sheets API Connector Overview](https://cloud.google.com/workflows/docs/reference/googleapis/sheets/Overview)

== Installation ==

1. Upload 'sheet-to-wp-table-for-google-sheet' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Connect with Google Sheet by Publish Share permission.

== Frequently Asked Questions ==

= Menu Location =

🔅 Dashboard -> Sheet to Table


= Do I need to sheet will be public? =

🔅 Yes, You have to share your table public with view permision only.

== Screenshots ==

1. Getting Start
2. Add new Table
3. Table on Frontend from Sheet

== Change log ==

= 1.0.1 =
* sheet url and gid empty issue has been solved. 

= 1.0.0 =
* Primary released.