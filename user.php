<?php
/**
*
* @package SpaceGame Core
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace schilljs\spacegame;

class user
{
	/* @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \schilljs\spacegame\tables */
	protected $tables;

	/** @var int */
	protected $user_id;

	/**
	* User data from the table
	* @var array
	*/
	protected $data;

	/**
	* List with user's planets
	* @var array
	*/
	protected $planets;

	/**
	* User's currently selected planet
	* @var int
	*/
	protected $cur_planet;

	/**
	* User's base planet
	* @var int
	*/
	protected $home_planet;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver $db
	* @param \schilljs\spacegame\tables $tables
	*/
	public function __construct(\phpbb\db\driver\driver $db, \schilljs\spacegame\tables $tables)
	{
		$this->db = $db;
		$this->tables = $tables;
	}

	public function get_user_by_id($user_id)
	{
		$this->user_id = (int) $user_id;

		$sql = 'SELECT *
			FROM ' . $this->tables->get('users') . '
			WHERE user_id = ' . $this->user_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $this;
	}

	public function get_ally()
	{
		return (int) $this->data['ally_id'];
	}

	public function get_cur_planet()
	{
		return (int) $this->data['user_cur_planet'];
	}

	public function num_planets()
	{
		return sizeof($this->planets);
	}

	public function load_planets()
	{
		$this->get_user_planets($this->user_id);

		if ($this->num_planets() == 0)
		{
			// New user!
			return;
		}

		if (!in_array($this->cur_planet, $this->planets))
		{
			$this->set_cur_planet($this->home_planet);
			$user_cur_quad = $this->planet_data[$this->cur_planet]['mquad_id'] . ':' . $this->planet_data[$this->cur_planet]['quad_id'];

			$sql = 'UPDATE ' . $this->tables->get('users') . '
				SET user_cur_planet = ' . $this->cur_planet . '
				WHERE user_id = ' . $this->user_id;
			$this->db->sql_query($sql);
		}
		$this->cur_mquad = (int) $this->planet_data[$this->cur_planet]['mquad_id'];
		$this->cur_quad = (int) $this->planet_data[$this->cur_planet]['quad_id'];

		$this->pdata = $this->planet_data[$this->cur_planet];
	}

	protected function get_user_planets($user_id)
	{
		$sql = 'SELECT p.*, n.*
			FROM ' . $this->tables->get('planets') . ' p
			LEFT JOIN ' . $this->tables->get('fleets') . ' n
				ON (p.fleet_id = n.fleet_id)
			WHERE p.user_id = ' . $user_id . '
			ORDER BY mquad_id, quad_id, row_id ASC';
		$result = $this->db->sql_query($sql);

		$status_planets = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($this->user_id == $row['user_id'])
			{
				#$this->template->assign_block_vars('jumpbox_planets', array(
				#	'PLANET_ID'		=> $row['planet_id'],
				#	'PLANET_NAME'	=> $row['planet_name'] . ' [' . $row['mquad_id'] . ':' . $row['quad_id'] . ':' . $row['row_id'] . ']',
				#	'SELECTED'		=> ($row['planet_id'] == $this->cur_planet) ? ' selected="selected"' : '',
				#));

				if ($row['planet_base'])
				{
					$this->home_planet = (int) $row['planet_id'];
				}
				if ($row['planet_status'])
				{
					$status_planets[] = (int) $row['planet_id'];
				}
				$this->planets[] = (int) $row['planet_id'];
			}
			$row['planet_change_status'] = 0;
			$this->planet_data[$row['planet_id']] = $row;
		}
		$this->db->sql_freeresult($result);

		foreach ($status_planets as $planet_id)
		{
			$sql = 'SELECT run_time
				FROM ' . $this->tables->get('queue') . '
				WHERE planet_id = ' . (int) $planet_id . '
					AND queue_mode = ' . SAQ_PLANET . '
				ORDER BY run_time ASC';
			$result = $this->db->sql_query_limit($sql, 1);
			$this->planet_data[$planet_id]['planet_change_status'] = (int) $db->sql_fetchfield('run_time');
			$this->db->sql_freeresult($result);
		}
	}

	function set_cur_planet($planet_id, $set_db = true)
	{
		if (!$planet_id)
		{
			return;
		}

		$this->cur_planet = (int) $planet_id;

		if ($set_db)
		{
			$sql = 'UPDATE ' . $this->tables->get('users') . '
				SET user_cur_planet = ' . $this->cur_planet . '
				WHERE user_id = ' . $this->user_id;
			$this->db->sql_query($sql);
		}
	}
}
