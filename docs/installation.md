---
title: Installation
weight: 20
---

## Prerequisites

- A working Filament v5 panel
- `crumbls/subscriptions` already installed and migrated (see its [installation guide](/documentation/subscriptions/v2/installation))
- PHP 8.3+, Laravel 11+

## Install the package

```bash
composer require crumbls/subscriptions-filament
```

Composer pulls in `crumbls/subscriptions ^2.0` as a transitive dependency if it isn't already present.

## Register the plugin

In your panel provider (typically `app/Providers/Filament/AdminPanelProvider.php`):

```php
use Crumbls\SubscriptionsFilament\SubscriptionsPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            SubscriptionsPlugin::make(),
        ]);
}
```

That's the entire integration. Reload your panel -- a "Subscriptions" navigation group appears with Plans, Features, and Subscriptions.

## If you cache Filament components

If you've previously run `php artisan filament:cache-components`, clear the cache so Filament picks up the new resources:

```bash
php artisan filament:cache-components
```

Or delete `bootstrap/cache/filament/panels/*.php` and let it rebuild on next request.

## Verify

Open the panel in a browser. You should see:

1. A "Subscriptions" group in the sidebar
2. Plans, Features, and Subscriptions listed under it
3. Empty list states (assuming you haven't seeded any data yet)

If you don't see them, the most common causes are:

- Cached Filament components (clear the cache as above)
- The plugin registration is in the wrong panel provider (you might have multiple panels)
- A panel-level `->resources([...])` call that explicitly enumerates resources (overrides plugin registration)

## What's next

- [Resources](/documentation/subscriptions-filament/v2/resources)
- [Workflow: features and per-plan values](/documentation/subscriptions-filament/v2/workflow)
