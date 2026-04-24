# Crumbls Subscriptions Filament

A Filament v5 admin panel plugin for [crumbls/subscriptions](https://github.com/Crumbls/subscriptions). Manage plans, features, and subscriptions through a complete admin interface.

## Requirements

| Package | PHP | Laravel | Filament | `crumbls/subscriptions` |
|---|---|---|---|---|
| `2.x` | `8.3`, `8.4` | `11.x`, `12.x`, `13.x` | `5.x` | `^2.0` |

Upgrading from `1.x`? See [`CHANGELOG.md`](./CHANGELOG.md) for the breaking changes that came with the `crumbls/subscriptions` 2.0 bump, and the parent package's [`UPGRADING.md`](https://github.com/Crumbls/subscriptions/blob/main/UPGRADING.md) for the schema migration.

## Installation

```bash
composer require crumbls/subscriptions-filament
```

Register the plugin in your Filament panel provider:

```php
use Crumbls\SubscriptionsFilament\SubscriptionsPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            SubscriptionsPlugin::make(),
        ]);
}
```

That's it. The plugin registers all resources automatically.

> **Note:** If you've previously run `php artisan filament:cache-components`, you'll need to clear the cache after installing:
> ```bash
> php artisan filament:cache-components
> ```
> Or delete `bootstrap/cache/filament/panels/*.php`.

## Resources

All resources appear under a **Subscriptions** navigation group in your admin sidebar.

### Plans

Full CRUD for subscription plans.

**List view:**
- Name, price (formatted with currency), billing cycle, subscriber count, active status
- Filter by active/inactive
- Sortable by display order
- Search by name or slug

**Create/Edit form:**
- Plan details: name, description (translatable)
- Pricing: price, signup fee, currency (USD/EUR/GBP/CAD/AUD)
- Billing cycle: invoice period + interval
- Status: active toggle, subscriber limit, sort order
- Trial period: collapsible section with period + interval
- Grace period: collapsible section with period + interval

**Relation managers:**

- **Features** — Attach existing features to the plan with a per-plan `value` (the limit). Create new features inline. Detach features without deleting them. Edit or delete features.
- **Subscriptions** — View all subscriptions for this plan. Create new subscriptions. Delete subscriptions. Status badges (Active, Trial, Grace, Canceled, Ended).

### Features

Standalone CRUD for features. Features exist independently of plans and can be attached to multiple plans with different values.

**List view:**
- Name, slug, number of plans using this feature, reset cycle
- Sortable by display order

**Create/Edit form:**
- Name, description (translatable), slug (auto-generated)
- Reset cycle: period + interval (set to 0 for running counts like "users")
- Sort order

### Subscriptions

Read-only list with management actions.

**List view:**
- Subscriber type + ID, plan name, slug, start/end dates, status badge
- Status badges: Active (green), Trial (blue), Grace (warning), Canceled (red), Ended (gray)
- Filter by plan
- Search by slug

**Inline actions:**
- **Cancel** — Cancel immediately with confirmation dialog
- **Renew** — Renew an ended (non-canceled) subscription

**View page:**
- Full subscription details and feature usage breakdown

## Feature/Plan Workflow

The key concept: **features are defined once, values are set per-plan**.

1. **Create features** in the Features resource (e.g. "Users", "API Calls", "SSL")
2. **Edit a plan** → Features tab → **Attach** a feature
3. Set the **value** for this plan (e.g. "Users" → `10` on Basic, `50` on Pro)
4. The same feature can have different values across different plans

This is managed through the `plan_features` pivot table. The attach form prompts for `value` and `sort_order`.

## Translatable Fields

Plan and feature names/descriptions support multiple locales via [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable). The admin forms use the current application locale. To support multiple locales in the admin, consider a locale switcher or [filament-spatie-translatable](https://filamentphp.com/plugins/filament-spatie-translatable).

## Drop-in Relation Manager

The package provides a reusable `PlanSubscriptionsRelationManager` you can add to any resource whose model uses `HasPlanSubscriptions`:

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

This gives you a full subscriptions tab on the resource with:
- List of all subscriptions with status badges
- Create new subscription (with plan picker)
- Cancel / Renew / Delete inline actions
- Filter by plan
- View subscription details

Works with any model — User, Tenant, Team, Organization — as long as it uses the `HasPlanSubscriptions` trait.

## Customization

All resources resolve model classes from `config/subscriptions.php`. If you've swapped in custom models, the admin panel uses them automatically — no additional configuration needed.

### Extending resources

You can extend any resource class and register your own plugin:

```php
use Crumbls\SubscriptionsFilament\Resources\PlanResource\PlanResource;

class CustomPlanResource extends PlanResource
{
    // Add custom columns, filters, actions, etc.
}
```

## What's Included

| Resource | Actions | Relation Managers |
|---|---|---|
| **Plans** | List, Create, Edit, Delete | Features (attach/detach/create/edit/delete), Subscriptions (create/view/delete) |
| **Features** | List, Create, Edit, Delete | — |
| **Subscriptions** | List, View, Cancel, Renew | — |

## License

MIT
