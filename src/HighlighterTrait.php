<?php


namespace Highlighter;


trait HighlighterTrait
{
    private function getFileLines($file)
    {
        if(!file_exists($file)) {
            throw new \Exception(sprintf('File %s does not exits'));
        }

        return file($file);
    }

    /**
     * @param     $max
     * @param     $range
     * @param int $offset
     *
     * @return array
     */
    private function generateNumberSeries($max, $range, $offset = 0)
    {
        $series = [];

        for ($i = 1; $i <= $max; $i++) {
            $series[] = $i;
        }
        $offset = $offset - $range / 2;

        if ($offset < 0) {
            $offset = 0;
        }

        return array_slice($series, $offset, $range);
    }
}