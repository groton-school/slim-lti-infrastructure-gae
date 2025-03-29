# groton-school/slim-lti-gae-shim

[![Latest Version](https://img.shields.io/packagist/v/groton-school/slim-lti-gae-shim.svg)](https://packagist.org/packages/groton-school/slim-lti-gae-shim)

```sh
composer create-project slim/slim-skeleton [my-app-name]
cd [my-app-name]
composer require groton-school/slim-lti-gae-shim php-di/php-di
cp vendor/groton-school/slim-gae-shim/.gcloudignore .
cp vendor/groton-school/slim-gae-shim/app.yaml .
cp -R vendor/groton-school/slim-gae-shim/bin .
cp vendor/groton-school/slim-gae-shim/package.json .
cp vendor/groton-school/slim-gae-shim/php.ini .
npm install
node bin/deploy.js
```
