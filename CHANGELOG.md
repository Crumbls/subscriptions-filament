# Changelog

## v2.1.0 — 2026-04-29

### Added

- Full translations layer. All user-facing labels in resources, forms, tables, pages, infolists, and relation managers route through `__('subscriptions-filament::subscriptions-filament.…')`. Ships English; publish via `php artisan vendor:publish --tag=subscriptions-filament-translations` to override or add locales.
- `SubscriptionsFilamentServiceProvider` now actually does work: loads the package translation namespace and registers the publish tag.
- Per-resource opt-out on the plugin: `SubscriptionsPlugin::make()->withoutPlans()`, `->withoutSubscriptions()`, `->withoutFeatures()`, and `->withoutResources(['plans', 'features'])`.
- Real Heroicon navigation icons on all three resources (`Plan`, `Feature`, `Subscription`); `getNavigationGroup()` is now translatable.
- `FeatureForm::components()` exposes the form components as an array so relation managers can compose with extra fields.

### Fixed

- `ViewSubscription` infolist used the Filament 3 namespace `Filament\Infolists\Components\Section`; in Filament 5 `Section` lives at `Filament\Schemas\Components\Section`.
- `FeaturesRelationManager` inline create form dropped the pivot `value` (`plan_features.value` was never captured). Pivot value is now in both create and attach forms, and rendered in the table.
- `PlansTable` `invoice_interval` column threw on rows with a null interval. Now renders `—`.
- `SubscriptionResource::form()` was a dead override returning the bare schema; removed (resource is index/view only).

### Changed

- Subscription status badges are now keyed by slug (`active`/`trial`/`grace`/`canceled`/`ended`) and rendered through the translation layer; consistent across `SubscriptionsTable`, `SubscriptionsRelationManager`, and `PlanSubscriptionsRelationManager`.
- `PlanSubscriptionsRelationManager` title moved from the static `$title` property to a translated `getTitle()` override.

## v2.0.0 — 2026-04-23

### Breaking

- `crumbls/subscriptions` constraint raised from `^1.0|dev-main` to `^2.0`. Consumers must update the parent package at the same time.
  - If you are upgrading `crumbls/subscriptions` from 1.x, follow the parent package's [`UPGRADING.md`](https://github.com/Crumbls/subscriptions/blob/main/UPGRADING.md) — it includes schema changes for the `plan_subscriptions` unique slug constraint and drops the unused `prorate_*` columns on `plans`.
- Minimum PHP raised from `^8.2` to `^8.3` to track `crumbls/subscriptions` 2.x.

### Fixed

- Removed `Gate::before` / `Gate::after` callbacks that unconditionally granted every ability — leftover development scaffolding that would have bypassed every policy in the host application.

### Added

- GitHub Actions workflow: PHP syntax + composer validate on push and PR.
- Dependabot config, `CONTRIBUTING.md`, `SECURITY.md`, issue and PR templates.

## v1.0.0 — 2026-02-21

Initial release against `crumbls/subscriptions` 1.x. Three resources (Plans, Features, Subscriptions), one reusable drop-in `PlanSubscriptionsRelationManager`.
