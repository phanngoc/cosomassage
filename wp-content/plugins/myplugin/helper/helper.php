<?php
function log_me($message) {
	if (WP_DEBUG === true) {
            $debug = debug_backtrace()[1];

		if (is_array($message) || is_object($message)) {
			error_log($debug['function']."@".$debug['line']."@".print_r($message, true));
		} else {
			error_log($debug['function']."@".$debug['line']."@".$message);
		}
	}
}