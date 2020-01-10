<?php

function sanitize_sql_input($input,$length,$regex)
{

    $num_of_spaces=substr_count($input,' ');

	$size=strlen($input);
	$input = preg_replace($regex, "", $input);
	$input=substr($input,0,(int)$length);

	if(strlen($input) !== $size && (strlen($input) + $num_of_spaces) !==$size )
		exit;
	
	return $input;
}

?>