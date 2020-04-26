<?php


namespace Highlighter\Renderer;

/**
 * Interface RendererInterface
 *
 * @package LineHighlighter\Renderer
 */
interface RendererInterface
{
    public function renderLine($line, $lineNum);
}