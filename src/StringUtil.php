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
    public static function wordCount($str)
    {
        return strlen($str);
    }

    /**
     * Nice formatting for computer sizes (Bytes).
     *
     * @param   integer $bytes The number in bytes to format
     * @param   integer $decimals The number of decimal points to include
     * @return  string
     */
    public static function sizeFormat($bytes, $decimals = 0)
    {
        $bytes = floatval($bytes);
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < pow(1024, 2)) {
            return number_format($bytes / 1024, $decimals, '.', '') . ' KiB';
        } elseif ($bytes < pow(1024, 3)) {
            return number_format($bytes / pow(1024, 2), $decimals, '.', '') . ' MiB';
        } elseif ($bytes < pow(1024, 4)) {
            return number_format($bytes / pow(1024, 3), $decimals, '.', '') . ' GiB';
        } elseif ($bytes < pow(1024, 5)) {
            return number_format($bytes / pow(1024, 4), $decimals, '.', '') . ' TiB';
        } elseif ($bytes < pow(1024, 6)) {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PiB';
        } else {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PiB';
        }
    }

    /**
     * Converts many english words that equate to true or false to boolean.
     *
     * Supports 'y', 'n', 'yes', 'no' and a few other variations.
     *
     * @param  string $string The string to convert to boolean
     * @param  bool $default The value to return if we can't match any yes/no words
     * @return boolean
     */
    public static function strToBool($string, $default = false)
    {
        $yes_words = 'affirmative|all right|aye|indubitably|most assuredly|ok|of course|okay|sure thing|y|yes+|yea|yep|sure|yeah|true|t|on|1|oui|vrai';
        $no_words = 'no*|no way|nope|nah|na|never|absolutely not|by no means|negative|never ever|false|f|off|0|non|faux';
        if (preg_match('/^(' . $yes_words . ')$/i', $string)) {
            return true;
        } elseif (preg_match('/^(' . $no_words . ')$/i', $string)) {
            return false;
        }
        return $default;
    }

    /**
     * Check if a string contains another string.
     *
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    public static function strContains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }

    /**
     * Check if a string contains another string. This version is case
     * insensitive.
     *
     * @param  string $haystack
     * @param  string $needle
     * @return boolean
     */
    public static function strContainsI($haystack, $needle)
    {
        return stripos($haystack, $needle) !== false;
    }

    /**
     * Wrapper to prevent errors if the user doesn't have the mbstring
     * extension installed.
     *
     * @param  string $encoding
     * @return string
     */
    protected static function mbInternalEncoding($encoding = null)
    {
        if (function_exists('mb_internal_encoding')) {
            return $encoding ? mb_internal_encoding($encoding) : mb_internal_encoding();
        }
        // @codeCoverageIgnoreStart
        return 'UTF-8';
        // @codeCoverageIgnoreEnd
    }

    /**
     * Pads a given string with zeroes on the left.
     *
     * @param  int $number The number to pad
     * @param  int $length The total length of the desired string
     * @return string
     */
    public static function zeroPad($number, $length)
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }


    /**
     * Returns the IP address of the client.
     *
     * @param   boolean $trust_proxy_headers Whether or not to trust the
     *                                       proxy headers HTTP_CLIENT_IP
     *                                       and HTTP_X_FORWARDED_FOR. ONLY
     *                                       use if your server is behind a
     *                                       proxy that sets these values
     * @return  string
     */
    public static function get_client_ip($trust_proxy_headers = false)
    {
        if (!$trust_proxy_headers) {
            return $_SERVER['REMOTE_ADDR'];
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Truncate the string to given length of characters.
     *
     * @param string $string The variable to truncate
     * @param integer $limit The length to truncate the string to
     * @param string $append Text to append to the string IF it gets
     *                        truncated, defaults to '...'
     * @return string
     */
    public static function truncate($string, $limit = 100, $append = '...')
    {
        if (mb_strlen($string) <= $limit) {
            return $string;
        }
        return rtrim(mb_substr($string, 0, $limit, 'UTF-8')) . $append;
    }

    /**
     * Truncate the string to given length of words.
     *
     * @param $string
     * @param $limit
     * @param string $append
     * @return string
     */
    public static function truncateWords($string, $limit = 100, $append = '...')
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $string, $matches);
        if (!isset($matches[0]) || strlen($string) === strlen($matches[0])) {
            return $string;
        }
        return rtrim($matches[0]) . $append;
    }
}