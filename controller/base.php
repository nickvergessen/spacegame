<?php

/**
*
* @package SpaceGame Controller
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame\controller;

abstract class base
{
	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\user */
	protected $user;

	/* @var \schilljs\spacegame\core */
	protected $space_core;

	/* @var \schilljs\spacegame\navigation */
	protected $navigation;

	/* @var \schilljs\spacegame\user */
	protected $space_user;

	protected function init()
	{
		$this->user->add_lang_ext('schilljs/spacegame', 'common');

		$this->space_core->run_queue();
		$this->space_user->load_planets();
		if (!$this->space_user->num_planets())
		{
			$this->space_core->generate_planet_for_user();
		}
		$this->navigation->display();
	}

	protected function init_gameplay()
	{
		$this->space_core->run_queue();
	}

	/**
	* Display statistics for the users
	*
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function nyf()
	{
		$this->init();

		return $this->helper->render('nyf_body.html', $this->user->lang('NYF'));
	}
}
