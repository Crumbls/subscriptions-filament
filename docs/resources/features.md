---
title: Features resource
weight: 20
---

Standalone CRUD for features. Features exist independently of plans -- create them once, attach them to multiple plans with different values via the [Plans resource](/documentation/subscriptions-filament/v2/resources/plans)'s Features tab.

## List view

Columns:

- **Name** -- in the current locale
- **Slug** -- the stable identifier you'll reference in code (`canUseFeature('users')`, `subscribed:pro` middleware, etc.)
- **Plans count** -- how many plans currently use this feature
- **Reset cycle** -- "Monthly", "Daily", "Never resets" derived from `resettable_period` + `resettable_interval`

Sorting:

- Default by `sort_order`

## Create / edit form

- **Name** -- translatable
- **Description** -- translatable, optional
- **Slug** -- auto-generated from name if left blank; editable on create, treat as immutable after first attach (changing it breaks every reference in code)
- **Reset cycle**
  - `resettable_period` (number, 0 = never resets)
  - `resettable_interval` (hour / day / week / month / year)
- **Sort order**

## When to set a reset cycle

| Pattern | `resettable_period` | `resettable_interval` |
|---|---|---|
| API request quota that refills monthly | 1 | month |
| Daily cap (transactional emails, exports) | 1 | day |
| Hard limit on current users / projects (no reset) | 0 | (any) |
| Quarterly | 3 | month |
| Annual | 1 | year |

For "current count" features (users, projects, integrations), use `0` for the period and reduce usage when the resource is removed.

## After creating a feature

The feature is created but not attached to anything. Go to the [Plans resource](/documentation/subscriptions-filament/v2/resources/plans), open a plan, switch to the Features tab, and **Attach** with a per-plan `value`. Repeat for each plan that should expose the feature.

## Deleting

Features use soft deletes. A deleted feature stays out of the resource list and reports `false` for `canUseFeature` everywhere it was attached. Restore by editing in the database directly or extending the resource to expose a "trashed" filter.
