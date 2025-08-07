# groton-school/slim-lti-infrastructure-gae

Shim to implement `packbackbooks/lti-1p3-tool` storage interfaces in Firestore

[![Latest Version](https://img.shields.io/packagist/v/groton-school/slim-lti-infrastructure-gae.svg)](https://packagist.org/packages/groton-school/slim-lti-infrastructure-gae)

## Install

```bash
composer require groton-school/slim-lti-infrastructure-gae
```

## Use

This implementation expects a default [slim-skeleton](https://github.com/slimphp/Slim-Skeleton#readme) (and Google App Engine configuration as by the `deploy` wizard in [`groton-school/slim-gae-shim`](https://github.com/groton-school/slim-gae-shim#readme)).

1. [Implement `SettingsInterface](https://github.com/groton-school/slim-skeleton/blob/0b32f964d753376ed2c2d9af4460e96342bbe919/src/Application/Settings/SettingsInterface.php#L11-L14)

2. [Define the dependency for `SettingsInterface`](https://github.com/groton-school/slim-skeleton/blob/0b32f964d753376ed2c2d9af4460e96342bbe919/app/dependencies.php#L32)

3. [Inject other implementation dependencies](https://github.com/groton-school/slim-skeleton/blob/0b32f964d753376ed2c2d9af4460e96342bbe919/app/dependencies.php#L23).

### groton-school/slim-skeleton@dev-lti/gae

[groton-school/slim-skeleton](https://github.com/groton-school/slim-skeleton/tree/lti/gae) is the canonical example of how this shim is meant to be used.
