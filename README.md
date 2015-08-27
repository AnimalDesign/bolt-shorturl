# Shorturl plugin for Bolt CMS

This plugin provides a new 'shorturl' fieldtype for contenttypes and redirects accordingly. When set up, it redirects i.e. from `http://yourhost.at/s/short` to `http://yourhost.at/page/this-is-my-long-slug`.

It runs before the internal routing and therefor overwrites all other routes.

## Setup

Add a `shorturl` fieldtype to your content type:

````
projects:
    [...]
    fields:
        [...]
        shorturl:
            type: shorturl
		[...]
    icon_one: fa:heart-o
    icon_many: fa:heart-o
````

You're done.

## Configuration

- `maxlength` (default: 10): Defines the maximal length of the shorturl hash.
- `prefix` (default: 's'): Defines the default prefix for shorturl routes (i.e. http://yourhost.at/**s**/short). If none is given, none is used (http://yourhost/short).

## About

„We build it“ — [ANIMAL](http://animal.at)
