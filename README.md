# Crumbls Subscriptions Filament

Filament v5 admin panel for [crumbls/subscriptions](https://github.com/Crumbls/subscriptions).

## Installation

```bash
composer require crumbls/subscriptions-filament
```

## Setup

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

## What's Included

### Plan Resource
- Full CRUD for plans (name, pricing, billing cycle, trial/grace periods)
- Features relation manager (add/edit/remove plan features inline)
- Active/inactive filter, subscriber count column
- Sortable by display order

### Subscription Resource
- List all subscriptions with status badges (Active, Trial, Grace, Canceled, Ended)
- Filter by plan
- Inline actions: Cancel, Renew
- View page with full subscription details and feature usage breakdown
- Header actions: Cancel, Reactivate, Renew

## Customization

Both resources use the model classes from your `config/subscriptions.php`, so custom models work automatically.

## License

MIT
