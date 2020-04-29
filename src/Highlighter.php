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
     * @param null                   $theme
     */
    public function __construct(RendererInterface $lineRenderer = null, $theme = null)
    {
        /** If specified custom line renderer - using it. Else - using standard line renderer */
        /** @var RendererInterface lineRenderer */
        if ($lineRenderer) {
            $this->lineRenderer = $lineRenderer;
        } else {
            $this->lineRenderer = new StandardLineRenderer();
        }

        if ($theme) {
            $this->lineRenderer->setTheme($theme);
        }
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
    public function getSnippet($file, $num, $range = 5)
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
        foreach ($range as $lineNum) {
            $result .= $this->lineRenderer->renderLine($lineNum, ($lineNum == $num));
        }

        return $result;
    }

    /**
     * @param     $file
     * @param     $num
     * @param int $range
     *
     * @return string
     * @throws Exception
     */
    public function getSpecifiedSnippet($file, $from, $to)
    {
        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        $result = "";

        /**
         * Building final result
         *
         * @var int $lineNum
         */
        for ($i = $from; $i <= $to; $i++) {
            $result .= $this->lineRenderer->renderLine($i);
        }

        return $result;
    }

    /**
     * @param $file
     *
     * @return string
     * @throws Exception
     */
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
        foreach (array_keys($fileLines) as $lineNum) {
            $result .= $this->lineRenderer->renderLine($lineNum + 1);
        }

        return $result;
    }


    /**
     * @param     $file
     *
     * @param     $num
     *
     * @return string
     * @throws Exception
     */
    public function getLineWithoutNumbers($file, $num)
    {
        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        $fileStore = $this->lineRenderer->getFileStore();

        return $fileStore[$num];
    }

    /**
     * @param     $file
     * @param     $num
     * @param int $range
     *
     * @return string
     * @throws Exception
     */
    public function getSnippetWithoutNumbers($file, $num, $range = 5)
    {
        $fileLines = $this->getFileLines($file);

        /** @var array $range */
        $range = $this->generateNumberSeries(count($fileLines), $range, $num);

        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        $result = "";

        $fileStore = $this->lineRenderer->getFileStore();

        /**
         * Building final result
         *
         * @var int $lineNum
         */
        foreach ($range as $lineNum) {
            $result .= $fileStore[$lineNum] . "\n";
        }

        return $result;
    }

    /**
     * @param     $file
     * @param     $num
     * @param int $range
     *
     * @return string
     * @throws Exception
     */
    public function getSpecifiedSnippetWithoutNumbers($file, $from, $to)
    {
        $fileLines = $this->getFileLines($file);

        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        $result = "";

        $fileStore = $this->lineRenderer->getFileStore();

        /**
         * Building final result
         *
         * @var int $lineNum
         */
        for ($i = $from; $i <= $to; $i++) {
            $result .= $fileStore[$i] . "\n";
        }

        return $result;
    }

    /**
     * @param     $file
     *
     * @return string
     * @throws Exception
     */
    public function getWholeFileWithoutNumbers($file)
    {
        /** Highlighting syntax of file and storing in into array in renderer */
        $this->lineRenderer->highlightSyntax(file_get_contents($file));

        $result = "";

        $fileStore = $this->lineRenderer->getFileStore();

        /**
         * Building final result
         *
         * @var int $lineNum
         */
        foreach ($fileStore as $line) {
            $result .= $line . "\n";
        }

        return $result;
    }
}