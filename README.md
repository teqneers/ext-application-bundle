# ext-application-bundle
A Symfony 2 bundle to integrate Sencha Ext JS into a Symfony 2 application

[![Build Status](https://travis-ci.org/teqneers/ext-application-bundle.svg?branch=master)](https://travis-ci.org/teqneers/ext-application-bundle)
[![Code Coverage](https://scrutinizer-ci.com/g/teqneers/ext-application-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/teqneers/ext-application-bundle/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/teqneers/ext-application-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/teqneers/ext-application-bundle/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/55b4ba49643533001c000526/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55b4ba49643533001c000526)

## Installation

You can install this bundle using composer

    composer require teqneers/ext-application-bundle

or add the package to your composer.json file directly.

After you have installed the package, you just need to add the bundle to your AppKernel.php file:

```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new TQ\Bundle\ExtJSApplicationBundle\TQExtJSApplicationBundle(),
    // ...
);
```

## Configuration

The *ext-application-bundle* requires some initial configuration so that it can find the correct files.

    # Default configuration for extension with alias: "tq_ext_js_application"
    tq_ext_js_application:
        workspace_path:          '%kernel.root_dir%/../workspace'
        relative_wWorkspace_url: ../workspace
        web_path:                '%kernel.root_dir%/../web'
        relative_web_url:        /
        builds:                  # Required
            # Prototype
            name:
                development_base:        ~ # Required
                production_base:         ~ # Required
                development_manifest:    manifest.json
                development_microloader: bootstrap.js
                development_appcache:    null
                production_manifest:     bootstrap.json
                production_microloader:  bootstrap.js
                production_appcache:     cache.appcache

Because the bundle provides its own controller to serve micro-loader, manifest and application cache manifest, you also
need to configure your routing to include the bundle routes at a given prefix. Edit your `app/config/routing.yml`:

    # ...
    ext_app:
        resource: "@TQExtJSApplicationBundle/Resources/config/routing.yml"
        prefix:/
    # ...

### Example

Given the following directory structure of a fictitious Symfony 2 application

    ./
    |-- app/            Application configuration and assets
    |-- src/            Application source code
    |-- web/            Public web-facing directory (document root)
    |   |-- app.php     Symfony 2 production front controller
    |   |-- app_dev.php Symfony 2 development front controller
    |   |-- app/        Root folder for Ext JS application production build
    |-- workspace/      The Ext JS application workspace
        |-- my-app/     The Ext JS application source folder


your configuration might look like this

    tq_ext_js_application:
        builds:
            default:
                development_base: my-app
                production_base:  app

In case your have configured your Ext JS application to produce mor than one build (e.g. desktop and tablet or
modern and classic toolkit), your directory structure could look like

    ./
    |-- app/
    |-- src/
    |-- web/
    |   |-- app.php
    |   |-- app_dev.php
    |   |-- desktop/    Root folder for Ext JS application production desktop build
    |   |-- tablet/     Root folder for Ext JS application production tablet build
    |-- workspace/      The Ext JS application workspace
        |-- my-app/     The Ext JS application source folder

In this case you can configure the bundle accordingly

    tq_ext_js_application:
        builds:
            desktop:
                development_base: my-app
                development_manifest: desktop.json
                production_base:  desktop
            tablet:
                development_base: my-app
                development_manifest: tablet.json
                production_base:  tablet

## Usage

Using the Twig extension provided by the bundle you can easily integrate the Ext JS application resources into your
application templates.

```twig
<!DOCTYPE HTML>
<html manifest="{{ extjsAppCachePath() }}" lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Welcome!</title>

    <script type="text/javascript">
        var Ext = Ext || {};
        Ext.manifest = '{{ extjsManifestPath()|e('js') }}';
    </script>
    <script id="microloader" type="text/javascript" src="{{ extjsBootstrapPath() }}"></script>
</head>
<body>
</body>
</html>
```

## License

The MIT License (MIT)

Copyright (c) 2015 TEQneers GmbH & Co. KG

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
