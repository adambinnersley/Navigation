<?php

namespace Nav\Operators;

class Check
{
    /**
     * Check to make sure the value is not false and returns the correct value if it is or not
     * @param mixed $variable The value you are checking if it is set
     * @param mixed $valueIfSet If the value is not false this value will be returned
     * @param mixed $valueIfEmpty If the value is false this value will be returned
     * @return mixed
     */
    public static function checkIfSet($variable, $valueIfSet, $valueIfEmpty = '')
    {
        if ($variable) {
            return $valueIfSet;
        }
        return $valueIfEmpty;
    }
    
    /**
     * Check to make sure the value is not empty and returns the correct value if it is or not
     * @param mixed $variable The value you are checking if it is empty
     * @param mixed $valueIfSet If the value is not empty this value will be returned
     * @param mixed $valueIfEmpty If the value is empty this value will be returned
     * @return mixed
     */
    public static function checkIfEmpty($variable, $valueIfSet, $valueIfEmpty = '')
    {
        if (!empty(trim($variable))) {
            return $valueIfSet;
        }
        return $valueIfEmpty;
    }
    
    /**
     * Check if a string is set and not empty
     * @param mixed $value The value you are checking if its a string
     * @return boolean
     */
    public static function checkIfStringSet($value)
    {
        if (!empty(trim($value)) && is_string($value)) {
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param int $value
     * @param int $greaterThan
     * @param mixed $valueIfSet If the value is not empty this value will be returned
     * @param mixed $valueIfEmpty If the value is empty this value will be returned
     * @return boolean#
     */
    public static function checkIfGreaterThan($value, $greaterThan, $valueIfSet, $valueIfEmpty = '')
    {
        if ($value > $greaterThan) {
            return $valueIfSet;
        }
        return $valueIfEmpty;
    }
    
    public static function checkIfGreaterThanEqual($value, $greaterThan, $valueIfSet, $valueIfEmpty = '')
    {
        if ($value >= $greaterThan) {
            return $valueIfSet;
        }
        return $valueIfEmpty;
    }
}
