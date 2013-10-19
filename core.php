<?php
/**
*
* @package SpaceGame Core
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame;

class core
{
	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	/** @var \schilljs\spacegame\queue */
	protected $space_queue;

	/** @var \schilljs\spacegame\tables */
	protected $tables;

	/** @var array */
	protected $space_user;

	/** @var array */
	protected $space_planets;

	/** @var int */
	protected $ally_id;

	/** @var int */
	protected $cur_planet;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\db\driver\driver	$db
	* @param \phpbb\user				$user
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\db\driver\driver $db, \phpbb\user $user, \schilljs\spacegame\user $space_user, \schilljs\spacegame\queue $queue, \schilljs\spacegame\tables $tables)
	{
		$this->helper = $helper;
		$this->db = $db;
		$this->user = $user;
		$this->tables = $tables;
		$this->space_queue = $queue;

		$this->space_user_id = (int) $user->data['user_id'];
		$this->space_user = $space_user->get_user_by_id($this->space_user_id);
		$this->ally_id = $this->space_user->get_ally();
		$this->cur_planet = $this->space_user->get_cur_planet();
	}

	public function get_user_id()
	{
		return (int) $this->space_user_id;
	}

	public function run_queue()
	{
		return $this->space_queue->run();
	}

	const SPACE_GP_UID = 63;
	const SPACE_GP_NAME = 'GamePlay';
	const SPACE_GP_IP = '127.0.0.1';

	const SPACE_NUM_MQUADS = 1;

	const SPACE_NUM_QUADS = 100;
	const SPACE_QUADS_MIN = 10;
	const SPACE_QUADS_MAX = 60;

	const SPACE_NUM_PLANETS = 10;
	const SPACE_PLANETS_MIN = 0;
	const SPACE_PLANETS_MAX = 10;

	function create_planet($user_id, $planet_name = '', $row_id = 0, $quad_id = 0, $mquad_id = 0)
	{
		if (!$row_id && !$quad_id && !$mquad_id)
		{
			$sql = 'SELECT row_id, quad_id, mquad_id
				FROM ' . $this->tables->get('planets') . '
				WHERE user_id <> ' . self::SPACE_GP_UID;
			$result = $this->db->sql_query($sql);

			$mquads = $quads = $planets = array();
			for ($i = 1; $i <= self::SPACE_NUM_MQUADS; $i++)
			{
				$mquads[$i] = 0;
				for ($j = 1; $j <= self::SPACE_NUM_QUADS; $j++)
				{
					$quads[$i][$j] = 0;
					for ($k = 1; $k <= self::SPACE_NUM_PLANETS; $k++)
					{
						$planets[$i][$j][$k] = 0;
					}
				}
			}
			while ($row = $this->db->sql_fetchrow($result))
			{
				$mquads[$row['mquad_id']]++;
				$quads[$row['mquad_id']][$row['quad_id']]++;
				$planets[$row['mquad_id']][$row['quad_id']][$row['row_id']]++;
			}
			$this->db->sql_freeresult($result);

			$limit = self::SPACE_NUM_QUADS * self::SPACE_NUM_PLANETS;
			$redo = true;

			for ($i = 1; $i <= self::SPACE_NUM_MQUADS; $i++)
			{
				if (isset($mquads[$i]) && ($limit < $mquads[$i]))
				{
					unset($mquads[$i]);
				}
				elseif (isset($mquads[$i]) && ($limit > $mquads[$i]))
				{
					$limit = $mquads[$i];
				}
				if ($i == self::SPACE_NUM_MQUADS && $redo)
				{
					$redo = false;
					$i = 0;
				}
			}
			$mquad_id = array_rand($mquads);
			$quads = $quads[$mquad_id];
			$limit = self::SPACE_NUM_PLANETS;
			$redo = true;
			for ($i = 1; $i <= self::SPACE_NUM_QUADS; $i++)
			{
				if (isset($quads[$i]) && ($limit < $quads[$i]))
				{
					unset($quads[$i]);
				}
				elseif (isset($quads[$i]) && ($limit > $quads[$i]))
				{
					$limit = $quads[$i];
				}
				if ($i == self::SPACE_NUM_QUADS && $redo)
				{
					$redo = false;
					$i = 0;
				}
			}
			$quad_id = array_rand($quads);
			$planets = $planets[$mquad_id][$quad_id];
			for ($i = 1; $i <= self::SPACE_NUM_PLANETS; $i++)
			{
				if ($planets[$i])
				{
					unset($planets[$i]);
				}
			}
			if (!sizeof($planets))
			{
				trigger_error('FATAL ERROR: Planet-System is full, please contact admin!', E_USER_ERROR);
			}
			$row_id = array_rand($planets);

			if (!$planet_name)
			{
				$planet_name = $this->user->data['username'];
			}
		}

		$sql_ary = array(
			'planet_id'		=> $mquad_id * 1000000 + $quad_id * 1000 + $row_id,
			'mquad_id'		=> $mquad_id,
			'quad_id'		=> $quad_id,
			'row_id'		=> $row_id,
			'user_id'		=> $user_id,
			'planet_name'	=> $planet_name,
		);

		$sql = 'UPDATE ' . $this->tables->get('planets') . '
			SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . "
			WHERE mquad_id = $mquad_id
				AND quad_id = $quad_id
				AND row_id = $row_id";
		$this->db->sql_query($sql);

		if ($this->db->sql_affectedrows() == 1)
		{
			// planet belongs to GamePlay
			$sql = 'SELECT *
				FROM ' . $this->tables->get('planets') . "
				WHERE mquad_id = $mquad_id
					AND quad_id = $quad_id
					AND row_id = $row_id";
			$result = $this->db->sql_query($sql);
			$planet_data = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$sql = 'UPDATE ' . $this->tables->get('fleets') . '
				SET user_id = ' . $user_id . '
				WHERE fleet_id = ' . $planet_data['fleet_id'];
			$this->db->sql_query($sql);

			return $planet_data['planet_id'];
		}

		$sql_ary['fleet_id'] = $this->create_fleet($user_id);

		$sql = 'INSERT INTO ' . $this->tables->get('planets') . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);

		return $sql_ary['planet_id'];
	}

	function create_fleet($user_id, $data = array())
	{
		$sql_ary['user_id'] = $user_id;
		$this->db->sql_query('INSERT INTO ' . $this->tables->get('fleets') . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));

		return $this->db->sql_nextid();
	}

	function generate_planet_for_user()
	{
			// User has no planet, create one!
			$planet_id = $this->create_planet($this->space_user_id);
			$planet_data = array(
				'resource_one'		=> 5000,
				'resource_two'		=> 5000,
				'resource_three'	=> 1500,
				'planet_base'		=> 1,
				'last_fill_stocks'	=> intval(time() / 60),
				'planet_time'		=> time(),
			);
			$this->space_user->set_cur_planet($planet_id);

			$sql = 'UPDATE ' . $this->tables->get('planets') . '
				SET ' . $this->db->sql_build_array('UPDATE', $planet_data) . '
				WHERE planet_id = ' . $planet_id;
			$this->db->sql_query($sql);

			$sql = 'SELECT *
				FROM ' . $this->tables->get('planets') . '
				WHERE planet_id = ' . $planet_id;
			$result = $this->db->sql_query($sql);
			$planet_data = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			#update_spy_time($planet_data['planet_time'] + 1, $this->space_user_id, 0, $planet_data['mquad_id'], $planet_data['quad_id'], 0, 1);
			#update_spy_time($planet_data['planet_time'] + 1, $this->space_user_id, 0, $planet_data['mquad_id'], $planet_data['quad_id'], $planet_data['row_id']);

			$stats_data = array(
				'user_id'				=> $this->space_user_id,
				'username'				=> $this->user->data['username'],
				'username_clean'		=> $this->user->data['username_clean'],
				'user_gameplay_start'	=> time(),
				'num_planets'			=> 1,
				'max_planets'			=> 3,
			);
			$sql = 'INSERT INTO ' . $this->tables->get('users') . ' ' . $this->db->sql_build_array('INSERT', $stats_data);
			$this->db->sql_query($sql);

			$stats_data = array(
				'user_id'					=> $this->space_user_id,
				'building_resource_one'		=> 5000,
				'building_resource_two'		=> 5000,
				'building_resource_three'	=> 1500,
			);
			$sql = 'INSERT INTO ' . $this->tables->get('statistics') . ' ' . $this->db->sql_build_array('INSERT', $stats_data);
			$this->db->sql_query($sql);

			$message = $this->user->lang('WELCOME_MESSAGE') . '<br /><br />' . $this->user->lang('RETURN_GENERAL_PLANETS', '<a href="' . $this->helper->url('main') . '">', '</a>');
			meta_refresh(10, $this->helper->url('main'));
			trigger_error($message);
	}
}
