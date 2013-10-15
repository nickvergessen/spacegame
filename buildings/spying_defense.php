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
* Provides protection from spy attacks.
* Can be upgraded to provide more protection.
* Max upgrade level : 30.
*/
class spying_defense extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 1800,
		'linear'		=> 1800,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 5000,
			'linear'			=> 10000,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 5000,
			'linear'			=> 5000,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 5000,
			'linear'			=> 10000,
			'square'			=> 0,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 4,
		'research_center_flat'			=> 2,
		'plasmagenerator_flat'			=> 2,
	);

	protected $technologies = array(
	);
}
