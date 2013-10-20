<?php

/**
*
* @package SpaceGame Controller
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame\controller;

class galaxy extends \schilljs\spacegame\controller\base
{
	protected $favorite_planets = array();

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver		$db
	* @param \phpbb\controller\helper		$helper
	* @param \phpbb\request\request			$request
	* @param \phpbb\template\template		$template
	* @param \phpbb\user					$user
	* @param \schilljs\spacegame\core		$space_core
	* @param \schilljs\spacegame\favorites	$favorites
	* @param \schilljs\spacegame\navigation	$navigation
	* @param \schilljs\spacegame\tables		$tables
	* @param \schilljs\spacegame\user		$space_user
	*/
	public function __construct(\phpbb\db\driver\driver $db, \phpbb\controller\helper $helper, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \schilljs\spacegame\core $space_core, \schilljs\spacegame\favorites $favorites, \schilljs\spacegame\navigation $navigation, \schilljs\spacegame\tables $tables, \schilljs\spacegame\user $space_user)
	{
		$this->db = $db;
		$this->helper = $helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->space_core = $space_core;
		$this->favorites = $favorites;
		$this->navigation = $navigation;
		$this->tables = $tables;
		$this->space_user = $space_user;

		$this->user->add_lang_ext('schilljs/spacegame', 'galaxy');
	}

	/**
	* Add a planet to the user's favorites
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function add($mquad, $quad, $planet)
	{
		$this->init();

		if (check_link_hash($this->request->variable('hash', ''), 'add/' . $mquad . '/' . $quad . '/' . $planet))
		{
			$success = $this->favorites->add($mquad, $quad, $planet);

			$message = $success ? $this->user->lang['GALAXY_FAVORITE_ADDED'] : $this->user->lang['GALAXY_ALREADY_FAVORITE'];
			if (!$this->request->is_ajax())
			{
				$message .= '<br /><br />' . $this->user->lang('RETURN_MAP', '<a href="' . $this->helper->url('galaxy/map/' . $mquad . '/' . $quad) . '">', '</a>');
			}

			trigger_error($message);
		}
		else
		{
			trigger_error('INVALID_REQUEST');
		}

		return $this->helper->render('galaxy_body.html', $this->user->lang('HL_GALAXY_FAVORITES'));
	}

	/**
	* Remove a planet from the user's favorites
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function remove($mquad, $quad, $planet)
	{
		$this->init();

		if (check_link_hash($this->request->variable('hash', ''), 'remove/' . $mquad . '/' . $quad . '/' . $planet))
		{
			$success = $this->favorites->remove($mquad, $quad, $planet);

			$message = $success ? $this->user->lang['GALAXY_FAVORITE_REMOVED'] : $this->user->lang['GALAXY_NOT_FAVORITE'];
			if (!$this->request->is_ajax())
			{
				$message .= '<br /><br />' . $this->user->lang('RETURN_MAP', '<a href="' . $this->helper->url('galaxy/map/' . $mquad . '/' . $quad) . '">', '</a>');
			}

			trigger_error($message);
		}
		else
		{
			trigger_error('INVALID_REQUEST');
		}

		return $this->helper->render('galaxy_body.html', $this->user->lang('HL_GALAXY_FAVORITES'));
	}

	/**
	* Display the favorite planets of the user
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function favorites()
	{
		$this->init();
		$this->favorite_planets = $this->favorites->get_planets();
		$this->display_favorite_quadrants();

		$this->display_map($this->favorite_planets);

		$this->template->assign_vars(array(
			'L_TITLE'			=> $this->user->lang('HL_GALAXY_FAVORITES'),
		));

		return $this->helper->render('galaxy_body.html', $this->user->lang('HL_GALAXY_FAVORITES'));
	}

	/**
	* Display the favorite planets of the user
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function map($mquad, $quad)
	{
		$this->init();
		$this->favorite_planets = $this->favorites->get_planets();
		$this->display_favorite_quadrants();

		$navigation = $this->space_user->get_navigation();
		if ($mquad == 0)
		{
			$mquad = $navigation['mquad'];
		}
		if ($quad == 0)
		{
			$quad = $navigation['quad'];
		}

		$planets = array();
		for ($i = 1; $i <= \schilljs\spacegame\core::SPACE_NUM_PLANETS; $i++)
		{
			$planets[$mquad][$quad][] = $i;
		}

		$this->display_map($planets);

		$this->template->assign_vars(array(
			'L_TITLE'			=> $this->user->lang('HL_GALAXY_MAP'),
		));

		return $this->helper->render('galaxy_body.html', $this->user->lang('HL_GALAXY_MAP'));
	}

	public function display_favorite_quadrants()
	{
		$favorite_quadrants = $this->favorites->get_quadrants();
		if (empty($favorite_quadrants))
		{
			return;
		}

		foreach ($favorite_quadrants as $mquadrant => $quadrants)
		{
			foreach ($quadrants as $quadrant)
			{
				$this->template->assign_block_vars('favorite', array(
					'QUAD_NAME'	=> '[' . $mquadrant . ':' . $quadrant . ']',
					'U_QUAD'	=> $this->helper->url('galaxy/map/' . $mquadrant . '/' . $quadrant),
				));
			}
		}
	}

	public function display_map($planets)
	{
		foreach ($planets as $mquadrant => $quadrants)
		{
			foreach ($quadrants as $quadrant => $planets)
			{
				foreach ($planets as $planet)
				{
					$planet_image = 'black';
					switch ($planet % 5)
					{
						case 1:
							$planet_image = 'blue';
						break;
						case 2:
							$planet_image = 'green';
						break;
						case 3:
							$planet_image = 'grey';
						break;
						case 4:
							$planet_image = 'orange';
						break;
					}

					$s_favorite_planet = isset($this->favorite_planets[$mquadrant][$quadrant]) &&  in_array($planet, $this->favorite_planets[$mquadrant][$quadrant]);
					$u_remove_favorite_planet = $this->helper->url('galaxy/favorites/remove/' . $mquadrant . '/' . $quadrant . '/' . $planet, 'hash=' . generate_link_hash('remove/' . $mquadrant . '/' . $quadrant . '/' . $planet));
					$u_add_favorite_planet = $this->helper->url('galaxy/favorites/add/' . $mquadrant . '/' . $quadrant . '/' . $planet, 'hash=' . generate_link_hash('add/' . $mquadrant . '/' . $quadrant . '/' . $planet));


					$this->template->assign_block_vars('planetrow', array(
						'PLANET_NAME'		=> $mquadrant . ':' . $quadrant . ':' . $planet,
						'PLANET_IMAGE'		=> 'planet_' . $planet_image,
						'S_FAVORITE_PLANET'			=> $s_favorite_planet,
						'U_FAVORITE_PLANET'			=> ($s_favorite_planet) ? $u_remove_favorite_planet : $u_add_favorite_planet,
						'U_FAVORITE_PLANET_TOGGLE'	=> (!$s_favorite_planet) ? $u_remove_favorite_planet : $u_add_favorite_planet,
						'L_FAVORITE_PLANET'			=> ($s_favorite_planet) ? $this->user->lang['GALAXY_FAVORITE_REMOVE'] : $this->user->lang['GALAXY_FAVORITE_ADD'],
						'L_FAVORITE_PLANET_TOGGLE'	=> (!$s_favorite_planet) ? $this->user->lang['GALAXY_FAVORITE_REMOVE'] : $this->user->lang['GALAXY_FAVORITE_ADD'],
					));
				}
			}
		}
	}
}
