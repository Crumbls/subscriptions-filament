---
title: Resources
weight: 30
---

Three Filament resources, all under a "Subscriptions" navigation group.

- [Plans](/documentation/subscriptions-filament/v2/resources/plans) -- full CRUD with relation managers for features and subscriptions
- [Features](/documentation/subscriptions-filament/v2/resources/features) -- standalone CRUD; features attach to plans elsewhere
- [Subscriptions](/documentation/subscriptions-filament/v2/resources/subscriptions) -- read-only list with inline Cancel / Renew actions

All three resolve model classes via `config('subscriptions.models')` -- if you've registered [extended models](/documentation/subscriptions/v2/advanced/extending-models) in the parent package, the admin uses them automatically.
