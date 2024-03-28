<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use GuzzleHttp\Psr7\Utils;
use Plank\Mediable\Helpers\File;
use Psr\Http\Message\StreamInterface;

/**
 * Local Path Adapter.
 *
 * Adapts a string representing an absolute path
 */
class LocalPathAdapter implements SourceAdapterInterface
{
    protected string $source;

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function getSource(): mixed
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function path(): string
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): string
    {
        return pathinfo($this->source, PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): string
    {
        $extension = pathinfo($this->source, PATHINFO_EXTENSION);

        if ($extension) {
            return $extension;
        }

        return (string)File::guessExtension($this->mimeType());
    }

    /**
     * {@inheritdoc}
     */
    public function mimeType(): string
    {
        return mime_content_type($this->source);
    }

    /**
     * {@inheritdoc}
     */
    public function contents(): string
    {
        return (string)file_get_contents($this->source);
    }

    /**
     * {@inheritdoc}
     */
    public function getStream(): StreamInterface
    {
        return Utils::streamFor(fopen($this->path(), 'rb'));
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return is_readable($this->source);
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return (int)filesize($this->source);
    }
}
