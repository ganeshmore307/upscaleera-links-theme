# UpscaleEra Links Theme

Premium mobile-first WordPress links microsite for UpscaleEra.

## Parent theme

This is a **child theme of Noryx**.

Install and keep the parent theme folder named:

`noryx`

Then install/activate this child theme.

## Editable content

After activation, go to:

**WordPress Admin → Appearance → Customize → UpscaleEra Links**

You can edit:

- Logo
- Agency label
- Main heading
- Intro text
- Primary CTA text + URL
- Website link
- Instagram link
- WhatsApp link
- LinkedIn link
- Facebook link
- Service names
- Bottom CTA
- Footer text
- Brand colors

## Theme files

- `style.css` — child theme definition
- `functions.php` — assets, icons and helpers
- `front-page.php` — premium link-page layout
- `inc/customizer.php` — editable WordPress settings
- `assets/css/main.css` — responsive design
- `assets/js/main.js` — subtle animations

## Deployment plan

The repository will later deploy to Hostinger through GitHub Actions + FTP.

Recommended destination:

`/wp-content/themes/upscaleera-links-theme/`

Do not store FTP passwords in repository files. They will be added as GitHub Actions Secrets.
