<?php


namespace MinhD\Util;


class ArrayUtil
{

    /**
     * Remove the duplicates from an array.
     *
     * This is faster version than the builtin array_unique().
     *
     * Notes on time requirements:
     *   array_unique -> O(n log n)
     *   array_flip -> O(n)
     *
     * http://stackoverflow.com/questions/8321620/array-unique-vs-array-flip
     * http://php.net/manual/en/function.array-unique.php
     *
     * @param  $array
     * @return array $array
     */
    public static function fastArrayUnique($array)
    {
        $array = array_keys(array_flip($array));
        return $array;
    }

    /**
     * Returns boolean if a function is an associative array
     *
     * @param  array $array An array to test
     *
     * @return boolean
     */
    public static function isAssocArray($array)
    {
        if (!is_array($array)) {
            return false;
        }
        // $array = array() is not associative
        if (sizeof($array) === 0) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Check for non existing keys in an array.
     *
     * @param array $keys
     * @param array $targetArray
     * @return array an array with the keys not found.
     * @internal param an $keys array with the keys to check in the target array.
     * @internal param the $targetArray target array.
     */
    static function nonExistingKeys(array $keys, array $targetArray)
    {
        $notFound = array();
        foreach ($keys as $k) {
            if (!isset($targetArray["$k"])) { // if ! array_key_exists( $k, $targetArray )
                array_push($notFound, $k);
            }
        }
        return $notFound;
    }

    /**
     * Removes an item from an array.
     *
     * @param array array
     * @param mixed $item
     * @param boolean $reindexArray
     * @param boolean $compareItemTypes
     * @return  true
     */
    static function removeItem(array &$array, $item, $reindexArray = true, $compareItemTypes = false)
    {
        if (!isset($array) || !isset($item)) {
            return false;
        }
        $key = array_search($item, $array, $compareItemTypes);
        if (false === $key) {
            return false;
        }
        unset($array[$key]);
        if ($reindexArray) {
            $array = array_values($array);
        }
        return true;
    }

    /**
     * Replace the given keys in the target array.
     *
     * @param array $keyMap The key map (usually string to string)
     * @param array $target The target array, passed by reference.
     */
    static function replaceKeys(array $keyMap, array &$target)
    {
        foreach ($keyMap as $oldKey => $newKey) {
            if (isset($target[$oldKey])) {
                $target[$newKey] = $target[$oldKey];
                unset($target[$oldKey]);
            }
        }
    }

    /**
     * Returns the first element in an array.
     *
     * @param  array $array
     * @return mixed
     */
    public static function arrayFirst(array $array)
    {
        return reset($array);
    }

    /**
     * Returns the last element in an array.
     *
     * @param  array $array
     * @return mixed
     */
    public static function arrayLast(array $array)
    {
        return end($array);
    }

    /**
     * Returns the first key in an array.
     *
     * @param  array $array
     * @return int|string
     */
    public static function arrayFirstKey(array $array)
    {
        reset($array);
        return key($array);
    }

    /**
     * Returns the last key in an array.
     *
     * @param  array $array
     * @return int|string
     */
    public static function arrayLastKey(array $array)
    {
        end($array);
        return key($array);
    }

    /**
     * Flatten a multi-dimensional array into a one dimensional array.
     *
     * Contributed by Theodore R. Smith of PHP Experts, Inc. <http://www.phpexperts.pro/>
     *
     * @param  array $array The array to flatten
     * @param  boolean $preserve_keys Whether or not to preserve array keys.
     *                                Keys from deeply nested arrays will
     *                                overwrite keys from shallowy nested arrays
     * @return array
     */
    public static function arrayFlatten(array $array, $preserve_keys = true)
    {
        $flattened = array();
        array_walk_recursive($array, function ($value, $key) use (&$flattened, $preserve_keys) {
            if ($preserve_keys && !is_int($key)) {
                $flattened[$key] = $value;
            } else {
                $flattened[] = $value;
            }
        });
        return $flattened;
    }

    /**
     * Accepts an array, and returns an array of values from that array as
     * specified by $field. For example, if the array is full of objects
     * and you call util::arrayPluck($array, 'name'), the function will
     * return an array of values from $array[]->name.
     *
     * @param  array $array An array
     * @param  string $field The field to get values from
     * @param  boolean $preserve_keys Whether or not to preserve the
     *                                   array keys
     * @param  boolean $remove_nomatches If the field doesn't appear to be set,
     *                                   remove it from the array
     * @return array
     */
    public static function arrayPluck(array $array, $field, $preserve_keys = true, $remove_nomatches = true)
    {
        $new_list = array();
        foreach ($array as $key => $value) {
            if (is_object($value)) {
                if (isset($value->{$field})) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value->{$field};
                    } else {
                        $new_list[] = $value->{$field};
                    }
                } elseif (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            } else {
                if (isset($value[$field])) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value[$field];
                    } else {
                        $new_list[] = $value[$field];
                    }
                } elseif (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            }
        }
        return $new_list;
    }

    /**
     * Searches for a given value in an array of arrays, objects and scalar
     * values. You can optionally specify a field of the nested arrays and
     * objects to search in.
     *
     * @param  array $array The array to search
     * @param  mixed $search The value to search for
     * @param bool|string $field The field to search in, if not specified
     *                         all fields will be searched
     * @return bool|mixed False on failure or the array key on success
     */
    public static function arraySearchDeep(array $array, $search, $field = false)
    {
        // *grumbles* stupid PHP type system
        $search = (string)$search;
        foreach ($array as $key => $elem) {
            // *grumbles* stupid PHP type system
            $key = (string)$key;
            if ($field) {
                if (is_object($elem) && $elem->{$field} === $search) {
                    return $key;
                } elseif (is_array($elem) && $elem[$field] === $search) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            } else {
                if (is_object($elem)) {
                    $elem = (array)$elem;
                    if (in_array($search, $elem)) {
                        return $key;
                    }
                } elseif (is_array($elem) && in_array($search, $elem)) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * Returns an array containing all the elements of arr1 after applying
     * the callback function to each one.
     *
     * @param  string $callback Callback function to run for each
     *                               element in each array
     * @param  array $array An array to run through the callback
     *                               function
     * @param  boolean $on_nonscalar Whether or not to call the callback
     *                               function on nonscalar values
     *                               (Objects, resources, etc)
     * @return array
     */
    public static function arrayMapDeep(array $array, $callback, $on_nonscalar = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $args = array($value, $callback, $on_nonscalar);
                $array[$key] = call_user_func_array(array(__CLASS__, __FUNCTION__), $args);
            } elseif (is_scalar($value) || $on_nonscalar) {
                $array[$key] = call_user_func($callback, $value);
            }
        }
        return $array;
    }

    /**
     * Merges two arrays recursively and returns the result.
     *
     * @param   array $dest Destination array
     * @param   array $src Source array
     * @param   boolean $appendIntegerKeys Whether to append elements of $src
     *                                      to $dest if the key is an integer.
     *                                      This is the default behavior.
     *                                      Otherwise elements from $src will
     *                                      overwrite the ones in $dest.
     * @return  array
     */
    public static function arrayMergeDeep(array $dest, array $src, $appendIntegerKeys = true)
    {
        foreach ($src as $key => $value) {
            if (is_int($key) and $appendIntegerKeys) {
                $dest[] = $value;
            } elseif (isset($dest[$key]) and is_array($dest[$key]) and is_array($value)) {
                $dest[$key] = static::arrayMergeDeep($dest[$key], $value, $appendIntegerKeys);
            } else {
                $dest[$key] = $value;
            }
        }
        return $dest;
    }

    public static function arrayClean(array $array)
    {
        return array_filter($array);
    }
}