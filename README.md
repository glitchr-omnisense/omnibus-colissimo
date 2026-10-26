# omnibus/colissimo

Colissimo (La Poste) for [omnisense/omnibus](https://github.com/glitchr-omnisense/omnibus).

For now the gateway prices parcels from configuration only; labels, tracking and relay points (Colissimo's web services) are still to be wired.

```php
$gateway = (new ColissimoGatewayFactory())->create($options);   // the options below
```

No framework needed, and no HTTP client: the package requires `omnisense/omnibus` alone and calls
nothing. In a Symfony application, the same through the bundle's configuration:

```yaml
omnibus:
    gateways:
        colissimo:
            factory: colissimo
            options:
                rates: [...]   # Omnibus\Action\ConfiguredRatingAction
```

## License

MIT since 2026-10-10; earlier versions remain published under LGPL-3.0-or-later.
