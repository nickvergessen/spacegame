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
* Provides additional attack damage and defense for fleet in orbit.
* Can be upgraded to provide more damage and defense.
* Max upgrade level : 10.
*/
class planetary_defense extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 7200,
		'linear'		=> 10400,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 15000,
			'linear'			=> 10000,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 15000,
			'linear'			=> 10000,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 5000,
			'linear'			=> 8000,
			'square'			=> 0,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 6,
		'plasmagenerator_flat'			=> 4,
		'spying_defense_flat'			=> 1,
		'research_center_flat'			=> 3,
	);

	protected $technologies = array(
	);
}
