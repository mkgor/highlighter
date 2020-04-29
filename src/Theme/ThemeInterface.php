<?php

namespace Highlighter\Theme;

interface ThemeInterface
{
    public function getStringColor();

    public function getCommentColor();

    public function getKeywordColor();

    public function getDefaultColor();

    public function getHTMLColor();

    public function getVariableColor();

    public function getFuncColor();

    public function getLineBeginColor();

    public function getLineNumberColor();

    public function getLineNumberBgColor();

    public function getLineNumberHighlightedColor();

    public function getLineNumberHighlightedBgColor();

    public function getLineHighlightBgColor();
}