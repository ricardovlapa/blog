# Data directory

Store content files used by the application here.

## posts.json structure

Each post should include:
- slug: unique string used in the URL
- title: post title
- date: YYYY-MM-DD
- excerpt: short summary shown in lists
- image: 1:1 image URL (local path or remote)
- tags: array of tag ids (UUIDs from tags.json)
- content: full text with blank lines between paragraphs

## tags.json structure

Each tag should include:
- id: UUID
- label: tag name
- slug: URL-safe slug
