<?php
/**
 * Class:  StringUtil
 *
 * @author: Minh Duc Nguyen <minh.nguyen@ands.org.au>
 */

namespace MinhD\Util;


class StringUtil
{
    /**
     * is the input a form of a string.
     *
     * @param $str
     *
     * @return bool
     */
    public static function isString($str)
    {
        return is_string($str);
    }

    /**
     * returns the reverse representation of a string.
     *
     * @param $str
     *
     * @return string
     */
    public static function reverseString($str)
    {
        return strrev($str);
    }

    /**
     * returns the.
     *
     * @param $str
     *
     * @return bool
     */
    public static function isUrl($str)
    {
        $pattern = "/^(https?:\/\/)?((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(\:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/";
        return self::isMatch($str, $pattern);
    }

    /**
     * @param $str
     *
     * @return bool
     */
    public static function isIp($str)
    {
        $pattern = "/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
        preg_match($pattern, $str, $matches);
        return sizeof($matches) > 0 ? true : false;
    }

    /**
     * get the string that is between the start and end provided.
     *
     * @param string $string
     * @param string $start
     * @param string $end
     *
     * @return string
     */
    public static function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * is a string starts with a substring?
     *
     * @param string $str
     * @param string $sub
     *
     * @return bool
     */
    public static function startsWith($str, $sub)
    {
        return (substr($str, 0, strlen($sub)) === $sub);
    }

    /**
     * alias of self::startsWith.
     *
     * @param string $str
     * @param string $sub
     *
     * @return bool
     */
    public static function beginsWith($str, $sub)
    {
        return self::startsWith($str, $sub);
    }

    /**
     * does a string ends with a substring?
     *
     * @param string $str
     * @param string $sub
     *
     * @return bool
     */
    public static function endsWith($str, $sub)
    {
        return (substr($str, strlen($str) - strlen($sub)) === $sub);
    }

    /**
     * returns a string with all of a substring replaced by another.
     *
     * @param string $str
     * @param string $search
     * @param string $replace
     *
     * @return string
     */
    public static function replaceAll($str, $search, $replace)
    {
        return str_replace($search, $replace, $str);
    }

    /**
     * returns the type of the credit card text
     * returns unknown if it's not a credit card type.
     *
     * @param string $str
     *
     * @return string
     */
    public static function getCreditCardType($str)
    {
        $str = strtr($str, array(' ' => '', '-' => ''));
        if (self::isMatch($str, '/^4[0-9]{12}(?:[0-9]{3})?$/')) {
            return 'visa';
        } elseif (self::isMatch($str, '/^3[47][0-9]{13}$/')) {
            return 'amex';
        } elseif (self::isMatch($str, '/^5[1-5][0-9]{14}$/')) {
            return 'matercard';
        } else {
            return 'unknown';
        }
    }

    /**
     * is a given string a credit card
     * uses self::getCreditCardType for convenience.
     *
     * @param string $str
     *
     * @return bool
     */
    public static function isCreditCard($str)
    {
        return self::getCreditCardType($str) != 'unknown' ? true : false;
    }

    /**
     * is a string match a certain regex pattern.
     *
     * @param string $str
     * @param string $pattern regex
     *
     * @return bool
     */
    public static function isMatch($str, $pattern)
    {
        preg_match($pattern, $str, $matches);
        return sizeof($matches) > 0 ? true : false;
    }

    /**
     * returns the camelcase version of a word.
     *
     * @param string $str
     *
     * @return string
     */
    public static function camelize($str)
    {
        return lcfirst(strtr(ucwords(strtr($str,
            array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => '')));
    }

    /**
     * returns the snake case version of a word
     *
     * @param $str
     * @return string
     */
    public static function underscore($str)
    {
        $str = preg_replace('/[^-_\w\s]/', '', $str);
        $str = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
        $str = preg_replace('/[-\s]/', '_', $str);
        return strtolower($str);
    }

    /**
     * returns the title case, camel case version of a string
     *
     * @param $str
     * @return string
     */
    public static function titleCase($str)
    {
        $downCase = [
            'a' => 1,
            'an' => 1,
            'the' => 1, // articles
            'and' => 1,
            'but' => 1,
            'for' => 1,
            'nor' => 1,
            'or' => 1,
            'so' => 1,
            'yet' => 1, // coordinating conjunctions
            'aboard' => 1,
            'about' => 1,
            'above' => 1,
            'across' => 1,
            'after' => 1,
            'against' => 1,
            'along' => 1,
            'amid' => 1,
            'among' => 1,
            'around' => 1,
            'as' => 1,
            'at' => 1,
            'atop' => 1,
            'before' => 1,
            'behind' => 1,
            'below' => 1,
            'beneath' => 1,
            'beside' => 1,
            'between' => 1,
            'beyond' => 1,
            'by' => 1,
            'despite' => 1,
            'down' => 1,
            'during' => 1,
            'from' => 1,
            'in' => 1,
            'inside' => 1,
            'into' => 1,
            'like' => 1,
            'near' => 1,
            'of' => 1,
            'off' => 1,
            'on' => 1,
            'onto' => 1,
            'out' => 1,
            'outside' => 1,
            'over' => 1,
            'past' => 1,
            'regarding' => 1,
            'round' => 1,
            'since' => 1,
            'than' => 1,
            'through' => 1,
            'throughout' => 1,
            'till' => 1,
            'to' => 1,
            'toward' => 1,
            'under' => 1,
            'unlike' => 1,
            'until' => 1,
            'up' => 1,
            'upon' => 1,
            'with' => 1,
            'within' => 1,
            'without' => 1 // prepositions
        ];
        $words = preg_split('/\s+/', strtolower($str));
        if (count($words) < 3) {
            return ucwords(implode(' ', $words));
        }
        $first = array_shift($words);
        array_unshift($words, ucfirst($first));
        $last = array_pop($words);
        array_push($words, ucfirst($last));
        foreach ($words as &$word) {
            if (!isset($downCase[strtolower($word)])) {
                $word = ucfirst($word);
            }
        }
        return implode(' ', $words);
    }

    /**
     * returns the word count of a string
     *
     * @param $str
     * @return int
     */
    public function wordCount($str)
    {
        return strlen($str);
    }
}