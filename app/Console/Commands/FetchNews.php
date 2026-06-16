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
     * Сигнатура команди: php artisan news:fetch
     */
    protected $signature = 'news:fetch {--limit=8 : Скільки новин брати з кожної стрічки}';

    protected $description = 'Імпортує свіжі новини з RSS-стрічок українських видань';

    /**
     * Перелік RSS-джерел. Кожному призначено категорію сайту.
     * Якщо якась стрічка недоступна — вона просто пропускається.
     */
    private array $feeds = [
        ['url' => 'https://www.pravda.com.ua/rss/',  'category' => 'Світ',       'source' => 'Українська правда'],
        ['url' => 'https://www.epravda.com.ua/rss/', 'category' => 'Економіка',  'source' => 'Економічна правда'],
        ['url' => 'https://itc.ua/feed/',            'category' => 'Технології', 'source' => 'ITC.ua'],
    ];

    public function handle(): int
    {
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

    /**
     * Розбір XML-стрічки та збереження новин у БД.
     */
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

            $title = trim((string) $item->title);
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

            $descriptionRaw = (string) $item->description;
            $plain   = trim(strip_tags($descriptionRaw));
            $excerpt = Str::limit($plain, 280);
            $content = $plain !== '' ? $plain : $title;

            try {
                $publishedAt = ! empty($item->pubDate)
                    ? Carbon::parse((string) $item->pubDate)
                    : now();
            } catch (\Throwable $e) {
                $publishedAt = now();
            }

            News::create([
                'title'        => Str::limit($title, 480, ''),
                'excerpt'      => $excerpt,
                'content'      => $content,
                'image_url'    => $this->extractImage($item, $descriptionRaw),
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
     * Спроба дістати зображення новини: enclosure → media:content → перший <img> в описі.
     */
    private function extractImage(\SimpleXMLElement $item, string $description): ?string
    {
        // 1) <enclosure url="..." />
        if (isset($item->enclosure['url'])) {
            $url = (string) $item->enclosure['url'];
            if ($url !== '') {
                return $url;
            }
        }

        // 2) <media:content url="..." />
        $media = $item->children('http://search.yahoo.com/mrss/');
        if (isset($media->content)) {
            $attrs = $media->content->attributes();
            if (isset($attrs->url) && (string) $attrs->url !== '') {
                return (string) $attrs->url;
            }
        }

        // 3) Перший <img> у тексті опису
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $description, $m)) {
            return $m[1];
        }

        return null;
    }
}
