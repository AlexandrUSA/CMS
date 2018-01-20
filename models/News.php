<?php
class News
{

	/** Returns single news items with specified id
	* @rapam integer &id
	*/

	public static function getNewsItemByID($id)
	{
		$id = intval($id);
		if ($id) {
			$db = Db::getConnection();
			$result = $db->query('SELECT * FROM news WHERE id=' . $id);
			$result->setFetchMode(PDO::FETCH_ASSOC);
			$newsItem = $result->fetch();
			return $newsItem;
		}
	}

	/**
	* Returns an array of news items
	*/
	public static function getNewsList() {
		$db = Db::getConnection();
		$newsList = [];
		$result = $db->query('SELECT id, title, date, short_content FROM news ORDER BY id ASC LIMIT 10');

		for($i = 0; $row = $result->fetch(); $i++) {
			$newsList[$i]['id'] = $row['id'];
			$newsList[$i]['title'] = $row['title'];
			$newsList[$i]['date'] = $row['date'];
			$newsList[$i]['short_content'] = $row['short_content'];
		}
		return $newsList;	
	}

}