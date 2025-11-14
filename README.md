# WDD Marketing Dynamics

**WDD Marketing Dynamics** is a lightweight but powerful WordPress plugin that tracks user behavior and uses an FCL (Forms Computed Language) rule engine to decide whether to display a **newsletter modal** or a **discount banner**.  
It enables fully dynamic, condition-based marketing automation.

---

##  Features

###  Behavior Tracking

Tracks:
- Time on page
- Number of clicks
- Number of visits (via cookie)
- Device type (mobile/desktop)
- User country (via IP lookup)

###  FCL Decision Engine

Use FCL rules to decide what to show based on user behavior.

Available variables:

```php
$time_on_page
$clicks
$visits
$is_mobile
$country
```

Return values:

- `show_newsletter_modal`
- `show_discount_banner`
- `none`

The frontend evaluates rules **every 5 seconds**.

###  Newsletter Modal

- Customizable title & text
- Built-in email submission form
- Submissions stored in custom DB table (`wp_ma_subscribers`)
- TTL-based control (e.g., show once every X days)

###  Discount Banner

- Custom banner text & link
- Auto-closes & respects TTL
- Mobile-friendly responsive layout

###  Admin Interface

Inside **Dashboard → WDD Dynamics**, you can:
- Set FCL rules
- Enable/disable modal or banner
- Customize texts and links
- View latest newsletter subscribers

---

##  Installation

1. Upload the plugin to `wp-content/plugins/wdd-marketing-dynamics`
2. Activate the plugin from **Plugins → Installed Plugins**
3. Go to **WDD Dynamics** in the admin menu
4. Configure FCL rules, modal, and banner settings

---

##  Example FCL Rules

### Show modal after 10 seconds:
```php
if ($time_on_page < 10) {
  $return = 'show_newsletter_modal';
} else {
  $return = 'none';
}
```

### Show banner after 5 clicks:
```php
if ($clicks > 5) {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}
```

### Show banner after 3 visits:
```php
if ($visits > 3) {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}
```

### Show modal only on mobile:
```php
if ($is_mobile == true) {
  $return = 'show_newsletter_modal';
} else {
  $return = 'none';
}
```

### Show banner only for visitors from Croatia:
```php
if ($country == "HR") {
  $return = 'show_discount_banner';
} else {
  $return = 'none';
}
```

---

##  Database

On activation, the plugin creates:

```
wp_ma_subscribers
```

Structure:
- `id` (BIGINT, auto-increment)
- `email` (VARCHAR)
- `created_at` (DATETIME)

---

##  REST API Endpoints

All API routes are prefixed with:  
`/wp-json/ma/v1`

### `POST /track`
Stores user metrics.

### `POST /evaluate`
Evaluates FCL rules and returns a decision.

### `POST /subscribe`
Stores a subscriber email in the database.

---

##  Frontend Behavior

- Tracks user behavior (clicks, time on page, visits)
- Sends data every 5 seconds
- Evaluates FCL logic
- Shows modal/banner only if:
  - FCL rule allows it
  - Feature is enabled
  - TTL has expired

CSS is loaded from `assets/css/frontend.css`.

---

##  Privacy & Cookies

The plugin uses:
- A **metrics cookie** for behavioral data
- A **visits cookie** for tracking visits
- **LocalStorage** for TTL control

No data is sent to external servers (except for country lookup via IP).

---

##  Requirements

- WordPress 5.0+
- PHP 7.4+
- REST API enabled
- `wp_remote_get()` must be allowed (for country detection)

---

##  License

Licensed under **GPLv2 or later**.
