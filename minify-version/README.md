# Listing Engine Frontend - Vacation Rental Websites wordpress plugin

| | |
|---|---|
| **Plugin Name** | Listing Engine Frontend |
| **Plugin Slug** | `LEF-(VR)` |
| **Version** | 1.0.0 |
| **Author** | Art-Tech Fuzion |
| **Author URI** | https://arttechfuzion.com |
| **Plugin URI** | https://arttechfuzion.com |
| **Description** | A WordPress plugin for vacation rental and property listing websites with search, filters, wishlists, reviews, reservations, and user dashboards. |
| **Requires at least** | WordPress 5.0 |
| **Requires PHP** | 7.4 |
| **License** | GPL v2 or later |

---

## Table of Contents

1. [About This Plugin](#about-this-plugin)
2. [Setup Guide](#setup-guide)
3. [Shortcodes Reference](#shortcodes-reference)
4. [User Guide](#user-guide)
5. [Developer Guide](#developer-guide)
6. [Companion Routing Plugin](#companion-routing-plugin)
7. [Database Tables](#database-tables)
8. [Troubleshooting](#troubleshooting)
9. [Credits](#credits)

---

## About This Plugin

**Listing Engine Frontend** is a WordPress plugin that powers the customer-facing experience for vacation rental and property listing websites. It connects property browsing, search with advanced filters, secure single-property pages, wishlist management, review system, reservation workflows, and role-based dashboards for travellers, hosts, and administrators into a single cohesive platform.

The plugin integrates with WordPress shortcodes, AJAX handlers, user roles, email notifications, and custom database tables to deliver a complete property listing experience.

---

## Setup Guide

### Step 1: Activate the Plugin

Go to **WP Admin > Plugins** and click **Activate** on **Listing Engine Frontend**. The **LEF** menu will appear in the admin sidebar.

### Step 2: Install the Companion Routing Plugin

This plugin requires the companion **Admin Management** plugin for page routing. Install and activate it on the same WordPress site.

### Step 3: Create Required Pages

Create these pages in **WP Admin > Pages > Add New**:

| Page Name       | Shortcode                  |
|-----------------|----------------------------|
| Listing Archive | `[listing_engine_view]`    |
| Property Detail | `[single_property_view]`   |
| My Profile      | `[lef_my_profile]`         |
| Home (optional) | `[premium_search_bar]`     |

### Step 4: Configure Route Mapping

Use the companion Admin Management plugin to add route records in the `wp_admin_management` table:

| Route Name            | Point To                    |
|-----------------------|-----------------------------|
| `Listing Archive`     | Listing Archive page ID     |
| `Listing Single View` | Property Detail page ID     |
| `Logout` (optional)   | Custom logout page ID       |

### Step 5: Create LEF Database Tables

Go to **LEF > Database** in the admin panel and click **Create / Repair** for each table.

---

## Shortcodes Reference

### `[listing_engine_view]`

Main property archive page with filtering, sorting, and wishlist support.

```text
[listing_engine_view]
```

**URL Filter Parameters:**

| Parameter   | Description            |
|-------------|------------------------|
| `location`  | Filter by location     |
| `address`   | Filter by address      |
| `type`      | Filter by property type|
| `guests`    | Filter by guest count  |
| `checkin`   | Check-in date          |
| `checkout`  | Check-out date         |
| `amenities` | Filter by amenities    |
| `min-price` | Minimum price          |
| `max-price` | Maximum price          |
| `sort`      | Sort order             |

### `[selected_list_view]`

Curated property sections for homepages or landing pages.

```text
[selected_list_view count="6" view="grid" location="Goa" type="Villa"]
```

| Attribute  | Values            | Default | Description          |
|------------|-------------------|---------|----------------------|
| `count`    | integer           | `9`     | Number of properties |
| `view`     | `grid`, `carousel`| `grid`  | Display layout       |
| `location` | string            | empty   | Filter by location   |
| `type`     | string            | empty   | Filter by type       |

### `[premium_search_bar]`

Standalone search bar for hero sections.

```text
[premium_search_bar]
```

### `[single_property_view]`

Property detail page. Automatically resolves the property from the `property_ref` URL parameter.

```text
[single_property_view]
```

### `[lef_my_profile]`

Logged-in user dashboard with role-based access.

```text
[lef_my_profile]
```

---

## User Guide

### For Site Owners

- Add listing pages using the shortcodes above.
- Manage reservations from **LEF > Manage Reserv**.
- Create or repair plugin tables from **LEF > Database**.
- View all shortcode references from **LEF > Dashboard**.

### For Travellers

- Browse and search properties by location, dates, guests, and amenities.
- Save favourites to wishlist.
- Submit reservation requests for available properties.
- Leave reviews after completed stays.
- Manage bookings and profile from the dashboard.

### For Hosts

- Create, edit, duplicate, and delete your listings.
- Manage listing status (draft, published, etc.).
- Store payout details (bank account, UPI) for receiving earnings.
- View and manage your listings from the dashboard.

---

## Developer Guide

### Folder Structure

| Path                            | Responsibility                                        |
|---------------------------------|-------------------------------------------------------|
| `listing-engine-frontend.php`   | Main plugin bootstrap                                 |
| `includes/shortcode-handler.php`| Shortcode registration and rendering                  |
| `includes/url-router.php`       | Secure property URL generation and `property_ref` decode |
| `includes/assets-loader.php`    | CSS/JS enqueueing                                     |
| `includes/ajax-handler.php`     | AJAX controller (search, reservations, profile, reviews, listings) |
| `includes/helpers.php`          | Shared helpers                                        |
| `includes/db-schema.php`        | LEF table schema definitions                          |
| `includes/class-db-handler.php` | DB status and repair logic                            |
| `frontend/template/`            | Frontend shortcode templates                          |
| `backend/template/`             | Admin screen templates                                |
| `mails/`                        | Email templates                                       |
| `components/`                   | Shared CSS/JS/UI components                           |
| `images/`                       | Global placeholder images                             |

### Customizing the Plugin

- **Markup:** `frontend/template/` or `backend/template/`
- **Styles:** `frontend/assets/css/`, `backend/assets/css/`, or `components/global/global.css`
- **JavaScript:** `frontend/assets/js/` or `backend/assets/js/`
- **Email Templates:** `mails/`

### Adding Shortcodes

1. Register in `includes/shortcode-handler.php`
2. Create/update template in `frontend/template/`
3. Enqueue assets in `includes/assets-loader.php`
4. Add AJAX handlers in `includes/ajax-handler.php` if needed

### Adding AJAX Endpoints

1. Add PHP action handler in `includes/ajax-handler.php`
2. Register `wp_ajax_{action}` hook (and `wp_ajax_nopriv_{action}` for guests)
3. Localize data/nonce in `includes/assets-loader.php`
4. Update the matching JS file

### Phone Validation System

Phone validation is handled via JavaScript — no external dependencies required.

**File:** `components/global/phone-core.js`

**`PhoneCore` API:**

| Method              | Description                          |
|---------------------|--------------------------------------|
| `getCountries()`    | Returns all country data             |
| `findCountry(code)` | Find country by calling code         |
| `detectCountry(number)` | Auto-detect country from prefix  |
| `validate(number, country)` | Validate phone against rules |
| `format(number)`    | Format phone number display          |

**Supported:** 40+ countries across all major regions.

**To add/modify countries:** Edit the `countries` array in `phone-core.js`:

```js
{ name: "Country", code: "+XX", flag: "🏳️", min: 10, max: 10, regex: /^\d{10}$/ }
```

**Server validation:** Handled via `wp_ajax_lef_edit_prof_validate_phone` in `includes/ajax-handler.php`.

---

## Companion Routing Plugin

This plugin depends on a companion plugin that manages the `wp_admin_management` database table for page-to-route mapping.

### What it does

The `wp_admin_management` table stores which WordPress pages handle:
- The listing archive page
- Individual property detail pages
- Logout URL routing

### Without the companion plugin

- Search bar and "See all" cards will not redirect correctly.
- Property detail pages will return `error_not_found`.
- Secure property URLs will fail to generate.

### How routing works

The plugin queries `wp_admin_management` to find the correct page IDs, then generates permalinks with obfuscated `property_ref` parameters (Base64-encoded listing IDs) for secure detail page access.

---

## Database Tables

### Tables Managed by This Plugin

Created/repaired via **LEF > Database**:

| Table                  | Purpose              |
|------------------------|----------------------|
| `wp_ls_reservation`    | Reservation requests |
| `wp_ls_reviews`        | Review records       |
| `wp_ls_wishlist`       | Wishlist entries     |

### Tables Required from Companion System

This plugin depends on an existing listing-management system for:

| Table                     | Purpose               |
|---------------------------|-----------------------|
| `wp_ls_property`          | Property records      |
| `wp_ls_img`               | Property images       |
| `wp_ls_location`          | Location data         |
| `wp_ls_types`             | Property types        |
| `wp_ls_amenities`         | Amenities data        |
| `wp_ls_block_date`        | Blocked dates         |
| `wp_authme_otp_storage`   | OTP storage           |

**Note:** If companion tables are missing, listing data, OTP, and profile features will not work.

---

## Troubleshooting

### Property detail page redirects away

- Confirm companion Admin Management plugin is installed and active.
- Verify `Listing Single View` mapping exists in `wp_admin_management`.
- Check the `property_ref` URL parameter is present and valid.
- Confirm the property ID exists in `wp_ls_property`.

### Search bar does not redirect correctly

- Confirm companion plugin is installed and active.
- Verify `Listing Archive` mapping exists in `wp_admin_management`.
- Confirm the archive page contains `[listing_engine_view]`.

### Country dropdown is empty on profile page

- Verify `components/global/phone-core.js` is being loaded.
- Open browser console and check `window.PhoneCore` is defined.
- Look for JavaScript errors on the profile page.

### OTP email not received

- Configure WordPress mail (`wp_mail()`) — test with an SMTP plugin if needed.
- Confirm `wp_authme_otp_storage` table exists.
- Check server email delivery settings.

### Wishlist / reviews / bookings fail

- Create LEF tables from **LEF > Database**.
- Verify companion listing tables exist.
- Check AJAX nonce and ensure the user is logged in.

---

## Credits

**Developed by [Art-Tech Fuzion](https://arttechfuzion.com)**
