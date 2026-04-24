---
title: Plans resource
weight: 10
---

Full CRUD for subscription plans, plus two relation managers (Features, Subscriptions).

## List view

Columns:

- **Name** -- displayed in the current locale (translatable)
- **Price** -- formatted with the plan's currency
- **Billing cycle** -- "1 month", "1 year", etc.
- **Subscribers** -- count of active subscriptions on this plan
- **Active** -- toggle column for `is_active`

Filters:

- **Active / Inactive**

Sorting:

- Default sort is by `sort_order`
- Click any column header to sort by it instead

Search:

- Name and slug

## Create / edit form

Organized into sections:

- **Plan details** -- name, description (both translatable), slug
- **Pricing** -- price, signup fee, currency picker (USD / EUR / GBP / CAD / AUD)
- **Billing cycle** -- invoice period (number) + interval (hour / day / week / month / year)
- **Status** -- active toggle, `active_subscribers_limit`, `sort_order`
- **Trial period** -- collapsible. `trial_period` + `trial_interval`
- **Grace period** -- collapsible. `grace_period` + `grace_interval`

Trial and grace start collapsed. Open them when you actually want a trial / grace window -- otherwise leave them collapsed and the values stay null.

## Features relation manager

Tab on the plan edit page. Manages the per-plan feature attachments via the `plan_features` pivot.

What you can do here:

- **Attach** an existing feature with a per-plan `value` (e.g. `users` -> `50`) and an optional `sort_order`
- **Create new feature inline** -- opens the same form as the standalone Features resource
- **Edit** the `value` for a feature already attached (changes the limit for this plan only)
- **Detach** without deleting -- removes from this plan, leaves the feature available for other plans
- **Delete** -- soft-deletes the underlying feature definition (use sparingly)

## Subscriptions relation manager

Tab on the plan edit page. Read-mostly view of every subscription on this plan.

What you can do:

- **View** subscriber, slug, dates, status badge
- **Create** a new subscription -- pick the subscriber type and the subscriber instance
- **Delete** a subscription
- **Status badges** -- Active (green), Trial (blue), Grace (warning), Canceled (red), Ended (gray)

Cancel and Renew live on the standalone Subscriptions resource; this tab is geared toward "see who's on this plan" rather than per-subscription management.

## What's not in this resource

- **Per-subscription cancel / renew actions** -- those live on the [Subscriptions resource](/documentation/subscriptions-filament/v2/resources/subscriptions)
- **Plan duplication** -- not built in; clone manually if you need a similar plan
- **Bulk price changes** -- use a one-off artisan command or tinker for this
