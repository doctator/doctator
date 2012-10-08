doctator - improve your documentation
=====================================

Warning: This project is at a very early stage.

## Requirements

* A webserver with PHP 5.3 support (e.g. Apache + `mod_php`) and `mongo` PHP extension
* Composer support
* MongoDB for storage

## Installation

Install composer (if not yet available)

    $ curl -s https://getcomposer.org/installer | php

Install dependencies

    $ php composer.phar install

Mount a webserver to `doctator/public` (depends on the webserver you use).

Browse to the new host for the doctator.org frontpage.

## How to include doctator in your project

Include the doctator script in your HTML markup at the location, where notes should be displayed:

```html
<script src="//doctator.local/s/37463245/all.js" id="doctator" data-subject="doctator-home"></script>
```
