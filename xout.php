<?php
/** 
 * A more readable implementation of PHP function var_dump();
 * 
 * Provides syntax-highlighted insight even into nested objects,arrays, etc.
 * 
 * ```
 * //Example usage:
 * $var = ['cars' => ['audi','bmw'], 'nothing' => (object)['name' => 'Mario', 'age' => 34]];
 * DataProcessor::xout($var);  
 * ```
 * 
 * @param mixed $value The variable to print out
 * @param bool $dontDie Default = false. If set to true the script will not be aborted after execution of this function.
 */
public static function xout($value, bool $dontDie = false)
{
	function xout_($value, bool $dontDie, bool $initCall)
	{
		//You can define you colors here (CSS value):
		$baseColor = 'black';
		$objectClassColor = 'gray';
		$arrayTypeColor = 'blue';
		$objectTypeColor = 'blue';
		$stringTypeColor = 'red';
		$integerTypeColor = 'orange';
		$doubleTypeColor = 'teal';
		$resourceTypeColor = 'black';
		$booleanTypeColor = 'green';
		$nullTypeColor = 'gray';

		$result = $initCall ? '<div id="xout-container" style="font-family: Courier New; font-weight: bold; font-size: 15px; color:'.$baseColor.';">' : '';

		$isSimpleVar = false;
		switch(gettype($value))
		{
			case 'array' : $result .= '<span>ARRAY</span><br />'.htmlspecialchars('['); break;
			case 'object' : $result .= '<span>OBJECT</span> <span style="color:'.$objectClassColor.';">' . get_class($value) . '</span><br />'.htmlspecialchars('('); break;
			default : $value = [$value]; $isSimpleVar = true; break;
		}

		$result .= '<ul style="list-style-type: none; margin: 0;">';

		foreach ($value as $key => $val)
		{
			if (gettype($val) === 'array' || gettype($val) === 'object')
			{
				if (gettype($val) === 'array')
				{
					$result .= '<li><span style="color:'.$arrayTypeColor.';">[' . htmlspecialchars($key) . ']</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('=>').' </b><span>' . xout_($val, $dontDie, false) . '</span></li>';
				}
				if (gettype($val) === 'object')
				{
					$result .= '<li><span style="color:'.$objectTypeColor.';">' . htmlspecialchars($key) . '</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('->').' </b><span>' . xout_($val, $dontDie, false) . '</span></li>';
				}
			}
			else
			{
				$color = 'black';
				switch(gettype($val))
				{
					case 'string' : $color = $stringTypeColor; $val = htmlspecialchars('\'').$val.htmlspecialchars('\''); break;
					case 'integer' : $color = $integerTypeColor; break;
					case 'double' : $color = $doubleTypeColor; break;
					case 'resource' : $color = $resourceTypeColor; break;
					case 'resource (closed)' : $color = $resourceTypeColor; break;
					case 'boolean' : $color = $booleanTypeColor; $val = ($val === true) ? 'TRUE' : 'FALSE'; break;
					case 'NULL' : $color = $nullTypeColor; $val = 'NULL'; break;
				}

				$result .= '<li>';
				if(!$isSimpleVar)
				{
					if(gettype($value) === 'array')
					{
						$result .= '<span style="color:'.$arrayTypeColor.';">[' . htmlspecialchars($key) . ']</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('=>').' </b>';
					}
					if(gettype($value) === 'object')
					{
						$result .= '<span style="color:'.$objectTypeColor.';">' . htmlspecialchars($key) . '</span><b style="color:'.$baseColor.';"> '.htmlspecialchars('->').' </b>';
					}
				}
				$result .= '<span style="color:'.$color.';">' . htmlspecialchars($val) . '</span></li>';
			}
		}

		$result .= '</ul>';

		if(!$isSimpleVar)
		{
			switch(gettype($value))
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
	//Lets go:
	xout_($value, $dontDie, true);
}
