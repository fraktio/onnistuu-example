
# Deprecation notice

This example is for the older Visma Sign API v0.

We encourage you to look into the API v1:

https://sign.visma.net/api/docs/v1/

https://gitlab.com/vismasign/vismasign-api-examples/

# Visma Sign APIv0 integration example

API documentation available at https://allekirjoitus.visma.fi/download/visma_sign-api.pdf

## Features

- Generates a pdf file based on form input
- Obtains a signature based on the identifier given
- Return data just dumped

## This example is

- not to be used as a basis for production forms
- not a comprehensive look into Visma Sign api functionality
- not rigorously tested
- only tested on linux environments

Contact us if you encounter any issues or would like more information.

## Installation

1. Install composer, https://getcomposer.org/doc/00-intro.md
1. Run `composer install`
1. Copy config/config.dist.php to config/config.php and edit to match your environment
1. Serve the web/ directory. See docs/nginx-vhost.conf for an example.
1. Make the data/ directory writable by php

