<?php
class Xout
{
    private function __construct(){}

    const FONT_FAMILY = 'Courier New';
	const FONT_WEIGHT = 'normal';
	const FONT_SIZE = '16px';
    const INDENT_SIZE = '2rem';

	const BASE_COLOR = '#333';
	const CLASS_COLOR = '#267f99';
	
    const ARRAY_COLOR = '#777';
    const OBJ_PROP_COLOR = '#333';

	const OBJ_COLOR = '#777';
	const STRING_COLOR = '#a31515';
	const INT_COLOR = '#098658';
	const DOUBLE_COLOR = '#098658';
	const RES_COLOR = 'purple';
	const RES_CLOSED_COLOR = 'plum';
	const BOOL_COLOR = '#00f';
	const NULL_COLOR = '#00f';
    const FUNCTION_COLOR = '#00f';

    private static $braceColors = ['#00f', '#319331', '#7b3814']; //PHP 5.4 does not support array constants
    const BRACE_STYLE_ALLMAN = true; 
    

    /** 
     * A more readable implementation of PHPs `var_dump()` or `print_r()` function.
     * Provides syntax-highlighted insight into nested objects, arrays, etc.
     * 
     * @param mixed $value The variable to print out
     * @param bool $return Return the resulting HTML insted of sending it with echo()
     * @param bool $dontDie If set to `TRUE` the script will not be aborted. This parameter has no effect if `$return` is set to `TRUE`
     * @return void|string If `$return` is set to `TRUE` the resulting HTML is returned as string
     * 
     * ```
     * //Example usage:
     * $something = ['cars' => ['audi','bmw'], 'nothing' => (object)['name' => 'Mario', 'age' => 34]];
     * xout($something);  
     * ```
     */
    public static function xout($value, $return = false, $dontDie = false)
    {
        self::$res = sprintf('<div id="xout-container" style="font-family:%s;font-size:%s;color:%s;font-weight:%s;">', self::FONT_FAMILY, self::FONT_SIZE, self::BASE_COLOR, self::FONT_WEIGHT);
        self::_xout($value, true);
        self::$res .= '</div>';

        if($return)
        {
            return self::$res;
        }

        echo self::$res;

        if($dontDie == false)
        {
            die;        
        }
    }

    private static function _xout($value)
    {
        /** @var string Value Type */
        $t = self::get_type($value);
        /** @var string Value Class Name */
        $cn = $t === 'object' ? self::get_classname($value) : '';
        //We define closure as a separate type, i think its ok, after all it IS a function
        $t = $cn === 'Closure' ? 'closure' : $t;
        /** @var bool Value is Simple/Scalar Type */
        $s = !($t === 'array' || $t === 'object');
        /** @var bool Value is an empty object or array */
        $eo = !$s && self::is_e($value); 
    

        self::pre($t,$eo,$cn);

        $value = $s ? [$value] : $value;
        foreach($value as $key => $val)
        {
            $t2 = self::get_type($val);
            switch($t2)
            {
                case 'array'             : self::main($t, $t2, $key, $val, ''); break;
                case 'object'            : self::main($t, $t2, $key, $val, ''); break;
                case 'closure'           : self::main($t, $t2, $key, sprintf('<span style="color:%s;">function</span>()', self::FUNCTION_COLOR), self::BASE_COLOR); break;
                case 'string'            : self::main($t, $t2, $key, sprintf('%s%s%s', '&quot;', htmlspecialchars($val), '&quot;'), self::STRING_COLOR); break;
                case 'integer'           : self::main($t, $t2, $key, (string)$val, self::INT_COLOR); break;
                case 'double'            : self::main($t, $t2, $key, (string)$val, self::DOUBLE_COLOR); break;
                case 'resource'          : self::main($t, $t2, $key, sprintf('resouce (%s)', get_resource_type($val)), self::RES_COLOR); break;
                case 'resource (closed)' : self::main($t, $t2, $key, 'resource (closed)', self::RES_CLOSED_COLOR); break;
                case 'boolean'           : self::main($t, $t2, $key, $val === true ? 'TRUE' : 'FALSE', self::BOOL_COLOR); break;
                case 'NULL'              : self::main($t, $t2, $key, 'NULL', self::NULL_COLOR); break;
            }
        }

        self::post($t,$eo);

    }

    private static function main($t, $t2, $key, $val, $color)
    {
        self::$res .= '<li>';
        switch($t)
        {
            case 'array' : self::$res .= self::format_array_key($key); break;
            case 'object' : self::$res .= self::format_obj_prop($key); break;
        }

        switch($t2)
        {
            case 'array' :
            case 'object' : self::_xout($val); break;
            default : self::$res .= sprintf('<span style="color:%s;">%s</span>', $color, $val); break;
        }

        self::$res .= '</li>';
    }

    private static function format_obj_prop($prop)
    {
        return sprintf('%s -&gt; ', htmlspecialchars((string)$prop));
    }    

    private static function format_array_key($key)
    {
        return sprintf('%s%s%s =&gt; ', self::square_brace(true), self::format_value($key, gettype($key)) , self::square_brace(false));
    }
    
    private static function square_brace($open)
    {
        self::$squareBraceLvl += $open ? 1 : -1;
        $i = (self::$squareBraceLvl - (int)$open) % count(self::$braceColors);
        
        return sprintf('<span style="color:%s;">%s</span>', self::$braceColors[$i], $open ? '[' : ']');
    }

    private static function curly_brace($open)
    {
        self::$curlyBraceLvl += $open ? 1 : -1;
        $i = (self::$curlyBraceLvl - (int)$open) % count(self::$braceColors);
        
        return sprintf('<span style="color:%s;">%s</span>', self::$braceColors[$i], $open ? '{' : '}');
    }

    private static function format_value($v, $t)
    {
        switch($t)
        {
            case 'closure'           : return sprintf('<span style="color:%s;">function</span>()', self::FUNCTION_COLOR);
            case 'string'            : return sprintf('<span style="color:%s;">%s%s%s</span>', self::STRING_COLOR, '&quot;', htmlspecialchars($v), '&quot;');
            case 'integer'           : return sprintf('<span style="color:%s;">%s</span>', self::INT_COLOR, $v);
            case 'double'            : return sprintf('<span style="color:%s;">%s</span>', self::DOUBLE_COLOR, $v);
            case 'resource'          : return sprintf('<span style="color:%s;">resource (%s)</span>', self::RES_COLOR, get_resource_type($v));
            case 'resource (closed)' : return sprintf('<span style="color:%s;">resource (closed)</span>', self::RES_CLOSED_COLOR);
            case 'boolean'           : return sprintf('<span style="color:%s;">%s</span>', self::BOOL_COLOR, $v ? 'TRUE' : 'FALSE');
            case 'NULL'              : return sprintf('<span style="color:%s;">NULL</span>', self::NULL_COLOR);
        }

        return '';
    }

    private static function post($t, $eo)
    {
        if($eo)
        {
            return;
        }

        self::$res .= '</ul>';
        self::$res .= $t === 'array' ? self::square_brace(false) : '';
        self::$res .= $t === 'object' ? self::curly_brace(false) : '';
    }

    private static function pre($t, $eo, $cn)
    {
        self::$res .= $t === 'array' ? self::format_array($eo) : ''; 
        self::$res .= $t === 'object' ? self::format_object($eo, $cn) : '';
        self::$res .= $eo ? '' : sprintf('<ul style="list-style-type:none;margin:0;padding-left:%s">', self::INDENT_SIZE);
    }

    private static function format_object($eo, $cn)
    {
        $s = sprintf('<span style="color:%s">OBJECT</span> <span style="color:%s;">%s</span>', self::OBJ_COLOR, self::CLASS_COLOR, $cn);
        $s .= $eo ? '' : sprintf('%s%s', self::BRACE_STYLE_ALLMAN ? '<br/>' : '', self::curly_brace(true));
        return $s;
    }

    private static function format_array($eo)
    {
        $s = sprintf('<span style="color:%s;">ARRAY</span> ', self::ARRAY_COLOR);
        $s .=  $eo ? '<i>empty</i>' : sprintf('%s%s', self::BRACE_STYLE_ALLMAN ? '<br/>' : '', self::square_brace(true));
        return $s;
    }

    private static function is_e($value)
    {
        foreach($value as $k => $v)
        {
            return false;
        }

        return true;
    }

    private static function get_type($value)
    {
        $type = gettype($value);

        return $type === 'object' && get_class($value) === 'Closure' ? 'closure' : $type;
    }

    private static function get_classname($value)
    {
        $type = self::get_type($value);
        return $type === 'object' ? get_class($value) : '';
    }

    /** @var string The result */
    private static $res = '';
    /** @var int Monitor level of nested [ ] brackts */
    private static $squareBraceLvl = 0;
    /** @var int Monitor level of nested { } brackts */
    private static $curlyBraceLvl = 0;
}

/** @see Xout::xout() */
function xout($value, $return = false, $dontDie = false)
{
    return Xout::xout($value, $return, $dontDie);
}