# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Changelogs

### [0.1.5] - 2023-10-21

* Remove authentication from production environment

### [0.1.4] - 2023-10-21

* Fix composer.json

### [0.1.3] - 2023-10-21

* Remove /news from footer and add dynamic versions
* Add docker image with mysqldump to enable consistent dumping of the database
* Mobile and frontend refactoring
  * Increase font-size within work and news tiles; Image to object-fit cover
  * Fix js errors (missing elements); Fix smooth scrolling
  * Fix search design with floating labels; Fix 404 error page design; Alow empty about add within news/blogs -> use author information instead
  * Add popperjs configuration and setup
  * Add line numbers to highlight.js; Switch to bootstrap 5.2
  * Fix symfony toolbar http -> https issue
  * etc.
* Add table and diagram switch
* Add h3 to about block
* Translate contact form

### [0.1.2] - 2023-10-04

* Add syntax highlighting
* Add updated at value to blog detail

### [0.1.1] - 2023-10-03

* Search refactoring
* Add target _blank to links from markdown

### [0.1.0] - 2023-09-25

* Initial release
* First news skeleton and website
* Add Semantic Versioning
* Add PHPUnit 10 - PHP Testing Framework
  * Disable symfony warnings within tests (Codeception)
* Add rector symfony rules
* Add PHPMD and rules
  * Fixes to this rules
* Add PHPStan 1.10 - PHP Static Analysis Tool
  * Fix code up to PHPStan Level Max
* Add PHP Coding Standards Fixer
  * Fix PHPCS issues

## Add new version

```bash
# → Either change patch version
$ vendor/bin/version-manager --patch

# → Or change minor version
$ vendor/bin/version-manager --minor

# → Or change major version
$ vendor/bin/version-manager --major

# → Usually version changes are set in the main or master branch
$ git checkout master && git pull

# → Edit your CHANGELOG.md file
$ vi CHANGELOG.md

# → Commit your changes to your repo
$ git add CHANGELOG.md VERSION .env && git commit -m "Add version $(cat VERSION)" && git push

# → Tag your version
$ git tag -a "$(cat VERSION)" -m "Version $(cat VERSION)" && git push origin "$(cat VERSION)"
```
