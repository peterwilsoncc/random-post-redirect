# Random Post Redirect

Plugin land proof of concept for [WP #64498](https://core.trac.wordpress.org/ticket/64498).

## Implemented

* `?random` Query variable
* Redirect requests use the main `WP_Query`
* Redirects added using the `wp_headers` filter (with filters from `wp_safe_redirect()` etc)
* Exits on the `send_headers` actions to avoid rendering the full page

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

### Yolo install

```
composer require peterwilsoncc/random-post-redirect:dev-main
```

## Demo (Yolo install, auto updates from `main` twice weekly, [`5 4 * * 1,4`](https://crontab.guru/#5_4_*_*_1,4))

* [Random post](https://peterwilson.cc/?random)
* [Random post type post (short note)](https://peterwilson.me/~/?random)
* [Random post from category (General)](https://peterwilson.cc/category/blog/general/?random)
* [Random post from tag (WordPress)](https://peterwilson.cc/tag/wordpress/?random)
* [Random post tagged WordPress in the category Code](https://peterwilson.cc/?cat=3&tag=wordpress&random)
