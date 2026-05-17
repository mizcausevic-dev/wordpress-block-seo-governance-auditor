# Architecture

WordPress Block SEO Governance Auditor is a PHP application served through the built-in PHP development server with a lightweight router.

## Surfaces

- HTML routes provide the operator-facing audit experience
- JSON routes expose summary, page, schema, and verification data
- a plugin starter file gives the repo a clear WordPress-oriented entry point

## Data model

The sample model separates:

- page audit records
- schema opportunity records
- verification lanes

That keeps the app focused on block-level governance rather than generic reporting.

## Rendering

The UI is rendered server-side through shared view helpers and route-specific content sections. README proof assets are generated from real browser renders using headless Edge.
