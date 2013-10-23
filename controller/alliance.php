<?php

/**
*
* @package SpaceGame Controller
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame\controller;

class alliance extends \schilljs\spacegame\controller\base
{
	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver		$db
	* @param \phpbb\controller\helper		$helper
	* @param \phpbb\request\request			$request
	* @param \phpbb\template\template		$template
	* @param \phpbb\user					$user
	* @param string			$phpbb_root_path
	* @param string			$php_ext
	* @param \schilljs\spacegame\core		$space_core
	* @param \schilljs\spacegame\navigation	$navigation
	* @param \schilljs\spacegame\tables		$tables
	* @param \schilljs\spacegame\user		$space_user
	* @param \schilljs\spacegame\user_helper	$user_helper
	*/
	public function __construct(\phpbb\db\driver\driver $db, \phpbb\controller\helper $helper, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $phpbb_root_path, $php_ext, \schilljs\spacegame\core $space_core, \schilljs\spacegame\navigation $navigation, \schilljs\spacegame\tables $tables, \schilljs\spacegame\user $space_user, \schilljs\spacegame\user_helper $user_helper)
	{
		$this->db = $db;
		$this->helper = $helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->space_core = $space_core;
		$this->navigation = $navigation;
		$this->tables = $tables;
		$this->space_user = $space_user;
		$this->user_helper = $user_helper;

		$this->user->add_lang_ext('schilljs/spacegame', 'alliance');
	}

	/**
	* Display all alliances
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function overview()
	{
		$this->init();

		$sql = 'SELECT username, user_id, ally_id
			FROM ' . $this->tables->get('users') . '
			WHERE ally_leader = 1';
		$result = $this->db->sql_query($sql);

		$ally_leaders = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$ally_leaders[(int) $row['ally_id']][] = array(
				'user_id'	=> (int) $row['user_id'],
				'username'	=> $row['username'],
			);
		}
		$this->db->sql_freeresult($result);

		$sql = 'SELECT *
			FROM ' . $this->tables->get('alliances');
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$leaders = array();
			if (sizeof($ally_leaders[(int) $row['ally_id']]))
			{
				foreach ($ally_leaders[(int) $row['ally_id']] as $leader)
				{
					$leaders[] = '<a href="' . append_sid("{$this->root_path}memberlist.{$this->php_ext}", 'mode=viewprofile&amp;u=' . (int) $leader['user_id']) . '">' . $leader['username'] . '</a>';
				}
			}

			$this->template->assign_block_vars('alliancerow', array(
				'ALLIANCE_ID'			=> $row['ally_id'],
				'ALLIANCE_NAME'			=> $row['ally_name'],
				'ALLIANCE_TAG'			=> $row['ally_tag'],
				'ALLIANCE_DESC'			=> generate_text_for_display($row['ally_desc'], $row['ally_desc_uid'], $row['ally_desc_bitfield'], 7),

				'ALLIANCE_MEMBERS'		=> $row['ally_members'],
				'ALLIANCE_POINTS'		=> round($row['total_points'] / 1000, 1),
				'ALLIANCE_LEADERS'		=> implode(', ', $leaders),
				'ALLIANCE_FOUNDED'		=> $this->user->format_date($row['ally_time']),
				'S_ALLIANCE_PENDING'	=> ($this->space_user->get('ally_pending') && ($row['ally_id'] == $this->space_user->get_ally())) ? $row['ally_id'] : 0,
				'S_IN_ALLIANCE'			=> (!$this->space_user->get('ally_pending') && ($row['ally_id'] == $this->space_user->get_ally())) ? $row['ally_id'] : 0,

				'U_ALLIANCE_JOIN'		=> (!$this->space_user->get_ally()) ? $this->helper->url('alliance/join/' . $row['ally_id'], 'hash=' . generate_link_hash('join/' . $row['ally_id'])) : '',
				'U_ALLIANCE_LEAVE'		=> ($row['ally_id'] == $this->space_user->get_ally()) ? $this->helper->url('alliance/leave', 'hash=' . generate_link_hash('leave')) : '',
			));
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'S_HIDDEN_FIELDS'		=> (isset($s_hidden_fields)) ? $s_hidden_fields : '',
			#'S_SPACE_ACTION'		=> $this->u_action,
			'S_FOUND_ACTION'		=> $this->helper->url('alliance/found'),

			'S_IN_ALLIANCE'		=> $this->space_user->get_ally() && !$this->space_user->get('ally_pending'),
			'S_IS_PENDING'		=> $this->space_user->get('ally_pending'),
			'S_IS_LEADER'		=> $this->space_user->get('ally_leader'),
			'S_NO_ALLIANCE'		=> !$this->space_user->get_ally(),
		));

		return $this->helper->render('alliance_overview.html', $this->user->lang('HL_ALLIANCE_OVERVIEW'));
	}

	/**
	* Request to join an alliance
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function join($ally)
	{
		$this->init();

		if (check_link_hash($this->request->variable('hash', ''), 'join/' . $ally))
		{
			$error_msg = $this->user_helper->join_ally($this->space_user, $ally);

			$message = $error_msg ?: 'ALLIANCE_JOINED';
			$message = $this->user->lang($message);
			if (!$this->request->is_ajax())
			{
				$message .= '<br /><br />' . $this->user->lang('RETURN_OVERVIEW', '<a href="' . $this->helper->url('alliance/overview') . '">', '</a>');
			}

			trigger_error($message);
		}
		else
		{
			trigger_error('INVALID_REQUEST');
		}
	}

	/**
	* Request to join an alliance
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function leave()
	{
		$this->init();

		if (check_link_hash($this->request->variable('hash', ''), 'leave'))
		{
			$error_msg = $this->user_helper->leave_ally($this->space_user);

			$message = $error_msg ?: 'ALLIANCE_LEFT';
			$message = $this->user->lang($message);
			if (!$this->request->is_ajax())
			{
				$message .= '<br /><br />' . $this->user->lang('RETURN_OVERVIEW', '<a href="' . $this->helper->url('alliance/overview') . '">', '</a>');
			}

			trigger_error($message);
		}
		else
		{
			trigger_error('INVALID_REQUEST');
		}
	}
}
