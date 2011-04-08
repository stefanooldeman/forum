<?php
namespace Audicious\Util;

use Audicious\Util\Session\Config as Config;

/**
 * Description of Session.. a striped version from Zend_Session
 *
 * @author stefanooldeman
 */
class Session extends Session\Storage {

	static protected $destroyed;
	static protected $sessionStarted;
	static protected $sessionCookieDeleted;
	static protected $regenerateIdState;
	
	/**
     * start() - Start the session.
     *
     * @param bool|array $options  OPTIONAL Either user supplied options, or flag indicating if start initiated automatically
     * @throws SessionException
     * @return void
     */
    public static function start($options = false)
    {
        if (self::$sessionStarted && self::$destroyed) {
            require_once 'Zend/Session/Exception.php';
            throw new SessionException('The session was explicitly destroyed during this request, attempting to re-start is not allowed.');
        }

        if (self::$sessionStarted) {
            return; // Go Home Early
        }

		if($options !== false) {
			Config::loadIniSettings($options);
		}

        $filename = $linenum = null;
        if (headers_sent($filename, $linenum)) {
            throw new SessionException("Session must be started before any output has been sent to the browser;"
               . " output started in {$filename}/{$linenum}");
        }

        // See http://www.php.net/manual/en/ref.session.php for explanation
        if (defined('SID')) {
            throw new SessionException('session has already been started by session.auto-start or session_start()');
        }

		$startedCleanly = session_start();

		if (!$startedCleanly) {
			session_write_close();
		}
        self::$sessionStarted = true;
    }

	public static function isStarted() {
		return self::$sessionStarted;
	}

	public function destroy($removeCookie) {
		session_destroy();
		self::$_destroyed = true;
		
		if($removeCookie) {
			self::expireSessionCookie();
		}
	}

	/**
     * getId() - get the current session id
     *
     * @return string
     */
	public static function getId() {
		return session_id();
	}

	/**
	* regenerateId() - Regenerate the session id.  Best practice is to call this after
	* session is started.  If called prior to session starting, session id will be regenerated
	* at start time.
	*
	* @throws Zend_SessionException
	* @return void
	*/
	public static function regenerateId() {
		if (headers_sent($filename, $linenum)) {
			throw new SessionException("You must call " . __CLASS__ . '::' . __FUNCTION__ .
			"() before any output has been sent to the browser; output started in {$filename}/{$linenum}");
		}

		if (self::$sessionStarted && self::$regenerateIdState <= 0) {
			session_regenerate_id(true);
			self::$regenerateIdState = 1;
		} else {
			self::$regenerateIdState = -1;
		}
	}

	/**
	* rememberUntil() - This method does the work of changing the state of the session cookie and making
	* sure that it gets resent to the browser via regenerateId()
	*
	* @param int $seconds
	* @return void
	*/
	public static function rememberUntil($seconds = 0) {
		$cookieParams = session_get_cookie_params();

		session_set_cookie_params(
			$seconds,
			$cookieParams['path'],
			$cookieParams['domain'],
			$cookieParams['secure']
		);

		// normally "rememberMe()" represents a security context change, so should use new session id
		self::regenerateId();
	}

	/**
	* expireSessionCookie() - Sends an expired session id cookie, causing the client to delete the session cookie
	*
	* @return void
	*/
	public static function expireSessionCookie() {

		if (self::$sessionCookieDeleted) {
			return;
		}

		self::$sessionCookieDeleted = true;

		if (isset($_COOKIE[session_name()])) {
			$cookie_params = session_get_cookie_params();

			setcookie(
				session_name(),
				false,
				315554400, // strtotime('1980-01-01'),
				$cookie_params['path'],
				$cookie_params['domain'],
				$cookie_params['secure']
			);
		}
	}

}

class SessionException extends \Exception {}