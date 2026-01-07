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
            $excerptRaw = trim($post['excerpt'] ?? '');
            $excerptEscaped = htmlspecialchars($excerptRaw, ENT_XML1, 'UTF-8');
            $description = $this->parseMarkdown($excerptEscaped);
            $contentRaw = trim($post['content'] ?? '');
            $contentHtml = '';
            if ($contentRaw !== '') {
                $paragraphs = preg_split('/\n\s*\n/', $contentRaw);
                foreach ($paragraphs as $paragraph) {
                    $paragraph = htmlspecialchars($paragraph, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $paragraph = $this->parseMarkdown($paragraph);
                    $paragraph = nl2br($paragraph);
                    $contentHtml .= "<p>{$paragraph}</p>\n";
                }
            }

            $items .= "\n    <item>\n";
            $items .= "      <title>{$title}</title>\n";
            $items .= "      <link>{$link}</link>\n";
            $items .= "      <guid>{$link}</guid>\n";
            $items .= "      <pubDate>" . date(DATE_RSS, strtotime($date)) . "</pubDate>\n";
            $items .= "      <description>{$description}</description>\n";
            if ($contentHtml !== '') {
                $safeContent = str_replace(']]>', ']]]]><![CDATA[>', $contentHtml);
                $items .= "      <content:encoded><![CDATA[{$safeContent}]]></content:encoded>\n";
            }
            $items .= "    </item>";
        }

        $siteTitle = htmlspecialchars($this->site['title'] ?? 'Blog', ENT_XML1, 'UTF-8');
        $siteTagline = htmlspecialchars($this->site['tagline'] ?? '', ENT_XML1, 'UTF-8');
        $feedLink = $baseUrl . '/feed.xml';
        $channelLink = $baseUrl . '/blog';

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
        echo "  <channel>\n";
        echo "    <title>{$siteTitle}</title>\n";
        echo "    <link>{$channelLink}</link>\n";
        echo "    <description>{$siteTagline}</description>\n";
        echo "    <language>pt-PT</language>\n";
        echo "    <lastBuildDate>" . date(DATE_RSS) . "</lastBuildDate>\n";
        echo "    <atom:link href=\"{$feedLink}\" rel=\"self\" type=\"application/rss+xml\" />";
        echo $items;
        echo "\n  </channel>\n";
        echo "</rss>\n";
    }

    private function parseMarkdown(string $text): string
    {
        $text = preg_replace('/\^(.+?)\^/s', '<small>$1</small>', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $text);
        return $text;
    }
}
