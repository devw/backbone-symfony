<?php

namespace HighFive\MainBundle\Markdown;

use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;

/**
 * This class wraps the original parser to support the GFM syntax
 */
class GithubFlavoredMarkdownParser extends MarkdownParser
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Converts text to html using markdown rules
     *
     * @param string $text plain text
     *
     * @return string rendered html
     */
    public function transform($text)
    {
        $text = $this->transformFencedCodeBlocks($text);

        return parent::transform($text);
    }

    protected function doAutoLinks($text)
    {
        $text = preg_replace(
            '#(^|[^<\\[\\(])((https?|ftp|dict):[^\'">\\]\\)\\s]+)([^>\\]\\)]|$)#i',
            '$1<$2>$4',
            $text
        );

        return parent::doAutoLinks($text);
    }

    private function transformFencedCodeBlocks($content)
    {
        return preg_replace(
            '#(^|[^\\\\])```#i',
            '$1~~~',
            $content
        );
    }

    protected function doHardBreaks($text)
    {
        return preg_replace('#[ ]*(\n)#i', '<br />$1', $text);
    }
}
