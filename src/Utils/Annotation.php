<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-06-30
 * Time: 17:29
 */

namespace Inhere\Console\Utils;

/**
 * Class Annotation
 * @package Inhere\Console\Utils
 */
class Annotation
{
    /*
     * 以下三个方法来自 yii2 console/Controller.php
     */

    /**
     * Parses the comment block into tags.
     *
     * @param string $comment The comment block text
     * @return array The parsed tags
     */
    public static function tagList($comment)
    {
        $comment = "@description \n" . strtr(trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($comment, '/'))), "\r", '');

        $parts = preg_split('/^\s*@/m', $comment, -1, PREG_SPLIT_NO_EMPTY);
        $tags = [];

        foreach ($parts as $part) {
            if (preg_match('/^(\w+)(.*)/ms', trim($part), $matches)) {
                $name = $matches[1];
                if (!isset($tags[$name])) {
                    $tags[$name] = trim($matches[2]);
                } elseif (is_array($tags[$name])) {
                    $tags[$name][] = trim($matches[2]);
                } else {
                    $tags[$name] = [$tags[$name], trim($matches[2])];
                }
            }
        }

        return $tags;
    }

    /**
     * Returns the first line of docBlock.
     *
     * @param  $comment
     * @return string
     */
    public static function firstLine($comment): string
    {
        $docLines = preg_split('~\R~u', $comment);

        if (isset($docLines[1])) {
            return trim($docLines[1], "\t *");
        }

        return '';
    }

    /**
     * Returns full description from the doc-block.
     * If have multi line text, will return multi line.
     *
     * @param  $comment
     * @return string
     */
    public static function description($comment): string
    {
        $comment = strtr(trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($comment, '/'))), "\r", '');

        if (preg_match('/^\s*@\w+/m', $comment, $matches, PREG_OFFSET_CAPTURE)) {
            $comment = trim(substr($comment, 0, $matches[0][1]));
        }

        return $comment;
    }
}
