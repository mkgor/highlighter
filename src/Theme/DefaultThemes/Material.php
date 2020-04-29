<?php


namespace Highlighter\Theme\DefaultThemes;


use Highlighter\Styles;
use Highlighter\Theme\ThemeInterface;

class Material implements ThemeInterface
{
    public function getStringColor()
    {
        return 'light_green';
    }

    public function getCommentColor()
    {
        return 'green';
    }

    public function getKeywordColor()
    {
        return 'light_magenta';
    }

    public function getDefaultColor()
    {
        return 'light_gray';
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
        return 'cyan';
    }

    public function getLineBeginColor()
    {
        return 'bg_light_yellow';
    }

    public function getLineNumberColor()
    {
        return 'white';
    }

    public function getLineNumberBgColor()
    {
        return 'bg_dark_gray';
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