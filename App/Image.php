<?php

class Image{

	private $valid_extensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'];

	private static function generatePath($module){
		$currentDate = getCurrentDate();
		$year = date('Y',  strtotime($currentDate));
		$month = date('M',  strtotime($currentDate));
		$week = getCurrentDate('W');

		$route = $module . DS;
		$yr = $route . $year;
		$mr = $route . $year . DS . $month;
		$wr = $route . $year . DS . $month . DS . $week;
		if(!is_dir(PATH_STORAGE . $yr)) mkdir(PATH_STORAGE . $yr);

		if(!is_dir(PATH_STORAGE . $mr)) mkdir(PATH_STORAGE . $mr);

		if(!is_dir(PATH_STORAGE . $wr)) mkdir(PATH_STORAGE . $wr);

		return $wr . DS;
	}

	public static function save($module, $file, $rel){
		$registry = Registry::getInstance();
		$db = $registry->get('db');
		$path = self::generatePath($module);
		$tmp = $file['tmp_name'];
		$name = $file['name'];
		$type = $file['type'];
		$size = $file['size'];
		$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

		if(move_uploaded_file($tmp, PATH_STORAGE . $path . $name)):
			$sql = "
				INSERT INTO attachments
				SET filename = '%s',
				    path = '%s',
				    filetype = '%s',
				    filesize = %d,
				    modulerel = '%s',
				    relatedid = %d
			";
			$db->prepare(sprintf($sql, $name, $path, $type, $size, $module, $rel));
			$db->exec();
		endif;
	}

	public static function resize(){

	}

}
