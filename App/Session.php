<?php

class Session{

	protected static $expire;
	protected static $log;

    public static function init(){
        session_start();
        //$timeout = SESSION_TIME > 0? SESSION_TIME: 1;
        //Session::set('expire', time() + (3600 * 24 * $timeout));
        Session::set('expire', SESSION_TIME * 60); // 1 minutos
		self::$log = Log::getInstance('Session');
    }

    public static function destroy($key = false){
        if($key):
            if(is_array($key)):
                foreach($key as $s):
                    if(isset($_SESSION[$s]))
						unset($_SESSION[$s]);
                endforeach;
            else:
                if(isset($_SESSION[$key]))
					unset($_SESSION[$key]);
            endif;
        else:
			//session_regenerate_id(true);
            session_destroy();
			setcookie("COOKIE_KEEP_ME", NULL, time() - 3600, "/");
			setcookie("COOKIE_USER", NULL, time() - 3600, "/");
			/*setcookie("COOKIE_USER_EMAIL", NULL, time() - 3600, "/");
			setcookie("COOKIE_USER_PASS", NULL, time() - 3600, "/");*/
        	redirect();
        endif;
    }

    public static function set($key, $value){
        if(!empty($key)) $_SESSION[$key] = $value;
    }

    public static function get($key){
        return $_SESSION[$key] ?? false;
    }

    public static function has($key): bool{
        return isset($_SESSION[$key]);
    }

	public static function get_all(){
		return $_SESSION;
	}

    public static function authenticated(){
		//self::$log->write(['time' => time(), 'timeout' => time() - Session::get('timeout'), 'expire' => Session::get('expire')]);

		if(Session::has('authenticated')):
			$customer = Session::get('customer');
			Session::set("timeout", time());
			/*$registry = Registry::getInstance();
			$request = $registry->get('request');
			$referer = $request->server['HTTP_REFERER'] ?? false;
			if($referer && !in_array($request->getController(), ['login', 'cron', 'panel']))
				setcookie("LAST_MODULE_{$user['userid']}", $referer, time() + 31622400, "/");
			if(isset($_COOKIE['COOKIE_KEEP_ME']) && $_COOKIE['COOKIE_KEEP_ME']):
				Session::set("timeout", time());
			else:
				Session::time();
			endif;*/
		endif;
    }

    public static function time(){
    	if(Session::has('timeout')):
			if(time() - Session::get('timeout') > Session::get('expire')):
				redirect('account/logout');
			else:
				Session::set("timeout", time());
			endif;
		else:
			redirect('account/logout');
		endif;
    }
}
