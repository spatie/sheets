<?php

namespace Spatie\Sheets\ContentParsers;

use Illuminate\Support\HtmlString;
use League\CommonMark\CommonMarkConverter;
use Spatie\Sheets\ContentParser;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class MarkdownWithFrontMatterParser implements ContentParser
{
    /** @var \League\CommonMark\CommonMarkConverter */
    protected $commonMarkConverter;

    public function __construct(CommonMarkConverter $commonMarkConverter)
    {
        $this->commonMarkConverter = $commonMarkConverter;
    }

    public function parse(string $contents): array
    {
        $document = YamlFrontMatter::parse($contents);

        $htmlContents = $this->commonMarkConverter->convertToHtml($document->body());

        return array_merge(
            $document->matter(),
            ['contents' => new HtmlString($htmlContents)]
        );
    }
}
