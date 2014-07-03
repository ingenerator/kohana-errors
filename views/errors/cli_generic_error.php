<?php
/**
 * CLI generic error renderer for uncaught exceptions and fatal errors. Shows the error, source and limited trace for
 * debugging.
 *
 * Could be significantly cleaned up by implementing internal replacements for the \Debug:: methods that do not output
 * HTML.
 */
$cols = exec('tput cols', $status, $result);
if ($result !== 0) {
	// Probably running under cron or otherwise detached from a terminal
	$cols = 100;
}

// Render the exception message in a header block
$main_message = $class.' ['.$code.']: '.$message;
$main_message = explode(\PHP_EOL, $main_message);
$main_message = array_map(function($val) use ($cols) {return str_pad($val, $cols);}, $main_message);
echo Minion_CLI::color(implode(\PHP_EOL, $main_message), 'white', 'red').\PHP_EOL;
echo Minion_CLI::color(str_pad(Debug::path($file).'['.$line.']', $cols), 'red').\PHP_EOL;

// Render the source that failed, highlighting the failing line
$source = html_entity_decode(strip_tags(Debug::source($file, $line)));
$source = preg_replace_callback("/^".$line.".+?$/m", function ($err_line) { return Minion_CLI::color($err_line[0], 'white', 'red');}, $source);
echo $source.\PHP_EOL;

// Render the trace
echo Minion_CLI::color(str_pad("TRACE (last 5 entries)", $cols), 'black', 'yellow').\PHP_EOL;
$level = -1;
foreach (Debug::trace($trace) as $i => $step)
{
	$level++;
	$file = $step['file'] ? Debug::path($step['file']).'['.$step['line'].']' : '{PHP internal call}';
	echo Minion_CLI::color(str_pad("#".$level, 3), 'black', 'yellow')." ".Minion_CLI::color($step['function'], 'yellow')." - ".Minion_CLI::color($file, 'cyan').\PHP_EOL;

	if ( ! $step['args'])
	{
		continue;
	}
	$max_name_len = max(array_map(function($key) { return strlen($key);}, array_keys($step['args'])));
	$indent = str_repeat(" ", $max_name_len + 5);
	$wrap_at = $cols - strlen($indent);

	foreach ($step['args'] as $name => $arg)
	{
		$name = str_pad(is_numeric($name) ? "#".$name : "$".$name, $max_name_len + 1);
		echo "  ".Minion_CLI::color($name, 'purple')."  ";
		// FATAL have already converted args to strings
		$arg = ($code === 'Fatal Error') ? $arg : html_entity_decode(strip_tags(Debug::dump($arg)), ENT_QUOTES, 'UTF-8');
		$arg = explode(\PHP_EOL, wordwrap($arg, $wrap_at, \PHP_EOL, TRUE));

		// Don't allow ridiculously long output if the method took a lot of args or a big array
		if (count($arg) > 20) {
			$truncated_count = count($arg) - 20;
			$arg = array_slice($arg, 0, 20);
			$arg[] = '<<<--- '.$truncated_count.' lines truncated --->>>';
		}
		$arg = implode(\PHP_EOL.$indent, $arg);

		echo $arg.\PHP_EOL;
	}
	if ($level >= 5)
	{
		break;
	}
}
