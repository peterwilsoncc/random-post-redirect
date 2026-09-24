# Random Post Redirect

Plugin land proof of concept for [WP #64498](https://core.trac.wordpress.org/ticket/64498).

## Implemented

* `?random` Query variable
* Redirects using the main `WP_Query`
* Redirects on  the`send_headers` hook to reduce database queries

## Todo

* Decide whether `parse_request` or `pre_get_posts` should be used for setting query variables
* Proper rewrite rule, either `/random/suffix` or `/prefix/random` -- Jorbin has suggested the former -- for blog, term, post type archives
* Decide if same is needed for date archives
* Tests beyond just the plugin meta data tests.

## Known issues

* Currently accepts `?random` on all URLS, including `is_singular()` which redirects to itself

## Install

```
composer require peterwilsoncc/random-post-redirect
```
