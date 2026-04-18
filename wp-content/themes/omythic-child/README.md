# Omythic Child Theme
The Omythic Child Theme is the basis of all Omythic custom websites and must be used in conjunction with the [Omythic Framework Theme](https://github.com/makespaceweb/makespace-framework).

## How To Use

* If using the [Makespace WordPress Installer](https://github.com/makespaceweb/wordpress-installer), this child theme will automatically be added to your project.
* If using this theme manually, you will need to port over the `bower.json`, `gulpfile.js` and `package.json` files from the [Makespace WordPress Installer](https://github.com/makespaceweb/wordpress-installer) and change them to suit your project

## Code Compliance
Your code should conform to the [WordPress coding standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/) but also adhere to our theme building best practices. The following is not an exhaustive list:

* Each page layout should have its own *Page Template* and a corresponding SCSS file in the `src/scss/pages` folder. We started you with `page.php` and `page_contact.php` as example and guides.
* Each post type should have its own `single-{post_type}.php` file and a corresponding SCSS file in the `src/scss/pages` folder if they will be viewed as individual pages.
* If custom post types will have paginated archives, you should create `archive-{post_type}.php` (with a SCSS file in `src/scss/pages`).
* WordPress administrative users **must be able to edit all content and images** through the WordPress dashboard, and without having to write any HTML code. This does not apply to general font selection and colors, but does apply to all content.

## Version Control Best Practices

* You must include a copy of your database with each major or end-of-day commit and push
* The database name should be `[databasename].sql.gz` where [databasename] is the name of the database itself
* You should only edit the `.gitignore` if you introduce:
  * another type of package manager and need to ignore its output (eg, if you bring in Composer and want to ignore the vendor packages it installs)
  * a caching plugin and you want to ignore the cached files it creates (you should)

## Issues or Problems
If you run into any problems or issues with (or see opportunities for optimizations of) the code in this child theme, you should [post them in the issues section of this repo](https://github.com/makespaceweb/makespace-child/issues).

---

## Styling Conventions & Notes (Claude working notes)

### Base scale
`html { font-size: 10px }` — 1rem = 10px throughout. Figma px ÷ 10 = rem value.

### Header
- `.site-header` is **transparent** by default (overlays hero); gains `background: $brand-blue-dark` + box-shadow when `.scrolled` class is added by JS
- Logo: `height: $header-height` (12rem = 120px, matching the Figma logo height of 121px). Use `width: auto`.
- Nav links (`#large-nav-primary`): `color: $brand-gold`; hover to `#fff`
- Hamburger `#nav-toggle` bars: `background: $brand-gold`
- Pop Out Menu CTA (`#header-nav-popout`): gold-border outline button style
- `.inner` uses the theme `.container` width — no extra padding needed on `.inner`
- Off-canvas `#ocn`: background `$brand-blue-dark`; nav links `#fff`; dividers `rgba(#fff, 0.1)`

### Hero
- Overlay: always `linear-gradient(to bottom, rgba(0,0,0,0) 0%, #000 100%)` — **top to bottom**, transparent to black. NOT left-to-right.
- Content alignment: **centered** — `text-align: center` on `.hero-content`; `.hero-content-inner { max-width: 90rem; margin: 0 auto }`
- Hero sits at bottom of viewport: use `align-items: flex-end` on `.hero-content`
- Title: `$brand-gold`; subtitle/body: `rgba(#fff, 0.8)`

### Color variables (hslawky)
- `$brand-gold: #9B7839` — used for: Why Choose Us bg, Client Success Stories bg, nav links, accents
- `$brand-blue-dark: #23262F` — used for: Practice Areas bg, header scrolled state, OCN bg, dark card backgrounds
- `$brand-blue-grey: #4D5464` — used for: Case Results section bg
- `$brand-green-dark: #636C63` — used for: CTA "Ready to Fight" section bg
- `$brand-grey-light: #E1E1D6` — NOT used for section backgrounds; it's a light warm grey for UI elements

### Section background / text color pairings
| Section | Background | Text |
|---------|-----------|------|
| Why Choose Us | `$brand-gold` | `$brand-blue-dark` |
| Case Results | `$brand-blue-grey` | `#fff` |
| Client Success Stories | `$brand-gold` | `$brand-blue-dark` |
| Practice Areas | `$brand-blue-dark` | `#fff` |
| CTA (Ready to Fight) | `$brand-green-dark` | `#fff` |

When flipping a section from dark→light background, remember to update ALL nested color references: section-title, intro text, card text, icons, slider dots, etc.

### Figma inspection tips
- Figma layers are often disorganized (no dedicated header layer). Inspect visually at 100% zoom.
- To find a section background color: expand the section group in layers, click the first Rectangle child, read "Background colors" in the right panel (Inspect tab).
- Logo dimensions read from Code tab: `width: Xpx; height: Ypx`.
- Hero overlay rectangle is typically named `Rectangle 20` inside the Hero group.