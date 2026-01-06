# Minimal PHP Blog Starter

Small MVC-style PHP blog with JSON-backed posts and tags.

## Setup

1) Install dependencies and autoload:

```sh
composer dump-autoload
```

2) Run locally:

```sh
composer serve
```

Then open `http://localhost:8000`.

## Content

Posts live in `app/Data/posts.json`.
Tags live in `app/Data/tags.json`.
Post images live in `public/uploads`.

## Environment

Create `.env.local` to override settings (example):

```env
APP_ENV=development
AD_SLOTS_VISIBLE=true
```

## SEO & Social

- Set `baseUrl` and `socialImage` in `app/config.php`.
- `socialImage` should be an absolute URL to a ~1200x630 image.

## RSS

The feed is available at `http://localhost:8000/feed.xml` when running locally.

## Starter Checklist

- Update `app/config.php` site fields (title, description, baseUrl, socialImage, allowedHosts).
- Replace the logo and favicon assets in `public/assets`.
- Add or remove tags in `app/Data/tags.json`.

