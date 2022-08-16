<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Contracts\Downloadable;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait StoresFiles
{
    public function getFileInfo(string $file_id): Telegraph
    {
        $telegraph = clone $this;

        $telegraph->endpoint = self::ENDPOINT_GET_FILE;
        $telegraph->data['file_id'] = $file_id;

        return $telegraph;
    }

    public function store(Downloadable|string $downloadable, string $path, string $filename = null, string $baseFileUrl = null): string
    {
        $fileId = is_string($downloadable) ? $downloadable : $downloadable->id();

        $response = $this->getFileInfo($fileId)->send();

        if ($response->telegraphError()) {
            throw FileException::failedToRetreiveFileInfo($fileId);
        }

        $filePath = $response->json('result.file_path');

        $url = Str::of($baseFileUrl ?? self::TELEGRAM_API_BASE_URL)
            ->rtrim('/')
            ->append('/file/bot')
            ->append($this->getBot()->token)
            ->append('/', $filePath);

        $response = Http::get($url);

        if ($response->failed()) {
            throw FileException::unableToDownloadFile($fileId);
        }

        $filename ??= $url->afterLast('/')->before('?');

        File::ensureDirectoryExists($path);
        File::put($path . "/" . $filename, $response->body());

        return $path . "/" . $filename;
    }
}
