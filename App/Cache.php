<?php

class Cache
{
	public $expire;

	/** @var string $path path of cache dir */
	private static $path;

	/** @var bool $instanciated flag for first instance */
	private static $instanciated = false;

	public function __construct($expire = 3600)
	{
		$this->expire = $expire;
		self::$path = ROOT . PATH_CACHE . '.ht.';

		if (self::$instanciated === false) {
			self::$instanciated = true;

			$files = glob(self::$path . '*');

			if ($files) {
				foreach ($files as $file) {
					$time = substr(strrchr($file, '.'), 1);
					if ($time < time()) {
						if (file_exists($file)) {
							unlink($file);
						}
					}
				}
			}
		}
	}

	public function get($key)
	{
		$files = glob(self::$path . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		if ($files) {
			$handle = fopen($files[0], 'r');
			flock($handle, LOCK_SH);
			$data = fread($handle, filesize($files[0]));
			flock($handle, LOCK_UN);
			fclose($handle);
			return json_decode($data, true);
		}

		return false;
	}

	public function getByPartOfString($key){
		$files = glob(self::$path . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '*');
		return $files? $files: [];
	}

	public function set($key, $value)
	{
		$this->delete($key);
		$file = self::$path . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);
		$handle = fopen($file, 'w');
		flock($handle, LOCK_EX);
		fwrite($handle, json_encode($value));
		fflush($handle);
		flock($handle, LOCK_UN);
		fclose($handle);
	}

	public function delete($key, $partOfString = false)
	{
		$files = false;
		if(!$partOfString):
			$files = glob(self::$path . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		else:
			// dots are used for a reason
			// if you want to delete all in domain of OnlineStore
			// name all your cache using dots
			// set('OnlineStore.Foo', true);
			// set('OnlineStore.Bar', true);
			// set('OnlineStore.Baz', true);
			// calling delete('OnlineStore') will delete all of them
			$files = glob(self::$path . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '*');
		endif;

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}
	}
}
