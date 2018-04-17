<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('passwd_hash')) {
	function passwd_hash($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}
}
if (!function_exists('passwd_verify')) {
	function passwd_verify($password, $hash) {
		return password_verify($password, $hash);
	}
}
