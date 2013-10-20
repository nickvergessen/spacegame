<?php
/**
*
* @package SpaceGame Favorites
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame;

class favorites
{
	/* @var \phpbb\db\driver\driver */
	protected $db;

	/* @var \schilljs\spacegame\tables */
	protected $tables;

	/* @var \schilljs\spacegame\user */
	protected $space_user;

	/* @var int */
	protected $user_id;

	/* @var array */
	protected $quadrants;

	/* @var array */
	protected $planets;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver	$db
	* @param \schilljs\spacegame\tables	$tables
	* @param \schilljs\spacegame\user	$space_user
	*/
	public function __construct(\phpbb\db\driver\driver $db, \schilljs\spacegame\tables $tables, \schilljs\spacegame\user $space_user)
	{
		$this->db = $db;
		$this->space_user = $space_user;
		$this->tables = $tables;
		$this->user_id = (int) $space_user->get_user_id();

		$this->load_favorites();
	}

	public function load_favorites()
	{
		$sql = 'SELECT *
			FROM ' . $this->tables->get('favorites') . '
			WHERE user_id = ' . $this->user_id . '
			ORDER BY mquad_id, quad_id, row_id ASC';
		$result = $this->db->sql_query($sql);

		$this->quadrants = $this->planets = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($row['row_id'] == 0)
			{
				$this->quadrants[(int) $row['mquad_id']][] = (int) $row['quad_id'];
			}
			else
			{
				$this->planets[(int) $row['mquad_id']][(int) $row['quad_id']][] = (int) $row['row_id'];
			}
		}
		$this->db->sql_freeresult($result);
	}

	public function add($mquad, $quad, $planet = 0)
	{
		if ($planet && isset($this->planets[$mquad][$quad]) && in_array($planet, $this->planets[$mquad][$quad]))
		{
			return false;
		}
		else if (!$planet && isset($this->quadrants[$mquad]) && in_array($quad, $this->quadrants[$mquad]))
		{
			return false;
		}

		$sql_ary = array(
			'user_id'		=> $this->user_id,
			'quad_id'		=> $quad,
			'mquad_id'		=> $mquad,
			'row_id'		=> $planet,
		);

		$sql = 'INSERT INTO ' . $this->tables->get('favorites') . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);

		$this->load_favorites();

		return true;
	}

	public function remove($mquad, $quad, $planet = 0)
	{
		if ($planet && (!isset($this->planets[$mquad][$quad]) || !in_array($planet, $this->planets[$mquad][$quad])))
		{
			return false;
		}
		else if (!$planet && (!isset($this->quadrants[$mquad]) || !in_array($quad, $this->quadrants[$mquad])))
		{
			return false;
		}

		$sql = 'DELETE FROM ' . $this->tables->get('favorites') . '
			WHERE user_id = ' . $this->user_id . '
				AND row_id = ' . $planet . '
				AND quad_id = ' . $quad . '
				AND mquad_id = ' . $mquad;
		$this->db->sql_query($sql);

		$this->load_favorites();

		return true;
	}

	public function get_quadrants()
	{
		return $this->quadrants;
	}

	public function get_planets()
	{
		return $this->planets;
	}
}
