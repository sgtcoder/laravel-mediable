<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Uploaded File Adapter.
 *
 * Adapts the UploadedFile class from Symfony Components.
 */
class UploadedFileAdapter implements SourceAdapterInterface
{
    protected UploadedFile $source;

    /**
     * Constructor.
     * @param UploadedFile $source
     */
    public function __construct(UploadedFile $source)
    {
        $this->source = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function path(): ?string
    {
        return $this->source->getPath() . '/' . $this->source->getFilename();
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): ?string
    {
        return pathinfo($this->source->getClientOriginalName(), PATHINFO_FILENAME) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): ?string
    {
        return $this->source->getClientOriginalExtension() ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function mimeType(): string
    {
        return $this->source->getMimeType();
    }

    public function clientMimeType(): ?string
    {
        return $this->source->getClientMimeType();
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
        return $this->source->isValid();
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return $this->source->getSize() ?: 0;
    }

    /**
     * {@inheritdoc}
     */
    public function hash(): string
    {
        return md5_file($this->path()) ?: '';
    }
}
