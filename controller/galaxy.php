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
	/**
	* Constructor
	*
	* @param \phpbb\controller\helper		$helper
	* @param \phpbb\template\template				$template
	* @param \phpbb\user					$user
	* @param \schilljs\spacegame\core		$space_core
	* @param \schilljs\spacegame\favorites	$favorites
	* @param \schilljs\spacegame\navigation	$navigation
	* @param \schilljs\spacegame\user		$space_user
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \schilljs\spacegame\core $space_core, \schilljs\spacegame\favorites $favorites, \schilljs\spacegame\navigation $navigation, \schilljs\spacegame\user $space_user)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->space_core = $space_core;
		$this->favorites = $favorites;
		$this->navigation = $navigation;
		$this->space_user = $space_user;

		$this->user->add_lang_ext('schilljs/spacegame', 'galaxy');
	}

	/**
	* Display the favorite planets of the user
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function favorites()
	{
		$this->init();

		$this->display_favorite_quadrants();

		$this->display_map($this->favorites->get_planets());

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
		foreach ($this->favorites->get_quadrants() as $mquadrant => $quadrants)
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
					$this->template->assign_block_vars('planetrow', array(
						'QUAD_NAME'	=> '[' . $mquadrant . ':' . $quadrant . ']',
						'U_QUAD'	=> $this->helper->url('galaxy/map/' . $mquadrant . '/' . $quadrant),
					));
				}
			}
		}
	}
}
