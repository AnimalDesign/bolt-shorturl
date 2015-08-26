# Shorturl plugin for Bolt CMS

This plugin provides a new 'shorturl' fieldtype for contenttypes and redirects accordingly. When set up, it redirects i.e. from `http://yourhost.at/s/short` to `http://yourhost.at/page/this-is-my-long-slug`.

It runs before the internal routing and therefor overwrites all other routes.

## Setup

Add a `shorturl` fieldtype to your content type.

## Configuration

`maxlength` (default: 10): Defines the max length of the shorturl hash.
`prefix` (default: 's'): Defines the default prefix for shorturl routes.
