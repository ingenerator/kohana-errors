<?php
// Use the simpler exception printer in cli mode to avoid long dumps from tasks or 
// during unit test/spec runs.
if (PHP_SAPI == 'cli')
{
	Kohana_Exception::$error_view = 'errors/cli_generic_error';
}
elseif (Kohana::$environment < Kohana::DEVELOPMENT)
{
	// By default, friendlier error with sanitised trace
	Kohana_Exception::$error_view = 'errors/web_generic_error';
}

