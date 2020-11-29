/** 
 * A better implementation of PHP function var_dump();
 * 
 * Provides syntax-highlighted insight even into nested objects,arrays, etc.
 * 
 * ```
 * //Example usage:
 * DataProcessor::xout(['cars' => ['audi','bmw'], 'nothing' => (object)['name' => 'Mario', 'age' => 34]]);  
 * ```
 * 
 * @param mixed $value The variable to print out
 * @param bool $dontDie Default = false. If set to true the script will not be aborted after execution of this function.
 */
public static function xout($value, bool $dontDie = false)
{
	function xout_($value, bool $dontDie, bool $initCall)
	{
		$result = $initCall ? '<div id="xout-container" style="font-family: Courier New; font-weight: bold; font-size: 15px;">' : '';
		
		$isSimpleVar = false;
		switch(gettype($value))
		{
			case 'array' : $result .= '<span>ARRAY</span><br />'.htmlspecialchars('['); break;
			case 'object' : $result .= '<span>OBJECT</span> <span style="color:grey;">' . get_class($value) . '</span><br />'.htmlspecialchars('('); break;
			default : $value = [$value]; $isSimpleVar = true; break;
		}
		
		$result .= '<ul style="list-style-type: none; margin: 0;">';
		
		foreach ($value as $key => $val)
		{
			if (gettype($val) === 'array' || gettype($val) === 'object')
			{
				if (gettype($val) === 'array')
				{
					$result .= '<li><span style="color:blue;">[' . htmlspecialchars($key) . ']</span><b style="color:black;"> '.htmlspecialchars('=>').' </b><span>' . xout_($val, $dontDie, false) . '</span></li>';
				}
				if (gettype($val) === 'object')
				{
					$result .= '<li><span style="color:blue;">' . htmlspecialchars($key) . '</span><b style="color:black;"> '.htmlspecialchars('->').' </b><span>' . xout_($val, $dontDie, false) . '</span></li>';
				}
			}
			else
			{
				$color = 'black';
				switch(gettype($val))
				{
					case 'string' : $color = 'red'; $val = htmlspecialchars('\'').$val.htmlspecialchars('\''); break;
					case 'integer' : $color = 'orange'; break;
					case 'double' : $color = 'teal'; break;
					case 'resource' : $color = 'black'; break;
					case 'boolean' : $color = 'green'; $val = ($val === true) ? 'TRUE' : 'FALSE'; break;
					case 'NULL' : $color = 'grey'; $val = 'NULL'; break;
				}
					
				$result .= '<li>';
				if(!$isSimpleVar)
				{
					if(gettype($value) === 'array')
					{
						$result .= '<span style="color:blue;">[' . htmlspecialchars($key) . ']</span><b style="color:black;"> '.htmlspecialchars('=>').' </b>';
					}
					if(gettype($value) === 'object')
					{
						$result .= '<span style="color:blue;">' . htmlspecialchars($key) . '</span><b style="color:black;"> '.htmlspecialchars('->').' </b>';
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
