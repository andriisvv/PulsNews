<?php

namespace App\Console\Commands;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchNews extends Command
{
    /**
     * php artisan news:fetch          — додати свіжі новини
     * php artisan news:fetch --fresh  — спершу видалити раніше імпортовані
     */
    protected $signature = 'news:fetch
                            {--limit=8 : Скільки новин брати з кожної стрічки}
                            {--fresh : Видалити раніше імпортовані новини перед оновленням}';

    protected $description = 'Імпортує свіжі новини з RSS-стрічок українських видань';

    /**
     * Українські RSS-джерела. Кожному призначено категорію сайту.
     * Недоступні стрічки автоматично пропускаються.
     */
    private array $feeds = [
        ['url' => 'https://www.pravda.com.ua/rss/',  'category' => 'Світ',       'source' => 'Українська правда'],
        ['url' => 'https://www.epravda.com.ua/rss/', 'category' => 'Економіка',  'source' => 'Економічна правда'],
        ['url' => 'https://life.pravda.com.ua/rss/', 'category' => 'Культура',   'source' => 'УП Життя'],
        ['url' => 'https://ain.ua/feed/',            'category' => 'Технології', 'source' => 'AIN.ua'],
    ];

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $deleted = News::where('source', '!=', 'manual')->delete();
            $this->warn("Видалено раніше імпортованих новин: {$deleted}");
        }

        $limit   = (int) $this->option('limit');
        $created = 0;

        foreach ($this->feeds as $feed) {
            $this->info("Завантаження: {$feed['source']} ...");

            try {
                $response = Http::timeout(20)
                    ->withHeaders(['User-Agent' => 'PulseNewsBot/1.0'])
                    ->get($feed['url']);

                if (! $response->ok()) {
                    $this->warn("  пропущено (HTTP {$response->status()})");
                    continue;
                }

                $added = $this->parseFeed($response->body(), $feed, $limit);
                $created += $added;
                $this->line("  додано: {$added}");
            } catch (\Throwable $e) {
                $this->warn("  помилка: {$e->getMessage()}");
                continue;
            }
        }

        $this->info("Готово. Усього додано новин: {$created}");

        return self::SUCCESS;
    }

    private function parseFeed(string $body, array $feed, int $limit): int
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($body);

        if ($xml === false || ! isset($xml->channel->item)) {
            return 0;
        }

        $count = 0;

        foreach ($xml->channel->item as $item) {
            if ($count >= $limit) {
                break;
            }

            $title = $this->clean((string) $item->title);
            $link  = trim((string) $item->link);

            if ($title === '' || $link === '') {
                continue;
            }

            // Пропускаємо дублікати (за посиланням або заголовком)
            $exists = News::where('external_url', $link)
                ->orWhere('title', $title)
                ->exists();

            if ($exists) {
                continue;
            }

            // Повний текст: content:encoded, інакше description
            $contentNs = $item->children('http://purl.org/rss/1.0/modules/content/');
            $fullHtml  = isset($contentNs->encoded) ? (string) $contentNs->encoded : '';
            $descHtml  = (string) $item->description;
            $rawHtml   = $fullHtml !== '' ? $fullHtml : $descHtml;

            $content = $this->clean($rawHtml);
            $excerpt = Str::limit($this->clean($descHtml), 280);

            if ($content === '') {
                $content = $title;
            }

            try {
                $publishedAt = ! empty($item->pubDate)
                    ? Carbon::parse((string) $item->pubDate)
                    : now();
            } catch (\Throwable $e) {
                $publishedAt = now();
            }

            // Зображення: зі стрічки, інакше — детермінований плейсхолдер
            $image = $this->extractImage($item, $rawHtml)
                ?: 'https://picsum.photos/seed/' . md5($link) . '/800/500';

            News::create([
                'title'        => Str::limit($title, 480, ''),
                'excerpt'      => $excerpt,
                'content'      => $content,
                'image_url'    => $image,
                'category'     => $feed['category'],
                'source'       => $feed['source'],
                'external_url' => $link,
                'author'       => $feed['source'],
                'is_published' => true,
                'is_featured'  => false,
                'published_at' => $publishedAt,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Очищення тексту: декодування HTML-сутностей (&#38; → &), зняття тегів,
     * згортання зайвих пробілів.
     */
    private function clean(string $html): string
    {
        $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = strip_tags($text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim($text);
    }

    /**
     * Пошук зображення: enclosure → media:content → media:thumbnail → <img> у тексті.
     */
    private function extractImage(\SimpleXMLElement $item, string $html): ?string
    {
        if (isset($item->enclosure['url'])) {
            $url = (string) $item->enclosure['url'];
            if ($url !== '') {
                return $url;
            }
        }

        $media = $item->children('http://search.yahoo.com/mrss/');

        if (isset($media->content)) {
            $attrs = $media->content->attributes();
            if (isset($attrs->url) && (string) $attrs->url !== '') {
                return (string) $attrs->url;
            }
        }

        if (isset($media->thumbnail)) {
            $attrs = $media->thumbnail->attributes();
            if (isset($attrs->url) && (string) $attrs->url !== '') {
                return (string) $attrs->url;
            }
        }

        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m)) {
            return $m[1];
        }

        return null;
    }
}
