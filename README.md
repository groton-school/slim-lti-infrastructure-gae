# groton-school/slim-lti-infrastructure-gae

Shim to implement `packbackbooks/lti-1p3-tool` storage interfaces in Firestore

[![Latest Version](https://img.shields.io/packagist/v/groton-school/slim-lti-infrastructure-gae.svg)](https://packagist.org/packages/groton-school/slim-lti-infrastructure-gae)

## Install

```bash
composer require groton-school/slim-lti-infrastructure-gae
```

## Use

This implementation expects a default [slim-skeleton](https://github.com/slimphp/Slim-Skeleton#readme) (and Google App Engine configuration as by the `deploy` wizard in [`groton-school/slim-gae-shim`](https://github.com/groton-school/slim-gae-shim#readme)).

Inject dependencies in the skeleton's [`app/dependencies.php`](https://github.com/groton-school/slim-skeleton/blob/8ad518f1d4a70ce7b81e93165c8ae027574972ca/app/dependencies.php#L58-L59).

### groton-school/slim-skeleton@dev-lti/gae

[groton-school/slim-skeleton](https://github.com/groton-school/slim-skeleton/tree/lti/gae) is the canonical example of how this shim is meant to be used.
