<?php

namespace App\Controller;

use App\Model\PostRepository;

class FeedController
{
    private array $site;
    private PostRepository $posts;

    public function __construct(array $site, PostRepository $posts)
    {
        $this->site = $site;
        $this->posts = $posts;
    }

    public function show(): void
    {
        header('Content-Type: application/rss+xml; charset=UTF-8');

        $baseUrl = $this->site['baseUrl'] ?? '';
        if ($baseUrl === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $host = preg_replace('/:\d+$/', '', $host);
            $allowedHosts = $this->site['allowedHosts'] ?? [];
            if ($allowedHosts !== []) {
                if (!in_array($host, $allowedHosts, true)) {
                    $host = $allowedHosts[0];
                }
            }
            $baseUrl = $scheme . '://' . $host;
        }

        $items = '';
        foreach ($this->posts->all() as $post) {
            $title = htmlspecialchars($post['title'] ?? 'Untitled', ENT_XML1, 'UTF-8');
            $slug = $post['slug'] ?? '';
            $link = $baseUrl . '/post/' . $slug;
            $date = $post['date'] ?? date('Y-m-d');
            $description = htmlspecialchars($post['excerpt'] ?? '', ENT_XML1, 'UTF-8');

            $items .= "\n    <item>\n";
            $items .= "      <title>{$title}</title>\n";
            $items .= "      <link>{$link}</link>\n";
            $items .= "      <guid>{$link}</guid>\n";
            $items .= "      <pubDate>" . date(DATE_RSS, strtotime($date)) . "</pubDate>\n";
            $items .= "      <description>{$description}</description>\n";
            $items .= "    </item>";
        }

        $siteTitle = htmlspecialchars($this->site['title'] ?? 'Blog', ENT_XML1, 'UTF-8');
        $siteTagline = htmlspecialchars($this->site['tagline'] ?? '', ENT_XML1, 'UTF-8');
        $feedLink = $baseUrl . '/feed.xml';
        $channelLink = $baseUrl . '/blog';

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<rss version=\"2.0\">\n";
        echo "  <channel>\n";
        echo "    <title>{$siteTitle}</title>\n";
        echo "    <link>{$channelLink}</link>\n";
        echo "    <description>{$siteTagline}</description>\n";
        echo "    <language>en-us</language>\n";
        echo "    <lastBuildDate>" . date(DATE_RSS) . "</lastBuildDate>\n";
        echo "    <atom:link href=\"{$feedLink}\" rel=\"self\" type=\"application/rss+xml\" xmlns:atom=\"http://www.w3.org/2005/Atom\"/>";
        echo $items;
        echo "\n  </channel>\n";
        echo "</rss>\n";
    }
}
