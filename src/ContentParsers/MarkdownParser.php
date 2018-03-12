<?php

namespace Spatie\Sheets\ContentParsers;

use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\ContentParser;

class MarkdownParser implements ContentParser
{
    /** @var \League\CommonMark\CommonMarkConverter */
    protected $commonMarkConverter;

    public function __construct(CommonMarkConverter $commonMarkConverter)
    {
        $this->commonMarkConverter = $commonMarkConverter;
    }

    public function parse(string $contents): array
    {
        return [
            'contents' => $this->commonMarkConverter->convertToHtml($contents),
        ];
    }
}
