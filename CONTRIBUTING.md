# Contributing

Thanks for your interest in contributing to `crumbls/subscriptions-filament`.

## Local setup

```bash
git clone https://github.com/Crumbls/subscriptions-filament.git
cd subscriptions-filament
composer install
```

To hack on this package alongside the underlying `crumbls/subscriptions` package, add a path repository to your test app's `composer.json`:

```json
{
    "repositories": [
        { "type": "path", "url": "../subscriptions" },
        { "type": "path", "url": "../subscriptions-filament" }
    ]
}
```

## Pull requests

- Open the PR against `main`.
- Breaking changes belong behind a version bump — note them in `CHANGELOG.md`.
- If the change affects what admins see, include a screenshot or screen recording.

## Reporting issues

Open a GitHub issue with:

- Affected version (`composer show crumbls/subscriptions-filament`).
- `crumbls/subscriptions` version.
- PHP, Laravel, and Filament versions.
- Steps to reproduce.
- Expected vs. actual behavior.

## Security issues

Please do not open public issues for security vulnerabilities. See `SECURITY.md` for reporting channels.
