# Listing Engine Frontend - Vacation Rental WordPress Plugin

A powerful WordPress plugin for building vacation rental and property listing websites. Features include property search with advanced filters, wishlists, reviews, reservation management, and role-based user dashboards.

This comprehensive documentation covers everything you need to know about the plugin—from installation and setup to advanced customization and troubleshooting. Whether you're a site owner looking to launch a vacation rental platform, a developer seeking to extend functionality, or a user wanting to understand the features, this guide has you covered. Find detailed information about plugin features in the sections below, including key capabilities, system requirements, step-by-step setup instructions, available shortcodes, user guides for different roles, developer documentation, phone validation details, database structure, common issues with solutions, and contact information for support.

---

## Table of Contents

1. [About This Plugin](#about-this-plugin)
2. [Who Should Use This Plugin](#who-should-use-this-plugin)
3. [Key Features](#key-features)
4. [System Requirements](#system-requirements)
5. [Setup Guide](#setup-guide)
6. [Shortcodes Reference](#shortcodes-reference)
7. [User Guide](#user-guide)
8. [Developer Guide](#developer-guide)
9. [Phone Validation System](#phone-validation-system)
10. [Database Tables](#database-tables)
11. [Troubleshooting](#troubleshooting)
12. [Support & Contact](#support--contact)
13. [Credits](#credits)

---

## About This Plugin

**Listing Engine Frontend** is a comprehensive WordPress plugin designed specifically for vacation rental and property listing websites. It provides a complete frontend solution that transforms any WordPress site into a fully functional property booking platform similar to Airbnb, Vrbo, or Booking.com.

The plugin seamlessly integrates property browsing, advanced search with filters, secure single-property pages, wishlist management, review systems, reservation workflows, and role-based dashboards into a cohesive platform. Whether you're building a small property management site or a large vacation rental marketplace, Listing Engine Frontend provides all the essential components needed to deliver a professional user experience.

The plugin connects with WordPress shortcodes, AJAX handlers, user roles, email notifications, and custom database tables to deliver a complete property listing experience without requiring any coding knowledge from site administrators.

---

## Who Should Use This Plugin

### Property Rental Platforms

If you're building a vacation rental marketplace, holiday home booking website, or property rental platform, this plugin provides all the frontend components needed to showcase properties and manage bookings. It handles the entire guest journey from property discovery to booking completion.

### Hosts and Property Managers

Individual property owners or property management companies can use this plugin to create a professional online presence for their rental properties. Hosts can manage their listings, accept reservations, and track earnings through an intuitive dashboard.

### Travel Agencies

Travel agencies looking to offer property accommodations alongside traditional travel services can integrate this plugin to provide a seamless booking experience for their clients.

### Real Estate Websites

Real estate websites focusing on holiday homes, timeshares, or rental properties can utilize the search and listing features to showcase available properties with detailed information and booking capabilities.

---

## Key Features

### 1. Advanced Property Search

The plugin offers a powerful search system with multiple filter options including location-based search with autocomplete suggestions, date range selection for availability, guest count filtering, property type selection, amenities filtering, and price range filtering. The search bar supports both desktop and mobile interfaces with an intuitive user experience.

### 2. Property Listings Display

Multiple display options are available including grid view for the main listing archive, carousel view for featured properties, and curated property sections that can be displayed on homepages or landing pages. Each listing displays essential property information including images, price, location, and key amenities.

### 3. Single Property Pages

Detailed property pages showcase all property information including image galleries, comprehensive property descriptions, amenities lists, location information, availability calendar, pricing details, and booking options. The pages are designed to provide guests with all the information they need to make a booking decision.

### 4. Wishlist System

Logged-in users can save their favorite properties to a wishlist for later reference. This feature helps users compare properties and return to their preferred choices without needing to search again.

### 5. Review and Rating System

Guests can submit reviews and ratings for properties they have stayed in. The review system includes rating components for different aspects of the property, written review text, and admin moderation capabilities for site administrators.

### 6. Reservation Management

A complete reservation workflow handles booking requests from guests, status tracking for reservations (pending, confirmed, cancelled, completed), reservation confirmation emails, and admin management capabilities for handling booking requests.

### 7. Role-Based Dashboards

The plugin provides specialized dashboards for different user roles. Travellers can view their bookings, manage their wishlist, update their profile, and track reservation status. Hosts can create and manage their listings, set availability, configure pricing, add payout details (bank account, UPI), and view their earnings. Administrators have access to manage all reservations, moderate reviews, configure plugin settings, and monitor site activity.

### 8. User Profile Management

Users can manage their personal information including profile pictures, contact details, phone numbers with country validation, and account settings. The phone validation system supports over 40 countries with automatic country detection and formatting.

### 9. Email Notifications

Automated email notifications are sent for various events including reservation requests, reservation confirmations, booking cancellations, review submissions, and password changes. The mail system is extensible and can be configured through WordPress settings.

### 10. Admin Backend

Site administrators have access to a comprehensive admin panel featuring database management tools for creating and repairing tables, reservation management for viewing and updating bookings, review moderation for approving or rejecting reviews, and shortcode reference guides.

---

## System Requirements

To ensure optimal performance and security, your server should meet the following requirements:

- **WordPress Version**: 5.0 or higher
- **PHP Version**: 7.4 or higher (PHP 8.0+ recommended)
- **MySQL Version**: 5.6 or higher
- **Web Server**: Apache or Nginx
- **Memory Limit**: Minimum 128MB (256MB recommended)
- **HTTPS**: Required for secure payment processing and user authentication

### Required Plugins

This plugin requires the companion **Admin Management** plugin for page routing and route mapping. This companion plugin manages the `wp_admin_management` database table which stores mappings for the listing archive page, property detail pages, and logout URL routing.

### Required Database Tables

The plugin manages its own tables for reservations, reviews, and wishlists. It also depends on an existing listing management system for core property data including property records, property images, location data, property types, amenities data, and blocked dates.

---

## Setup Guide

### Step 1: Activate the Plugin

Navigate to **WP Admin > Plugins** and click **Activate** on **Listing Engine Frontend**. Once activated, the **LEF** menu will appear in the admin sidebar providing access to all plugin features.

### Step 2: Install the Companion Routing Plugin

This plugin requires the companion Admin Management plugin for proper page routing. Install and activate it on the same WordPress site. The companion plugin handles URL routing for property detail pages and search results.

### Step 3: Create Required Pages

Create the following pages in **WP Admin > Pages > Add New**:

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

Navigate to **LEF > Database** in the admin panel and click **Create / Repair** for each required table. This will create the reservation, reviews, and wishlist tables needed for plugin functionality.

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

Standalone search bar for hero sections and homepage integration.

```text
[premium_search_bar]
```

### `[single_property_view]`

Property detail page. Automatically resolves the property from the `property_ref` URL parameter.

```text
[single_property_view]
```

### `[lef_my_profile]`

Logged-in user dashboard with role-based access and navigation.

```text
[lef_my_profile]
```

---

## User Guide

### For Site Owners

Site administrators can add listing pages using the shortcodes provided, manage reservations from **LEF > Manage Reservations**, create or repair plugin tables from **LEF > Database**, and view all shortcode references from **LEF > Dashboard**. The admin panel provides comprehensive controls for managing the entire rental platform.

### For Travellers

Travellers can browse and search properties using advanced filters including location, dates, guest count, and amenities. They can save favorites to their wishlist for later reference, submit reservation requests for available properties, leave reviews after completed stays, and manage their bookings and profile from the personalized dashboard.

### For Hosts

Hosts have access to create, edit, duplicate, and delete their property listings. They can manage listing status including draft and published states, store payout details including bank account information and UPI IDs for receiving earnings, and view all their listings from a centralized dashboard. The host dashboard provides analytics on booking performance and earnings.

---

## Developer Guide

### Folder Structure

| Path                            | Responsibility                                        |
|---------------------------------|-------------------------------------------------------|
| `listing-engine-frontend.php`   | Main plugin bootstrap and initialization             |
| `includes/shortcode-handler.php`| Shortcode registration and rendering                  |
| `includes/url-router.php`       | Secure property URL generation and property_ref decode |
| `includes/assets-loader.php`   | CSS/JS enqueueing and script management               |
| `includes/ajax-handler.php`     | AJAX controller for search, reservations, profile, reviews, and listings |
| `includes/helpers.php`          | Shared helper functions and utilities                 |
| `includes/db-schema.php`        | LEF table schema definitions                          |
| `includes/class-db-handler.php`| Database status and repair logic                      |
| `frontend/template/`            | Frontend shortcode templates                          |
| `backend/template/`             | Admin screen templates                                |
| `mails/`                        | Email templates                                       |
| `components/`                   | Shared CSS/JS/UI components                           |
| `images/`                       | Global placeholder images                             |

### Customizing the Plugin

Developers can customize various aspects of the plugin to match specific project requirements. Markup customization can be done in `frontend/template/` for frontend views or `backend/template/` for admin screens. Styles can be modified in `frontend/assets/css/`, `backend/assets/css/`, or `components/global/global.css`. JavaScript customization is possible in `frontend/assets/js/` or `backend/assets/js/`. Email templates can be customized in the `mails/` directory.

### Adding New Shortcodes

To add a new shortcode, first register it in `includes/shortcode-handler.php` using the add_shortcode function. Then create or update the corresponding template in `frontend/template/`. Add any required CSS/JS assets in `includes/assets-loader.php`. Finally, if the shortcode needs dynamic data, add AJAX handlers in `includes/ajax-handler.php`.

### Adding AJAX Endpoints

To create a new AJAX endpoint, first add the PHP action handler in `includes/ajax-handler.php`. Register the hook using `wp_ajax_{action}` for logged-in users and `wp_ajax_nopriv_{action}` for guest access. Localize any required data and nonces in `includes/assets-loader.php`. Finally, implement the corresponding JavaScript in the appropriate JS file.

---

## Phone Validation System

The plugin includes a comprehensive phone validation system built with JavaScript that works without external dependencies.

**File:** `components/global/phone-core.js`

**`PhoneCore` API:**

| Method              | Description                          |
|---------------------|--------------------------------------|
| `getCountries()`    | Returns all country data             |
| `findCountry(code)` | Find country by calling code         |
| `detectCountry(number)` | Auto-detect country from prefix  |
| `validate(number, country)` | Validate phone against rules |
| `format(number)`    | Format phone number display          |

The system supports over 40 countries across all major regions including North America, Europe, Asia, and Oceania.

**To add or modify countries:** Edit the `countries` array in `phone-core.js`:

```js
{ name: "Country", code: "+XX", flag: "🏳️", min: 10, max: 10, regex: /^\d{10}$/ }
```

**Server-side validation:** Handled via `wp_ajax_lef_edit_prof_validate_phone` in `includes/ajax-handler.php`.

---

## Database Tables

### Tables Managed by This Plugin

These tables are created and maintained via **LEF > Database**:

| Table                  | Purpose              |
|------------------------|----------------------|
| `wp_ls_reservation`    | Reservation requests and booking data |
| `wp_ls_reviews`        | Review records and ratings       |
| `wp_ls_wishlist`       | User wishlist entries            |

### Tables Required from Companion System

This plugin depends on an existing listing-management system for core property data:

| Table                     | Purpose               |
|---------------------------|-----------------------|
| `wp_ls_property`          | Property records      |
| `wp_ls_img`               | Property images       |
| `wp_ls_location`          | Location data         |
| `wp_ls_types`             | Property types        |
| `wp_ls_amenities`         | Amenities data        |
| `wp_ls_block_date`        | Blocked dates         |
| `wp_authme_otp_storage`   | OTP storage for authentication |

**Note:** If companion tables are missing, listing data, OTP verification, and profile features will not function properly.

---

## Troubleshooting

### Property detail page redirects away

Confirm that the companion Admin Management plugin is installed and active. Verify that the `Listing Single View` mapping exists in `wp_admin_management`. Check that the `property_ref` URL parameter is present and valid. Confirm the property ID exists in `wp_ls_property`.

### Search bar does not redirect correctly

Confirm the companion plugin is installed and active. Verify the `Listing Archive` mapping exists in `wp_admin_management`. Confirm the archive page contains the `[listing_engine_view]` shortcode.

### Country dropdown is empty on profile page

Verify `components/global/phone-core.js` is being loaded. Open the browser console and check that `window.PhoneCore` is defined. Look for JavaScript errors on the profile page.

### OTP email not received

Configure WordPress mail settings using an SMTP plugin if needed. Confirm the `wp_authme_otp_storage` table exists. Check server email delivery settings and spam filters.

### Wishlist / reviews / bookings fail

Create LEF tables from **LEF > Database**. Verify companion listing tables exist. Check AJAX nonce and ensure the user is logged in with appropriate permissions.

---

## Support & Contact

For any inquiries, bug reports, feature requests, or technical support regarding the Listing Engine Frontend plugin, please contact us:

- **Website**: https://arttechfuzion.com
- **Email**: contact@arttechfuzion.com
- **Support**: https://arttechfuzion.com/contact

Our team is dedicated to providing prompt support and addressing any issues you may encounter while using this plugin. When contacting support, please include your website URL, WordPress version, PHP version, and a detailed description of the issue or question.

---

## Credits

**Developed by [Art-Tech Fuzion](https://arttechfuzion.com)**

We specialize in creating innovative WordPress solutions for the vacation rental and property management industry. Our team is committed to delivering high-quality, feature-rich plugins that help property owners and rental managers succeed in the digital marketplace.

---

*Last Updated: May 2026*