---
title: Customization
weight: 60
---

The plugin is intentionally minimal -- one plugin registration, three resources, one relation manager. Customization happens by extending the package's classes and registering your own plugin or resources.

## Custom models

The Filament resources resolve model classes through `config('subscriptions.models')` -- the same config used by the parent package. If you've [extended a model](/documentation/subscriptions/v2/advanced/extending-models) in the parent package, the admin uses your subclass automatically. No additional configuration needed here.

## Extending a resource

Every resource class is extendable. Subclass it, override what you need, and replace the registration:

```php
use Crumbls\SubscriptionsFilament\Resources\PlanResource\PlanResource;
use Filament\Forms;

class MyPlanResource extends PlanResource
{
    public static function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                ...parent::form($form)->getComponents(),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured plan'),
            ]);
    }
}
```

Then register your version instead of using the plugin:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        // ... no SubscriptionsPlugin here ...
        ->resources([
            MyPlanResource::class,
            \Crumbls\SubscriptionsFilament\Resources\FeatureResource\FeatureResource::class,
            \Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\SubscriptionResource::class,
        ]);
}
```

Mix and match -- you can extend one resource and use the shipped versions of the others. Just don't register the plugin and your subclasses simultaneously, or you'll get duplicate-resource errors.

## Adding bulk actions

If you want bulk cancel, bulk renew, or a "send notice to subscribers" bulk action on the Subscriptions resource, the simplest path is a subclass:

```php
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\SubscriptionResource;
use Filament\Tables;

class CustomSubscriptionResource extends SubscriptionResource
{
    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->bulkActions([
                Tables\Actions\BulkAction::make('cancel-all')
                    ->action(fn ($records) => $records->each->cancel(immediately: true))
                    ->requiresConfirmation(),
            ]);
    }
}
```

## Adding a custom widget

Plug a dashboard widget that shows MRR or churn into your panel directly -- the plugin doesn't ship widgets, but the parent package's models give you the data:

```php
use Filament\Widgets\StatsOverviewWidget;
use Crumbls\Subscriptions\Models\PlanSubscription;

class SubscriptionStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active subscriptions', PlanSubscription::findActive()->count()),
            Stat::make('Ending in 7 days',     PlanSubscription::findActive()->findEndingPeriod(7)->count()),
        ];
    }
}
```

Register it on your panel's dashboard like any other widget.

## Locale switcher for translatable fields

The admin writes to the current application locale. To support multi-locale editing, install [filament-spatie-translatable](https://filamentphp.com/plugins/filament-spatie-translatable) and add its translatable trait to your subclassed resources. The shipped resources don't include it by default to avoid forcing the dependency.
