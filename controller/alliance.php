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
	* @param \phpbb\controller\helper		$helper
	* @param \phpbb\user					$user
	* @param \schilljs\spacegame\core		$space_core
	* @param \schilljs\spacegame\navigation	$navigation
	* @param \schilljs\spacegame\user		$space_user
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\user $user, \schilljs\spacegame\core $space_core, \schilljs\spacegame\navigation $navigation, \schilljs\spacegame\user $space_user)
	{
		$this->helper = $helper;
		$this->user = $user;
		$this->space_core = $space_core;
		$this->space_user = $space_user;
		$this->navigation = $navigation;
	}
}
