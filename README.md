# Credit Loan Calculator

A WordPress plugin that adds a loan calculator to Elementor, captures the resulting
enquiry through Contact Form 7, stores it in WordPress, and pushes the lead to
[Smaily](https://smaily.com) for email follow-up.

It is not a calculator widget on its own — it is the whole path from "visitor moves a
slider" to "lead sitting in the mailing list", which is usually where these projects
actually break.

---

## What it does

**1. Elementor widget**

Registers a `Calculator` widget (`clc_calculator`) under the Elementor panel with its
own controls: the field label, the submit button label, and the gap between elements.
Styles and scripts are enqueued through `get_style_depends()` / `get_script_depends()`,
so nothing loads on pages that don't use the widget.

**2. Custom Contact Form 7 form tags**

Rather than asking the site owner to hand-build a form, the plugin registers four
custom CF7 form tags:

| Tag | Purpose |
|---|---|
| `[clc_name]` / `[clc_name*]` | Name field with its own validation |
| `[clc_phone]` / `[clc_phone*]` | Phone field with format validation |
| `[clc_date]` / `[clc_date*]` | Date field with range validation |
| `[clc_price]` | Loan amount, formatted for display |

Each one hooks into CF7's own validation pipeline
(`wpcf7_validate_clc_phone`, `wpcf7_validate_clc_name`, `wpcf7_validate_clc_date`
and their required variants), so errors appear inline exactly like native CF7 fields
instead of through a separate mechanism.

**3. Lead capture**

On `wpcf7_before_send_mail` the plugin creates a lead and does two things with it:

- **Saves it locally** as a `submission` custom post type, with a meta box on the edit
  screen and custom columns in the admin list, so the client can read enquiries without
  leaving WordPress and without depending on email delivery.
- **Sends it to Smaily** through the API client in
  `includes/classes/class-smaily-api-integration.php`, which talks to
  `https://{domain}.sendsmaily.net/api/` over `wp_remote_get()` / `wp_remote_post()`
  and creates the subscriber.

Storing the submission locally is deliberate: if the API call or the notification email
fails, the enquiry is still on the site rather than lost.

---

## Installation

1. Copy the plugin folder into `wp-content/plugins/` (or upload the zip through
   **Plugins → Add New → Upload Plugin**).
2. Activate **Credit Loan Calculator**.
3. Make sure **Elementor** and **Contact Form 7** are both active.

## Configuration

Settings live under the plugin's admin page.

**Smaily**

| Field | Value |
|---|---|
| Domain | Your Smaily subdomain — the `{domain}` in `{domain}.sendsmaily.net` |
| Username | Smaily API username |
| Password | Smaily API password |

**Loan settings**

| Field | Value |
|---|---|
| Registration Page URL | Where the visitor is sent after submitting |
| Contact Form ID | The ID of the CF7 form that carries the `clc_*` tags |

## Usage

1. Create a Contact Form 7 form and use the `clc_*` tags in it, for example:

   ```
   [clc_name* applicant-name]
   [clc_phone* applicant-phone]
   [clc_date* loan-date]
   [clc_price loan-amount]
   [submit "Apply"]
   ```

2. Put that form's ID into **Contact Form ID** in the plugin settings.
3. Drop the **Calculator** widget onto a page in Elementor and set its label,
   button label and gap.

Submissions then appear under **Submissions** in the WordPress admin, and in Smaily.

---

## Requirements

- WordPress 5.6+
- PHP 8.0+
- Elementor
- Contact Form 7
- A Smaily account with API access

## Structure

```
credit-loan-calculator.php        Plugin bootstrap
includes/
  class-autoloader.php            PSR-style class autoloading
  class-core.php                  Plugin core
  class-starter.php               Bootstrapping
  elementor/
    class-elementor-builder.php   Registers the widget with Elementor
    class-calculator.php          The Calculator widget
  classes/
    class-admin.php               Admin screens
    class-contact-form-field.php  Custom CF7 tags, validation, lead creation
    class-smaily-api-integration.php  Smaily API client
  post-types/
    class-submissions.php         `submission` CPT, meta box, admin columns
  functions/
    settings.php                  Settings page
    helpers.php                   Settings accessor
assets/                           CSS and JS
```

Coding standards are enforced with PHPCS (`phpcs.xml`).

## License

MIT — see [LICENSE](LICENSE).
