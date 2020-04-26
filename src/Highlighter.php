<?php

namespace Highlighter;

use Exception;
use Highlighter\Renderer\RendererInterface;
use Highlighter\Renderer\StandardLineRenderer;

/**
 * Class Highlighter
 *
 * @package Highlighter
 */
class Highlighter
{
    use HighlighterTrait;

    /**
     * @var RendererInterface
     */
    public $lineRenderer;

    /**
     * Highlighter constructor.
     *
     * @param RendererInterface|null $lineRenderer
     */
    public function __construct(RendererInterface $lineRenderer = null)
    {
        /** If specified custom line renderer - using it. Else - using standard line renderer */
        /** @var RendererInterface lineRenderer */
        $this->lineRenderer = $lineRenderer ? $lineRenderer : new StandardLineRenderer();
    }

    /**
     * @param string $file
     * @param int    $num
     *
     * @param bool   $highlight
     *
     * @return mixed
     * @throws Exception
     */
    public function getLine($file, $num, $highlight = false)
    {
        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        return $this->lineRenderer->renderLine($num, $highlight);
    }

    /**
     * @param $file
     * @param $num
     * @param $range
     *
     * @return string
     * @throws Exception
     */
    public function getLineWithNeighbors($file, $num, $range = 5)
    {
        $fileLines = $this->getFileLines($file);

        /** @var array $range */
        $range = $this->generateNumberSeries(count($fileLines), $range, $num);

        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        $result = "";

        /**
         * Building final result
         *
         * @var int $lineNum
         */
        foreach($range as $lineNum)
        {
            $result .= $this->lineRenderer->renderLine($lineNum, ($lineNum == $num));
        }

        return $result;
    }

    public function getWholeFile($file)
    {
        $fileLines = $this->getFileLines($file);

        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        $result = "";

        /**
         * Building final result
         *
         * @var int $lineNum
         */
        foreach(array_keys($fileLines) as $lineNum)
        {
            $result .= $this->lineRenderer->renderLine($lineNum + 1);
        }

        return $result;
    }
}