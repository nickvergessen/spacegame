<?php
/**
*
* @package SpaceGame Buildings
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace schilljs\spacegame\buildings;

/**
* Researches upgrades for ships and buildings.
* Can be upgraded to provide more upgrades.
* Max upgrade level : 20.
*/
class research_center extends \schilljs\spacegame\buildings\base
{
  protected $times = array(
		'flat'			=> 1800,
		'linear'		=> 600,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 5000,
			'linear'			=> 500,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 5000,
			'linear'			=> 500,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 1500,
			'linear'			=> 300,
			'square'			=> 100,
		),
	);

	protected $buildings = array(
		'research_center_flat'		=> 2,
	);

	protected $technologies = array(
	);
}
