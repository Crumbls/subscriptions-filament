---
title: Subscriptions resource
weight: 30
---

Read-mostly listing of every subscription across every subscriber, with inline cancel and renew actions.

## List view

Columns:

- **Subscriber** -- displayed as "type #id" (e.g. `App\Models\Tenant #42`)
- **Plan name**
- **Slug** -- the per-subscriber subscription slug (`main`, `addon-storage`, etc.)
- **Starts / Ends** -- the current billing period
- **Status badge** -- Active (green), Trial (blue), Grace (warning), Canceled (red), Ended (gray)

Filters:

- **Plan**

Search:

- Subscription slug

## Inline actions

- **Cancel** -- opens a confirmation dialog, then cancels immediately. Fires `SubscriptionCanceled` with `$immediate = true`.
- **Renew** -- only enabled on subscriptions that have ended (and weren't canceled). Advances the period by one billing cycle and clears the cancel state. Fires `SubscriptionRenewed`.

Cancel and Renew route through the model's `cancel()` / `renew()` methods, so any [extension](/documentation/subscriptions/v2/advanced/extending-models) you've added (e.g. cancelling the matching Stripe subscription) runs automatically.

## View page

Click into a subscription for the full detail view:

- All subscription metadata
- Current feature usage breakdown -- one row per attached feature with `used`, `value`, `valid_until`

This is read-only. Modifying usage is intentionally not exposed in the UI; do it through `recordFeatureUsage` / `reduceFeatureUsage` from your application code.

## What's not in this resource

- **Editing subscription fields** (price, plan, dates) -- intentional; the model's `changePlan()` and lifecycle methods are the right way to mutate state, not raw form edits
- **Bulk cancellation** -- if you need this, extend the resource and add a bulk action
- **Subscriber search by name** -- the polymorphic relationship makes this complex; search by slug instead

## Drop-in alternative for per-subscriber views

If you'd rather see subscriptions on a specific subscriber's page (e.g. inside `TenantResource`), use the [Drop-in relation manager](/documentation/subscriptions-filament/v2/relation-manager) instead.
