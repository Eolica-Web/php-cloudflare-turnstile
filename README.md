# Cloudflare Turnstile

Monorepo of the [Cloudflare Turnstile](https://developers.cloudflare.com/turnstile/) packages for PHP.

| Package                                                                      | Description                                     |
|------------------------------------------------------------------------------|-------------------------------------------------|
| [eolica/cloudflare-turnstile](packages/cloudflare-turnstile)                 | Framework-agnostic server-side token validation |
| [eolica/laravel-cloudflare-turnstile](packages/laravel-cloudflare-turnstile) | Laravel integration                             |

Each package is split into its own read-only repository, which is the one published on Packagist. Issues and pull requests go to this repository.

## Development

Install the dependencies of every package from the root:

```bash
composer install
```

The root `composer.json` installs the packages from `packages/` and holds the development tools, which run on all of them. See the [contributing guide](.github/CONTRIBUTING.md) for the available commands.

## Releasing

Every package is released with the same version, even when only one of them changed, and each one requires the others at that exact version (`self.version`).

To release a version, push a tag to this repository:

```bash
git tag v0.1.0
git push origin v0.1.0
```

The [split workflow](.github/workflows/split.yml) creates the same tag in the repository of every package, and Packagist picks it up from there.

## License

The MIT License (MIT). See the [license file](LICENSE) for more information.
