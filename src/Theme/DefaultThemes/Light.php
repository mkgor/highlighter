<?php

namespace Highlighter\Theme\DefaultThemes;

use Highlighter\Styles;
use Highlighter\Theme\ThemeInterface;

class Light implements ThemeInterface
{
    public function getStringColor()
    {
        return 'green';
    }

    public function getCommentColor()
    {
        return 'light_gray';
    }

    public function getKeywordColor()
    {
        return 'red';
    }

    public function getDefaultColor()
    {
        return 'white';
    }

    public function getHTMLColor()
    {
        return 'cyan';
    }

    public function getVariableColor()
    {
        return 'white';
    }

    public function getFuncColor()
    {
        return 'light_cyan';
    }

    public function getLineBeginColor()
    {
        return 'bg_red';
    }

    public function getLineNumberColor()
    {
        return 'black';
    }

    public function getLineNumberBgColor()
    {
        return 'bg_white';
    }

    public function getLineNumberHighlightedColor()
    {
        return 'white';
    }

    public function getLineNumberHighlightedBgColor()
    {
        return 'bg_red';
    }

    public function getLineHighlightBgColor()
    {
        return 'bg_red';
    }
}