# Random Post Redirect

Plugin land proof of concept for [WP #64498](https://core.trac.wordpress.org/ticket/64498).

## This repo has been archived

The feature has now landed in WordPress Core so the POC is no longer required. See:

* [WP #64498](https://core.trac.wordpress.org/ticket/64498)
* Make WordPress Core: [Your Content Is Going To Get More Random!](https://make.wordpress.org/core/2026/11/05/your-content-is-going-to-get-more-random/)
* [WordPress/WordPress-Develop@`757cb9c`](https://github.com/WordPress/wordpress-develop/commit/757cb9c1c515e822b018d7345bea4e618dd39152)
* [WordPress/WordPress-Develop@`9d949f3`](https://github.com/WordPress/wordpress-develop/commit/9d949f34e7e7e397394b00d932b996fb5fcc2e43)
* [WordPress/WordPress-Develop@`eed6f20`](https://github.com/WordPress/wordpress-develop/commit/eed6f206be0b3b17a669bca40cc128e6a547b390)
* [WordPress/WordPress-Develop@`2f8310c`](https://github.com/WordPress/wordpress-develop/commit/2f8310cf9170dc1766316c62fcc7f01c89709004)

## Implemented

* `?random` Query variable
* Redirect requests use the main `WP_Query`
* Redirects added using the `wp_headers` filter (with filters from `wp_safe_redirect()` etc)
* Exits on the `send_headers` actions to avoid rendering the full page

## Todo

* Decide how to expose this feature to regular users: opt-in, opt-out? how can they discover it?
* Decide how to expose this feature to developers: can CPTs opt in, or will it initially be limited to built-in types?
* Decide whether `parse_request` or `pre_get_posts` should be used for setting query variables
* Proper rewrite rule, either `/random/suffix` or `/prefix/random` -- Jorbin has suggested the former -- for blog, term, post type archives
* Decide if same is needed for date archives
* Tests beyond just the plugin meta data tests.

## Known issues

* Currently accepts `?random` on all URLS, including `is_singular()` which redirects to itself

## Install

> [!NOTE]
> The Composer package has been marked as abandoned.

```
composer require peterwilsoncc/random-post-redirect
```

### Yolo install

```
composer require peterwilsoncc/random-post-redirect:dev-main
```

## Demo (Yolo install, auto updates from `main` twice weekly, [`5 4 * * 1,4`](https://crontab.guru/#5_4_*_*_1,4))

> [!NOTE]
> These URLs are no longer active as the plugin has been removed pending the WordPress 7.2 release.

* [Random post](https://peterwilson.cc/?random)
* [Random post type post (short note)](https://peterwilson.me/~/?random)
* [Random post from category (General)](https://peterwilson.cc/category/blog/general/?random)
* [Random post from tag (WordPress)](https://peterwilson.cc/tag/wordpress/?random)
* [Random post tagged WordPress in the category Code](https://peterwilson.cc/?cat=3&tag=wordpress&random)
