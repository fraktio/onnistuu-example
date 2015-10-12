
# onnistuu.fi integration example

API documentation available at https://esittely.onnistuu.fi/api-dokumentaatio/

## Features

- Generates a pdf file based on form input
- Obtains a signature based on the identifier given
- Return data just dumped

## This example is

- not to be used as a basis for production forms
- not a comprehensive look into onnistuu.fi api functionality
- not rigorously tested
- only tested on linux environments

Contact us if you encounter any issues or would like more information.

## Installation

1. Install composer, https://getcomposer.org/doc/00-intro.md
1. Run `composer install`
1. Copy config/config.dist.php to config/config.php and edit to match your environment
1. Serve the web/ directory. See docs/nginx-vhost.conf for an example.
1. Make the data/ directory writable by php

