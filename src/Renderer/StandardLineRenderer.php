<?php


namespace Highlighter\Renderer;

use Highlighter\Styles;

/**
 * Class StandardLineRenderer
 *
 * @package Highlighter\Renderer
 */
class StandardLineRenderer implements RendererInterface
{
    const TOKEN_DEFAULT = 'default',
        TOKEN_COMMENT = 'comment',
        TOKEN_STRING = 'string',
        TOKEN_HTML = 'html',
        TOKEN_KEYWORD = 'keyword',
        TOKEN_VARIABLE = 'variable';

    /**
     * @var array
     */
    private $theme = [
        self::TOKEN_STRING   => 'light_yellow',
        self::TOKEN_COMMENT  => 'green',
        self::TOKEN_KEYWORD  => 'blue',
        self::TOKEN_DEFAULT  => 'default',
        self::TOKEN_HTML     => 'cyan',
        self::TOKEN_VARIABLE => "light_cyan",
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
        $boldLine = sprintf("%s %s", $this->buildStyleCode(['bg_yellow']), self::ANSI_RESET_STYLES);

        $lineNumColoured = sprintf("%s %s %s", $this->buildStyleCode((!$highlight) ? ['light_gray', 'bg_dark_gray'] : ['dark_gray', 'bg_light_red']), str_pad($lineNum, $lineStrlen, ' ', STR_PAD_LEFT), self::ANSI_RESET_STYLES);

        if (!$highlight) {
            return sprintf("%s%s %s\n", $boldLine, $lineNumColoured, $this->fileStore[$lineNum]);
        } else {
            $line = str_replace("\x1b[0m", "", $this->fileStore[$lineNum]);

            return sprintf("%s%s%s%s%s\n", $boldLine, $lineNumColoured, $this->buildStyleCode(['bg_dark_gray']), ' ' . $line, self::ANSI_RESET_STYLES);
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
            foreach($tokenLine as $token) {
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
                        $newType = self::TOKEN_VARIABLE;
                        break;
                    case T_OPEN_TAG:
                    case T_OPEN_TAG_WITH_ECHO:
                    case T_CLOSE_TAG:
                    case T_STRING:
                    case T_DIR:
                    case T_FILE:
                    case T_METHOD_C:
                    case T_DNUMBER:
                    case T_LNUMBER:
                    case T_NS_C:
                    case T_LINE:
                    case T_CLASS_C:
                    case T_TRAIT_C:
                        $newType = self::TOKEN_DEFAULT;
                        break;

                    case T_COMMENT:
                    case T_DOC_COMMENT:
                        $newType = self::TOKEN_COMMENT;
                        break;

                    case T_ENCAPSED_AND_WHITESPACE:
                    case T_CONSTANT_ENCAPSED_STRING:
                        $newType = self::TOKEN_STRING;
                        break;

                    case T_INLINE_HTML:
                        $newType = self::TOKEN_HTML;
                        break;

                    default:
                        $newType = self::TOKEN_KEYWORD;
                }

                $result[$i]['name'] = $newType;
                $result[$i]['content'] = $token[1];
                $result[$i]['line'] = $token[2];

                $lastLine = $token[2];
            } else {
                $result[$i]['name'] = self::TOKEN_DEFAULT;
                $result[$i]['content'] = $token;
                $result[$i]['line'] = $lastLine;
            }
        }

        return $result;
    }

    /**
     * @param array $tokens
     * @return array
     */
    private function handleTokens(array $tokens)
    {
        $lines = array();

        $line = array();

        foreach ($tokens as $token) {
            foreach (explode("\n", $token['content']) as $count => $tokenLine) {
                if ($count > 0) {
                    $lines[] = $line;
                    $line = array();
                }

                if ($tokenLine === '') {
                    continue;
                }

                $line[] = [
                    'name' => $token['name'],
                    'content' => $tokenLine
                ];
            }
        }

        $lines[] = $line;

        return $lines;
    }
}