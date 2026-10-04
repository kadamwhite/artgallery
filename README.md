ArtGallery
========

ArtGallery is a artist's portfolio management plugin, providing tools for artists to catalog their works using the WordPress backend and editor blocks to configure and display that artwork on the frontend.

* **Contributors:** kadamwhite
* **Tags:** art, media, portfolio, archive
* **Requires at least:** 6.0
* **Requires PHP:** 8.2
* **License:** GPLv2 or later or Artistic License 2.0
* **License URI:** http://www.gnu.org/licenses/gpl-2.0.html

## Requirements

Scripts and styles are enqueued through [humanmade/asset-loader](https://github.com/humanmade/asset-loader), which must be loaded before this plugin. Both the 0.x (`enqueue_asset`) and 1.x (`enqueue_manifest_asset`) APIs are supported.

## Development

```
npm install
npm run build        # production build to build/
npm start            # watch mode
npm test             # Vitest unit tests
npm run lint:js
composer install
composer phpcs
```

The build uses `@wordpress/scripts` with a small `webpack.config.js` extension that keeps two entries (`editor`, `frontend`) and writes `build/production-asset-manifest.json` for asset-loader. Built files are not committed to `main`.

## Local Environment

[wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) runs a containerized WordPress instance at [localhost:3047](http://localhost:3047) with this plugin active. Log in with `admin` / `password`. Run `npm run build` (or `npm start`) first so the plugin has assets to load.

Command | Purpose
---- | ----
`npm run env:start` | Start the environment
`npm run env:stop` | Stop the environment
`npm run env:cli -- wp ...` | Run WP-CLI commands in the environment
`npm run env:logs` | Tail the PHP error log<sup>&ddagger;</sup>
`npm run env:db` | Open the database in the mysql command line
`npm run env:destroy` | Destroy the environment, including its database

<sup>&ddagger;</sup> GET/OPTIONS/HEAD/POST/PUT access log entries are filtered out.

## Release process

1. Bump the version in `plugin.php` (both the `Version:` header and `ARTGALLERY_VERSION`), add a changelog entry below, and merge to `main`.
2. Every merge to `main` runs the "Build to release branch" workflow, which merges `main` into `release`, runs the build, and commits `build/` there. A project may track `dev-release` to always get the latest built code.
3. To cut a versioned release, run the "Tag and Release" workflow from the Actions tab with the version (e.g. `v0.5.0`). It checks that the tag does not already exist and that the version matches `plugin.php`, tags the `release` branch, and creates a GitHub release with generated notes.

Composer consumers pin to the tag: `"kadamwhite/artgallery": "^0.5"`.

## Changelog

### 0.5.0

- Build with `@wordpress/scripts` (webpack 5, Dart Sass, Vitest) replacing the webpack 4 / node-sass toolchain.
- Built assets move to the `release` branch via CI; tags are cut from that branch.
- Script dependencies and versions come from the generated `.asset.php` files.
- Support asset-loader 1.x without deprecation notices, falling back to the 0.x API when needed.
- PHP 8.4 compatibility: explicit nullable parameter types.
- Require PHP 8.2.

### 0.4.5

- Properly enqueue CSS assets and fix error where manifest not generated.

## License

This plugin is free software; you can redistribute it and/or modify it under the terms of either:

- the [GNU General Public License](LICENSE.md#gnu-general-public-license) as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version, or
- the [Artistic License 2.0](LICENSE.md#artistic-license-20)

Make things for artists!
