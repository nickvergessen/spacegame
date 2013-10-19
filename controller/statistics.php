<?php

/**
*
* @package SpaceGame Controller
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame\controller;

class statistics extends \schilljs\spacegame\controller\base
{
	/* @var \phpbb\db\driver\driver */
	protected $db;

	/* @var \phpbb\config */
	protected $config;

	/* @var \phpbb\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var string phpBB root path */
	protected $root_path;

	/* @var string phpEx */
	protected $php_ext;

	protected $sort_keys = array('building', 'technology', 'battle', 'total');

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver	$db		Config object
	* @param \phpbb\config	$config		Config object
	* @param \phpbb\template	$template	Template object
	* @param \phpbb\user	$user		User object
	* @param \phpbb\controller\helper		$helper				Controller helper object
	* @param \schilljs\spacegame\core		$space_core
	* @param \schilljs\spacegame\navigation		$navigation
	* @param \schilljs\spacegame\user		$space_user
	* @param string			$root_path	phpBB root path
	* @param string			$php_ext	phpEx
	* @param \schilljs\spacegame\tables		$tables
	*/
	public function __construct(\phpbb\db\driver\driver $db, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, \schilljs\spacegame\core $space_core, \schilljs\spacegame\navigation $navigation, \schilljs\spacegame\user $space_user, $root_path, $php_ext, $tables)
	{
		$this->db = $db;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		$this->tables = $tables;
		$this->space_core = $space_core;
		$this->space_user = $space_user;
		$this->navigation = $navigation;
	}

	/**
	* Display statistics for the users
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function users($sort_key, $sort_dir)
	{
		$this->init();

		$sort_key = (in_array($sort_key, $this->sort_keys)) ? $sort_key : 'total';
		$sort_dir = ($sort_dir == 'asc') ? 'ASC' : 'DESC';

		$sql = 'SELECT *
			FROM ' . $this->tables->get('users') . "
			ORDER BY {$sort_key}_points $sort_dir";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$stats = array();
			foreach ($this->sort_keys as $stat)
			{
				$stats[strtoupper($stat) . '_POINTS'] = round(($row[$stat . '_points'] / 1000), 1);
				$stats[strtoupper($stat) . '_POINTS_ADD'] = ((($row[$stat . '_points'] - $row['last_' . $stat . '_points']) > 0) ? '+' . round((($row[$stat . '_points'] - $row['last_' . $stat . '_points']) / 1000), 1) : '');
			}
			$place = ($row['last_total_place'] - $row['total_place']);
			$this->template->assign_block_vars('userrow', array_merge($stats, array(
				'USERNAME'			=> $row['username'],
				'ALLY_TAG'			=> $row['ally_tag'],
				'TOTAL_PLACE'		=> $row['total_place'],
				'TOTAL_PLACE_ADD'	=> ($place == 0) ? '<span style="color: #000000;">&plusmn;0</span>' : (($place > 0) ? '<span style="color: #00AA00;">+' . $place . '</span>' : '<span style="color: #AA0000;">' . $place . '</span>'),

				'S_IS_PROTECTED'	=> false,//@todo: calc_noob_protection($row['total_points'], $space->udata['total_points']),
				'S_IS_VACATION'		=> $row['user_vacation'],
				'S_IS_NOOB'			=> false,//@todo: ($row['user_gameplay_start'] + SPACE_NOOB_SCHUTZ > time()),
			)));
		}
		$this->db->sql_freeresult($result);

		$sort_dir_link = (($sort_dir == 'DESC') ? 'asc' : 'desc');
		$this->template->assign_vars(array(
			'SORT_BUILDING_POINTS'		=> $this->helper->url('stats/users/building/' . $sort_dir_link),
			'SORT_TECHNOLOGY_POINTS'	=> $this->helper->url('stats/users/technology/' . $sort_dir_link),
			'SORT_BATTLE_POINTS'		=> $this->helper->url('stats/users/battle/' . $sort_dir_link),
			'SORT_TOTAL_POINTS'			=> $this->helper->url('stats/users/total/' . $sort_dir_link),

			'L_TITLE'			=> $this->user->lang('SPACE_STATISTICS_USERS'),
		));

		return $this->helper->render('statistics_body.html', $this->user->lang('SPACE_STATISTICS_USERS'));
	}

	/**
	* Display statistics for the alliances
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function alliances($sort_key, $sort_dir, $mode)
	{
		$this->init();

		$sort_key = ($sort_key == 'members') ? 'ally_members' : ((in_array($sort_key, $this->sort_keys)) ? $sort_key : 'total') . '_points';
		$sort_dir = ($sort_dir == 'asc') ? 'ASC' : 'DESC';

		$allys = array();
		$allys[0] = array(
			'ally_id'			=> 0,
			'ally_name'			=> 'Ally der Allylosen!',
			'ally_tag'			=> '',
			'ally_members'		=> 0,
			'building_points'	=> 0,
			'technology_points'	=> 0,
			'battle_points'		=> 0,
			'total_points'		=> 0,
		);

		$sql = 'SELECT *
			FROM ' . $this->tables->get('users') . '
			WHERE ally_id = 0';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$allys[0]['building_points'] += $row['building_points'];
			$allys[0]['technology_points'] += $row['technology_points'];
			$allys[0]['battle_points'] += $row['battle_points'];
			$allys[0]['total_points'] += $row['total_points'];
			$allys[0]['ally_members']++;
		}
		$this->db->sql_freeresult($result);

		if ($allys[0]['ally_members'] == 0)
		{
			unset($allys[0]);
		}

		$sql = 'SELECT *
			FROM ' . $this->tables->get('alliances');
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$allys[$row['ally_id']] = $row;
		}
		$this->db->sql_freeresult($result);

		$allys = $this->sort_ally_array($allys, $sort_key, $mode == 'members');
		if ($sort_dir == 'ASC')
		{
			$allys = array_reverse($allys, true);
		}

		foreach ($allys as $key => $row)
		{
			if ($row['ally_members'] == 0) continue;

			$this->template->assign_block_vars('userrow', array(
				'USERNAME'			=> $row['ally_name'],
				'MEMBERS'			=> $row['ally_members'],
				'ALLY_TAG'			=> $row['ally_tag'],

				'BUILDING_POINTS'	=> round($row['building_points'] / 1000, 1),
				'TECHNOLOGY_POINTS'	=> round($row['technology_points'] / 1000, 1),
				'BATTLE_POINTS'		=> round($row['battle_points'] / 1000, 1),
				'TOTAL_POINTS'		=> round($row['total_points'] / 1000, 1),

				'BUILDING_POINTS_PM'	=> round(($row['building_points'] / $row['ally_members']) / 1000, 1),
				'TECHNOLOGY_POINTS_PM'	=> round(($row['technology_points'] / $row['ally_members']) / 1000, 1),
				'BATTLE_POINTS_PM'		=> round(($row['battle_points'] / $row['ally_members']) / 1000, 1),
				'TOTAL_POINTS_PM'		=> round(($row['total_points'] / $row['ally_members']) / 1000, 1),
			));
		}

		if ($mode == 'members')
		{
			$sort_dir_link = (($sort_dir == 'DESC') ? 'asc' : 'desc');
			$sort_dir_pm_link = (($sort_dir == 'DESC') ? 'desc' : 'asc');
		}
		else
		{
			$sort_dir_link = (($sort_dir == 'DESC') ? 'desc' : 'asc');
			$sort_dir_pm_link = (($sort_dir == 'DESC') ? 'asc' : 'desc');
		}
		$this->template->assign_vars(array(
			'SORT_MEMBERS'				=> $this->helper->url('stats/alliances/members/' . $sort_dir_link),

			'SORT_BUILDING_POINTS'		=> $this->helper->url('stats/alliances/building/' . $sort_dir_link),
			'SORT_TECHNOLOGY_POINTS'	=> $this->helper->url('stats/alliances/technology/' . $sort_dir_link),
			'SORT_BATTLE_POINTS'		=> $this->helper->url('stats/alliances/battle/' . $sort_dir_link),
			'SORT_TOTAL_POINTS'			=> $this->helper->url('stats/alliances/total/' . $sort_dir_link),

			'SORT_BUILDING_POINTS_PM'	=> $this->helper->url('stats/alliances/building/' . $sort_dir_pm_link . '/members'),
			'SORT_TECHNOLOGY_POINTS_PM'	=> $this->helper->url('stats/alliances/technology/' . $sort_dir_pm_link . '/members'),
			'SORT_BATTLE_POINTS_PM'		=> $this->helper->url('stats/alliances/battle/' . $sort_dir_pm_link . '/members'),
			'SORT_TOTAL_POINTS_PM'		=> $this->helper->url('stats/alliances/total/' . $sort_dir_pm_link . '/members'),

			'L_TITLE'			=> $this->user->lang('SPACE_STATISTICS_ALLIANCES'),
			'S_ALLY_STATS'		=> true,
		));

		return $this->helper->render('statistics_body.html', $this->user->lang('SPACE_STATISTICS_ALLIANCES'));
	}

	private function sort_ally_array($ary, $sort_by, $per_member)
	{
		$return = array();
		$calced_sort = array();

		foreach ($ary as $row)
		{
			$calced_sort[$row['ally_id']] = ($per_member) ? ($row[$sort_by] / $row['ally_members']) : (int) $row[$sort_by];
		}

		while (sizeof($calced_sort))
		{
			$highest_value = $next_ally = -1;
			foreach ($calced_sort as $ally_id => $value)
			{
				if ($value > $highest_value)
				{
					$next_ally = $ally_id;
					$highest_value = $value;
				}
			}
			$return[] = $ary[$next_ally];
			unset($calced_sort[$next_ally]);
			unset($ary[$next_ally]);
		}

		return $return;
	}
}
