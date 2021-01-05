<?php

// remove all characters in '$regex' from '$input'. After we check if there is a change in length. if there is, exit => (this will likely be someone trying to hack it )
function sanitize_sql_input($input, $regex)
{
	$size = strlen($input);
	$input = preg_replace($regex, "", $input);
	
	if(strlen($input) !== $size)  
		exit;
	return $input;
}

?>