# Changelog

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
