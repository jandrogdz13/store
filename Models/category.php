<?php

class categoryModel extends Model{

	public function __construct(){
		parent::__construct();
	}

	public function Get_Category_Id(String $keyword): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT category_id
			FROM store_category
			WHERE store_category.keyword = :keyword
		';
		$db->prepare($sql, [
			'keyword' => $keyword
		]);
		return $db->fetch();
	}

	public function Get_Records(): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT *
			FROM store_category
			WHERE store_category.parent_id = 0
		';
		$db->prepare($sql);
		$records = $db->fetchAll();

		// Get Sub Categories
		$sql = '
			SELECT *
			FROM store_category
			WHERE store_category.parent_id != 0
		';
		$db->prepare($sql);
		$sub = $db->fetchAll();

		foreach($records as &$record):
			$parent_id = $record['category_id'];
			$record['sub'] = array_filter($sub, function($item) use($parent_id){
				return $item['parent_id'] == $parent_id;
			});
		endforeach;
		unset($record);
		return $records;
	}

	public function Get_Record($record_id): array
	{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT *
			FROM store_category
			WHERE store_category.category_id = :record_id
		';
		$db->prepare($sql, [
			'record_id' => $record_id
		]);
		return $db->fetch();
	}
}
