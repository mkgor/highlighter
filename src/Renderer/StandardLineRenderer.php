<?php


namespace Highlighter\Renderer;

use Highlighter\Highlighter;
use Highlighter\Styles;

/**
 * Class StandardLineRenderer
 *
 * @package Highlighter\Renderer
 */
class StandardLineRenderer implements RendererInterface
{
    /**
     * @var array
     */
    private $theme = [
        Highlighter::TOKEN_STRING   => 'light_yellow',
        Highlighter::TOKEN_COMMENT  => 'green',
        Highlighter::TOKEN_KEYWORD  => 'blue',
        Highlighter::TOKEN_DEFAULT  => 'default',
        Highlighter::TOKEN_HTML     => 'cyan',
        Highlighter::TOKEN_VARIABLE => "light_cyan",
        Highlighter::TOKEN_FUNC     => "light_blue",

        'line_begin_color'     => 'bg_yellow',
        'line_number_bg_color' => 'bg_dark_gray',
        'line_number_color'    => 'light_gray',

        'line_number_highlighted_bg_color' => 'bg_light_red',
        'line_number_highlighted_color' => 'dark_gray',

        'line_highlight_bg_color' => 'bg_dark_gray'
    ];

    const ANSI_RESET_STYLES = "\x1b[0m";

    /** @var array */
    private $fileStore;

    /**
     * @return array
     */
    public function getFileStore()
    {
        return $this->fileStore;
    }

    /**
     * @param array $fileStore
     */
    public function setFileStore($fileStore)
    {
        $this->fileStore = $fileStore;
    }

    /**
     * @return array
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param array $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @param      $lineNum
     * @param bool $highlight
     *
     * @return mixed
     */
    public function renderLine($lineNum, $highlight = false)
    {
        end($this->fileStore);

        $lineStrlen = strlen(key($this->fileStore) + 1);
        $boldLine = sprintf("%s %s", $this->buildStyleCode([$this->theme['line_begin_color']]), self::ANSI_RESET_STYLES);

        $lineNumColoured = sprintf("%s %s %s", $this->buildStyleCode((!$highlight) ? [$this->theme['line_number_color'], $this->theme['line_number_bg_color']] : [$this->theme['line_number_highlighted_color'], $this->theme['line_number_highlighted_bg_color']]), str_pad($lineNum, $lineStrlen, ' ', STR_PAD_LEFT), self::ANSI_RESET_STYLES);

        if (!$highlight) {
            return sprintf("%s%s %s\n", $boldLine, $lineNumColoured, $this->fileStore[$lineNum]);
        } else {
            $line = str_replace("\x1b[0m", "", $this->fileStore[$lineNum]);

            return sprintf("%s%s%s%s%s\n", $boldLine, $lineNumColoured, $this->buildStyleCode([$this->theme['line_highlight_bg_color']]), ' ' . $line, self::ANSI_RESET_STYLES);
        }
    }

    /**
     * @param $phpString
     *
     * @return array
     * @throws \Exception
     */
    public function highlightSyntax($phpString)
    {
        $tokens = $this->tokenize($phpString);
        $preparedLines = $this->handleTokens($tokens);

        $lines = [];

        foreach ($preparedLines as $i => $tokenLine) {
            $line = '';
            foreach ($tokenLine as $token) {
                $line .= sprintf("%s%s%s", $this->buildStyleCode([$this->theme[$token['name']]]), $token['content'], self::ANSI_RESET_STYLES);
            }

            $lines[$i + 1] .= $line;
        }

        foreach ($lines as $i => $line) {
            $lines[$i] = str_replace("\r\n", "", $line);
        }

        $this->fileStore = $lines;

        return $lines;
    }

    /**
     * @param array $styles
     *
     * @return string
     */
    public function buildStyleCode($styles = [])
    {
        $stylesNumeric = [];

        foreach ($styles as $style) {
            $stylesNumeric[] = Styles::$styles[$style];
        }

        return sprintf("\x1b[%sm", implode(";", $stylesNumeric));
    }

    /**
     * @param $code
     *
     * @return array
     */
    private function tokenize($code)
    {
        $tokens = token_get_all($code);


        $result = [];
        $lastLine = 0;

        foreach ($tokens as $i => $token) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_WHITESPACE:
                        break;
                    case T_VARIABLE:
                        $newType = Highlighter::TOKEN_VARIABLE;
                        break;
                    case T_OPEN_TAG:
                    case T_OPEN_TAG_WITH_ECHO:
                    case T_CLOSE_TAG:
                    case T_DIR:
                    case T_FILE:
                    case T_DNUMBER:
                    case T_LNUMBER:
                    case T_NS_C:
                    case T_LINE:
                    case T_CLASS_C:
                    case T_TRAIT_C:
                        $newType = Highlighter::TOKEN_DEFAULT;
                        break;

                    case T_COMMENT:
                    case T_DOC_COMMENT:
                        $newType = Highlighter::TOKEN_COMMENT;
                        break;

                    case T_ENCAPSED_AND_WHITESPACE:
                    case T_CONSTANT_ENCAPSED_STRING:
                        $newType = Highlighter::TOKEN_STRING;
                        break;

                    case T_INLINE_HTML:
                        $newType = Highlighter::TOKEN_HTML;
                        break;

                    case T_STRING:
                        $newType = Highlighter::TOKEN_FUNC;
                        break;

                    default:
                        $newType = Highlighter::TOKEN_KEYWORD;
                }

                $result[$i]['name'] = $newType;
                $result[$i]['content'] = $token[1];
                $result[$i]['line'] = $token[2];

                $lastLine = $token[2];
            } else {
                $result[$i]['name'] = Highlighter::TOKEN_DEFAULT;
                $result[$i]['content'] = $token;
                $result[$i]['line'] = $lastLine;
            }
        }

        return $result;
    }

    /**
     * @param array $tokens
     *
     * @return array
     */
    private function handleTokens(array $tokens)
    {
        $lines = [];

        $line = [];

        foreach ($tokens as $token) {
            foreach (explode("\n", $token['content']) as $count => $tokenLine) {
                if ($count > 0) {
                    $lines[] = $line;
                    $line = [];
                }

                if ($tokenLine === '') {
                    continue;
                }

                $line[] = [
                    'name'    => $token['name'],
                    'content' => $tokenLine,
                ];
            }
        }

        $lines[] = $line;

        return $lines;
    }
}