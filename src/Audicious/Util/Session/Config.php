<?php
namespace Audicious\Util\Session;

class Config {

	/**
     * Private list of php's ini values for ext/session
     * null values will default to the php.ini value, otherwise
     * the value below will overwrite the default ini value, unless
     * the user has set an option explicity with setOptions()
     *
     * @var array
     */
    static private $defaultOptions = array(
        'save_path'                 => null,
        'name'                      => null, /* this should be set to a unique value for each application */
        'save_handler'              => null,
        //'auto_start'                => null, /* intentionally excluded (see manual) */
        'gc_probability'            => null,
        'gc_divisor'                => null,
        'gc_maxlifetime'            => null,
        'serialize_handler'         => null,
        'cookie_lifetime'           => null,
        'cookie_path'               => null,
        'cookie_domain'             => null,
        'cookie_secure'             => null,
        'cookie_httponly'           => null,
        'use_cookies'               => null,
        'use_only_cookies'          => 'on',
        'referer_check'             => null,
        'entropy_file'              => null,
        'entropy_length'            => null,
        'cache_limiter'             => null,
        'cache_expire'              => null,
        'use_trans_sid'             => null,
        'bug_compat_42'             => null,
        'bug_compat_warn'           => null,
        'hash_function'             => null,
        'hash_bits_per_character'   => null
    );

	public static function loadIniSettings(array $options = array()) {

		foreach($options as $name => $value) {
			$optionName = strtolower($name);
			if(in_array($optionName, self::defaultOptions)) {
				ini_set("session.$optionName", $value);
			}
		}
	}
}