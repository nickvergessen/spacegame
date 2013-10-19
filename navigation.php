<?php
/**
*
* @package SpaceGame Core
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace schilljs\spacegame;

class navigation
{
	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \schilljs\spacegame\user */
	protected $space_user;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper $helper
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \schilljs\spacegame\user $space_user
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \schilljs\spacegame\user $space_user)
	{
		$this->template = $template;
		$this->helper = $helper;
		$this->user = $user;
		$this->space_user = $space_user;
	}

	protected function get_navigation_elements()
	{
		return array(
			array(
				'route'			=> 'general',
				'condition'		=> true,
				'name'			=> 'NAV_GENERAL',
				'children'		=> array(
					array(
						'route'			=> 'general/planets',
						'condition'		=> true,
						'name'			=> 'NAV_GENERAL_PLANETS',
					),
					array(
						'route'			=> 'general/settings',
						'condition'		=> true,
						'name'			=> 'NAV_GENERAL_SETTINGS',
					),
				),
			),
			array(
				'route'			=> 'planet',
				'condition'		=> true,
				'name'			=> 'NAV_PLANET',
				'children'		=> array(
					array(
						'route'			=> 'planet/overview',
						'condition'		=> true,
						'name'			=> 'NAV_PLANET_OVERVIEW',
					),
					array(
						'route'			=> 'planet/buildings',
						'condition'		=> true,
						'name'			=> 'NAV_PLANET_BUILDINGS',
					),
					array(
						'route'			=> 'planet/dockyard',
						'condition'		=> true,
						'name'			=> 'NAV_PLANET_DOCKYARD',
					),
					array(
						'route'			=> 'planet/laboratory',
						'condition'		=> true,
						'name'			=> 'NAV_PLANET_LABORATORY',
					),
					array(
						'route'			=> 'planet/fleet',
						'condition'		=> true,
						'name'			=> 'NAV_PLANET_FLEET',
					),
				),
			),
			array(
				'route'			=> 'alliance',
				'condition'		=> true,
				'name'			=> 'NAV_ALLIANCE',
				'children'		=> array(
					array(
						'route'			=> 'alliance/overview',
						'condition'		=> true,
						'name'			=> 'NAV_ALLIANCE_OVERVIEW',
					),
					array(
						'route'			=> 'alliance/found',
						'condition'		=> $this->space_user->get_ally() == 0,
						'name'			=> 'NAV_ALLIANCE_FOUND',
					),
					array(
						'route'			=> 'alliance/manage',
						'condition'		=> $this->space_user->get_ally() != 0,
						'name'			=> 'NAV_ALLIANCE_MANAGE',
					),
					array(
						'route'			=> 'alliance/users',
						'condition'		=> $this->space_user->get_ally() != 0,
						'name'			=> 'NAV_ALLIANCE_USERS',
					),
					array(
						'route'			=> 'alliance/forums',
						'condition'		=> $this->space_user->get_ally() != 0,
						'name'			=> 'NAV_ALLIANCE_FORUM',
					),
				),
			),
			array(
				'route'			=> 'galaxy',
				'condition'		=> true,
				'name'			=> 'NAV_GALAXY',
				'children'		=> array(
					array(
						'route'			=> 'galaxy/favorites',
						'condition'		=> true,
						'name'			=> 'NAV_GALAXY_FAVORITES',
					),
					array(
						'route'			=> 'galaxy/map',
						'condition'		=> true,
						'name'			=> 'NAV_GALAXY_MAP',
					),
				),
			),
			array(
				'route'			=> 'stats',
				'condition'		=> true,
				'name'			=> 'NAV_STATISTICS',
				'children'		=> array(
					array(
						'route'			=> 'stats/users',
						'condition'		=> true,
						'name'			=> 'NAV_STATISTICS_USERS',
					),
					array(
						'route'			=> 'stats/alliances',
						'condition'		=> true,
						'name'			=> 'NAV_STATISTICS_ALLIANCES',
					),
				),
			),
		);
	}

	public function display()
	{
		$this->display_step($this->get_navigation_elements(), 't_block1');
	}

	public function display_step($elements, $template_block, $mark_active = false)
	{
		foreach ($elements as $navigation)
		{
			if ($navigation['condition'])
			{
				$this->template->assign_block_vars($template_block, array(
					'U_TITLE'		=> $this->helper->url($navigation['route']),
					'L_TITLE'		=> $this->user->lang($navigation['name']),
					'S_SELECTED'	=> $mark_active || $this->is_active($navigation['route'], $template_block == 't_block1'),
				));

				if (($mark_active || $this->is_active($navigation['route'], $template_block == 't_block1')) && !empty($navigation['children']))
				{
					$this->display_step($navigation['children'], 't_block2', $this->is_active($navigation['route']));
				}

				$mark_active = false;
			}
		}
	}

	protected function is_active($route, $category = false)
	{
		$page = $this->user->page['page_name'];
		if (strpos($page, 'app.php/') === 0)
		{
			$page = substr($page, strlen('app.php/'));
		}
		return $page === $route || ($category && strpos($page, $route. '/') === 0);
	}
}
