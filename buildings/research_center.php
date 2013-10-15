<?php
/**
*
* @package SpaceGame Buildings
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame\buildings;

/**
* Researches upgrades for ships and buildings.
* Can be upgraded to provide more upgrades.
* Max upgrade level : 10.
*/
class research_center extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 7200,
		'linear'		=> 7200,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 10000,
			'linear'			=> 5000,
			'square'			=> 600,
		),

		'resource_two'		=> array(
			'flat'				=> 10000,
			'linear'			=> 5000,
			'square'			=> 600,
		),

		'resource_three'	=> array(
			'flat'				=> 10000,
			'linear'			=> 5000,
			'square'			=> 600,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 4,
		'plasmagenerator_flat'			=> 1,
	);

	protected $technologies = array(
	);
}
