---
title: Introduction
weight: 10
---

`crumbls/subscriptions-filament` is the Filament v5 admin UI for [`crumbls/subscriptions`](/documentation/subscriptions). One plugin registration, three resources appear under a "Subscriptions" navigation group, and you can manage plans, features, and subscriber subscriptions without writing any Filament code.

## What you get

- **Plans resource** -- full CRUD, with collapsible trial and grace sections, currency picker, and translatable name/description
- **Features resource** -- standalone features with reset cycle config and a count of plans using each
- **Subscriptions resource** -- read-only list with inline Cancel and Renew actions, status badges (Active / Trial / Grace / Canceled / Ended)
- **Plan -> Features relation manager** -- attach existing features with per-plan values, create new ones inline, detach without deleting
- **Plan -> Subscriptions relation manager** -- view all subscriptions on a plan, create new ones, delete
- **Drop-in subscriber relation manager** -- add a subscriptions tab to any resource whose model uses `HasPlanSubscriptions`

## Requirements

| Package | PHP | Laravel | Filament | `crumbls/subscriptions` |
|---|---|---|---|---|
| `2.x` | 8.3, 8.4 | 11.x, 12.x, 13.x | 5.x | ^2.0 |

## Relationship to the parent package

This package is UI only. All business logic, models, events, and middleware live in [`crumbls/subscriptions`](/documentation/subscriptions). The Filament resources are thin wrappers that resolve their model classes through `config('subscriptions.models')`, so any [extended model](/documentation/subscriptions/v2/advanced/extending-models) you've registered shows up here automatically.

You can use the parent package without this one. You cannot use this one without the parent package -- it's a hard dependency.

## What's next

- [Installation](/documentation/subscriptions-filament/v2/installation)
- [Resources](/documentation/subscriptions-filament/v2/resources)
- [Drop-in relation manager](/documentation/subscriptions-filament/v2/relation-manager)
