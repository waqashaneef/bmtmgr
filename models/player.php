<?php
namespace bmtmgr;

class Player extends \bmtmgr\Model {
	public $id;
	public $season_id;
	public $club_id;
	public $textid;
	public $name;
	public $gender;
	public $birth_year;
	public $nationality;
	public $email;
	public $phone;

	public function __construct($row, $_is_new=true) {
		$this->id = $row['id'];
		$this->season_id = $row['season_id'];
		$this->club_id = $row['club_id'];
		$this->textid = $row['textid'];
		$this->name = $row['name'];
		$this->gender = $row['gender'];
		$this->birth_year = $row['birth_year'];
		$this->nationality = $row['nationality'];
		$this->email = $row['email'];
		$this->phone = $row['phone'];
		$this->_is_new = $_is_new;
	}

	protected static function from_row($row, $_is_new=false) {
		return new static($row, $_is_new);
	}

	public static function exists($season, $textid) {
		return static::fetch_optional('WHERE season_id=? AND textid=?', [$season->id, $textid]) !== null;
	}

	public static function get_rows_with_club_names($add_sql='', $add_params=[], $add_tables=[], $add_fields='') {
		return static::get_all(
			'WHERE user.id=player.club_id ' . $add_sql, $add_params,
			\array_merge(['user'], $add_tables),
			'user.name AS club_name' . ($add_fields ? ', ' . $add_fields : ''),
			function($row) {
				return $row;
			});
	}

	public static function get_by_input($input, $add_sql='', $add_params=[]) {
		$input = \trim($input);
		if ($input == '') {
			return null;
		}
		$inp = $input;
		if (preg_match('/^\s*\((.*?)\)/', $input, $m)) {
			$inp = $m[1];
		}
		$params = \array_merge([':input' => $inp], $add_params);
		return static::fetch_one('WHERE player.textid = :input ' . $add_sql, $params);
	}

	public static function get_in_club_season($club_id, $season_id, $add_sql='') {
		return static::get_all(
			'WHERE club_id=:club_id AND season_id = :season_id ' . $add_sql,
			[':club_id' => $club_id, ':season_id' => $season_id]);
	}

	public static function create($season_id, $club_id, $textid, $name, $gender) {
		return new static([
			'id' => null,
			'season_id' => $season_id,
			'club_id' => $club_id,
			'textid' => $textid,
			'name' => $name,
			'gender' => $gender,
			'birth_year' => null,
			'nationality' => null,
			'email' => null,
			'phone' => null,
		], true);
	}
}
