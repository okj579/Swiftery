<?php


namespace Swiftery;


class SwiftPatternString {
    /**
     * Convert SWIFT-style pattern string to regular expression
     *
     * @param string $pattern
     * @return string
     */
    public static function toRegex($pattern) {
        $pattern = preg_quote($pattern);
        $pattern = preg_replace_callback('/(?<length>\d+)(?<fixed>\\\!)?(?<type>[nace])/', function($match){
            static $types = array(
                'n' => '\d',
                'a' => '[A-Z]',
                'c' => '[A-Za-z0-9]',
                'e' => '\s',
            );

            $type = $match['type'];
            $length = $match['length'];
            $fixed = strlen($match['fixed']) > 0;

            return '(' . $types[$type] . '{' . ($fixed ? '' : '0,') . $length . '})';
        }, $pattern);
        return "/^$pattern$/";
    }
}
