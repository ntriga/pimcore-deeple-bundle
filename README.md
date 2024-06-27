# Pimcore Deepl Bundle

Use Deeple in Pimcore

## Features

- **Objects:**  Translate your objects' fields into multiple languages.

### Dependencies

| Release | Supported Pimcore Versions | Supported Symfony Versions | Maintained     | Branch |
|---------|----------------------------|----------------------------|----------------|--------|
| **1.x** | `11.0`                     | `6.2`                      | Feature Branch | master |

## Installation

You can install the package via composer:

```bash
composer require ntriga/pimcore-deepl
```

Add Bundle to `bundles.php`:

```php
return [
    Ntriga\PimcoreDeepl\PimcoreDeeplBundle::class => ['all' => true],
];
```

Add your Deepl API key to your `.env.local` file:

```dotenv
DEEPL_API_KEY=your-api-key
```

## Default configuration
Default configuration for the bundle can look like this:

```yaml
parameters:
    deepl_glossary_prefix: 'pimcore-deepl-prefix'
    deepl_source_lang: 'nl'
    deepl_target_langs:
        - 'fr'

    deepl_glossary:
        "default string":
            fr: "wanted translation"
```

## Further configuration
For more information about the setup, check [Setup](./docs/00_Setup.md)


## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits
- [All contributors](../../contributors)

## License
GNU General Public License version 3 (GPLv3). Please see [License File](./LICENSE.md) for more information.

