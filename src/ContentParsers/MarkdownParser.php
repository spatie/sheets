<?php

namespace Spatie\Sheets\ContentParsers;

use Illuminate\Support\HtmlString;
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
        $htmlContents = $this->commonMarkConverter->convertToHtml($contents);

        return [
            'contents' => new HtmlString($htmlContents),
        ];
    }
}
