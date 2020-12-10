<?php
declare(strict_types = 1);
/** 
 * A more readable implementation of PHP function `var_dump()`. Provides syntax-highlighted insight even into nested objects,arrays, etc.
 * 
 * @param mixed $value The variable to print out
 * @param bool $dontDie If set to `TRUE` the script will not be aborted after execution of this function.
 * @param bool $initCall Dont specifiy this parameter. xout will use it cause it call itself.
 * @return void
 * 
 * ```
 * //Example usage:
 * $var = ['cars' => ['audi','bmw'], 'nothing' => (object)['name' => 'Mario', 'age' => 34]];
 * xout($var);  
 * ```
 */
function xout($value, bool $dontDie = false, bool $initCall = true)
{
	//You can define your own syntax coloring here.
	$baseColor = 'black';
	$objectClassColor = 'gray';
	$arrayTypeColor = 'blue';
	$objectTypeColor = 'blue';
	$stringTypeColor = 'red';
	$integerTypeColor = 'orange';
	$doubleTypeColor = 'teal';
	$resourceTypeColor = 'purple';
	$resourceClosedTypeColor = 'plum';
	$booleanTypeColor = 'green';
	$nullTypeColor = 'gray';

	$result = $initCall ? '<div id="xout-container" style="font-family: Courier New; font-weight: bold; font-size: 15px; color:'.$baseColor.';">' : '';

	$isSimpleVar = false;
	$valueType = gettype($value);
	switch($valueType)
	{
		case 'array' : $result .= '<span>ARRAY</span><br />'.htmlspecialchars('['); break;
		case 'object' : $result .= '<span>OBJECT</span> <span style="color:'.$objectClassColor.';">' . get_class($value) . '</span><br />'.htmlspecialchars('('); break;
		default : $value = [$value]; $isSimpleVar = true; break;
	}

	$result .= '<ul style="list-style-type: none; margin: 0;">';

	foreach ($value as $key => $val)
	{
		$valType = gettype($val);
		if ($valType === 'array' || $valType === 'object')
		{
			if ($valueType === 'array')
			{
				$result .= '<li><span style="color:'.$arrayTypeColor.';">[' . htmlspecialchars(strval($key)) . ']</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('=>').' </b><span>' . xout($val, $dontDie, false) . '</span></li>';
			}
			if ($valueType === 'object')
			{
				$result .= '<li><span style="color:'.$objectTypeColor.';">' . htmlspecialchars(strval($key)) . '</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('->').' </b><span>' . xout($val, $dontDie, false) . '</span></li>';
			}
		}
		else
		{
			$color = 'black';
			switch($valType)
			{
				case 'string' : $color = $stringTypeColor; $val = htmlspecialchars('\'').$val.htmlspecialchars('\''); break;
				case 'integer' : $color = $integerTypeColor; $val = strval($val); break;
				case 'double' : $color = $doubleTypeColor; $val = strval($val); break;
				case 'resource' : $color = $resourceTypeColor; $val = 'resource ('.get_resource_type($val).')'; break;
				case 'resource (closed)' : $color = $resourceClosedTypeColor; $val = 'resource (closed)'; break;
				case 'boolean' : $color = $booleanTypeColor; $val = ($val === true) ? 'TRUE' : 'FALSE'; break;
				case 'NULL' : $color = $nullTypeColor; $val = 'NULL'; break;
			}

			$result .= '<li>';
			if(!$isSimpleVar)
			{
				if($valueType === 'array')
				{
					$result .= '<span style="color:'.$arrayTypeColor.';">[' . htmlspecialchars(strval($key)) . ']</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('=>').' </b>';
				}
				if($valueType === 'object')
				{
					$result .= '<span style="color:'.$objectTypeColor.';">' . htmlspecialchars(strval($key)) . '</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('->').' </b>';
				}
			}
			$result .= '<span style="color:'.$color.';">' . htmlspecialchars($val) . '</span></li>';
		}
	}

	$result .= '</ul>';

	if(!$isSimpleVar)
	{
		switch($valueType)
		{
			case 'array' : $result .= htmlspecialchars(']'); break;
			case 'object' : $result .= htmlspecialchars(')'); break;
		}
	}

	$result .= $initCall ? '</div>' : '';

	if($initCall) //Finished
	{
		echo($result);
		if(!$dontDie)
		{
			die();
		}
	}
	else //End of recursive call
	{
		return $result; 
	}
}
