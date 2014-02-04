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
* Can be build to reduce a fleets travel time.
* Can be upgraded to increase travel speed.
* Max upgrade level : 5.
*/
class acceleration_gate extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 36000,
		'linear'		=> 36000,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 80000,
			'linear'			=> 80000,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 80000,
			'linear'			=> 80000,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 80000,
			'linear'			=> 80000,
			'square'			=> 0,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 15,
		'plasmagenerator_flat'			=> 10,
		'research_center_flat'			=> 7,
		'deuterium_reactor_flat'		=> 10,
	);

	protected $technologies = array(
		'warp_engine_flat'				=> 6,
		'hyperspace_engine_flat'		=> 7,
		'reaction_efficiency_flat'		=> 5,
	);
}
