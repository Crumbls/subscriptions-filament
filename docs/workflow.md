---
title: Feature / plan workflow
weight: 40
---

The central pattern: **features are defined once, values are set per-plan**. This is what lets a single `users` feature be `5` on Basic, `50` on Pro, and `999999` on Enterprise -- one definition, three limits.

## The five-step flow

### 1. Create features in the Features resource

For each capability or quota that varies by plan:

- Name and slug (e.g. "Users" / `users`)
- Reset cycle if applicable

Don't worry about values yet -- features have no value of their own.

### 2. Open a plan

Navigate to the Plans resource and edit the plan you want to attach features to.

### 3. Switch to the Features tab

This is the relation manager for the `plan_features` pivot.

### 4. Attach a feature

Click **Attach**. The form prompts for:

- **Feature** -- pick from the existing features
- **Value** -- the per-plan limit (e.g. `5`, `50`, `999999` for quota; `true` / `false` for boolean features)
- **Sort order** -- where this feature appears on the plan's pricing card

### 5. Repeat for each plan

The same feature appears on multiple plans; the value can differ for each.

## Worked example

Imagine you're modeling Basic / Pro / Enterprise tiers.

**Features (created once):**

| Slug | Name | Reset cycle |
|---|---|---|
| `users` | Users | Never (running count) |
| `api-requests` | API Requests | Monthly |
| `ssl` | SSL | Never (boolean flag) |

**Per-plan attachments:**

| Plan | `users` | `api-requests` | `ssl` |
|---|---|---|---|
| Basic | 5 | 100 | false |
| Pro | 50 | 10000 | true |
| Enterprise | 999999 | 999999 | true |

In the admin: open Basic plan -> Features tab -> Attach (Users, value=5) -> Attach (API Requests, value=100) -> Attach (SSL, value=false). Repeat for Pro and Enterprise with their values.

In code, your app then checks:

```php
$sub->canUseFeature('users');           // bool
$sub->getFeatureValue('users');         // string -- the per-plan limit
$sub->recordFeatureUsage('users');      // increment current count
```

## Translatable fields

Plan and feature names / descriptions are stored as JSON via `spatie/laravel-translatable`. The admin forms write to the current application locale.

If you need a locale switcher in the admin, look at [filament-spatie-translatable](https://filamentphp.com/plugins/filament-spatie-translatable) -- it stacks cleanly on top of these resources.

## "Unlimited" features

There's no built-in "unlimited" sentinel. Pick a convention and stick with it:

- `999999` if you want code that does `$used < $value` to keep working naively
- `-1` if you want explicit "unlimited" handling in your app
- A separate `is_unlimited` column on a custom Plan / Feature subclass if you want it modeled explicitly

The package is intentionally agnostic here.
