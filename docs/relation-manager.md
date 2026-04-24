---
title: Drop-in relation manager
weight: 50
---

The package ships a reusable `PlanSubscriptionsRelationManager` you can add to any Filament resource whose model uses `HasPlanSubscriptions`. It gives you a Subscriptions tab on the resource's edit page with create / cancel / renew / delete inline.

## Add it to a resource

```php
use Crumbls\SubscriptionsFilament\RelationManagers\PlanSubscriptionsRelationManager;

class TenantResource extends Resource
{
    public static function getRelations(): array
    {
        return [
            PlanSubscriptionsRelationManager::class,
        ];
    }
}
```

That's the whole integration. Open any tenant in the admin, switch to the Subscriptions tab, and you'll see every plan subscription that tenant holds.

## What you get

- **List** -- all subscriptions for this subscriber, with status badges (Active / Trial / Grace / Canceled / Ended)
- **Filter by plan**
- **Create** -- a new subscription with a plan picker
- **Cancel** -- inline action, immediate cancellation, fires `SubscriptionCanceled`
- **Renew** -- inline action on ended subscriptions
- **Delete** -- soft-delete the subscription
- **View** -- click through to subscription detail

## Works on any subscriber model

The relation manager is fully polymorphic. As long as the resource's model uses the `HasPlanSubscriptions` trait from `crumbls/subscriptions`, the relation manager works:

```php
use Crumbls\Subscriptions\Traits\HasPlanSubscriptions;

class Tenant extends Model
{
    use HasPlanSubscriptions;
}

class User extends Model
{
    use HasPlanSubscriptions;
}

class Team extends Model
{
    use HasPlanSubscriptions;
}
```

Each of `TenantResource`, `UserResource`, `TeamResource` can include `PlanSubscriptionsRelationManager::class` and they'll all work without modification.

## When to use this vs. the standalone Subscriptions resource

| Use case | Pick |
|---|---|
| "Show me everyone's subscriptions" | Standalone Subscriptions resource |
| "Show me this tenant's subscriptions on their tenant detail page" | Drop-in relation manager |
| "Both" | Both -- they don't conflict |

The drop-in relation manager is the right surface for ops / support work where you're looking at one customer at a time. The standalone resource is the right surface for cross-customer reporting and bulk views.

## Customization

If you need to change columns, filters, or actions:

```php
use Crumbls\SubscriptionsFilament\RelationManagers\PlanSubscriptionsRelationManager;

class CustomPlanSubscriptionsRelationManager extends PlanSubscriptionsRelationManager
{
    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                // your custom actions
            ]);
    }
}
```

Then register your subclass instead of the base in your resource's `getRelations()`.
