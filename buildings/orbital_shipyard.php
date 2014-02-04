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
* Can produce small, medium, large and capital ships.
* Can be upgraded to provide more facility slots and to unlock new ships and researches.
* Max upgrade level : 20.
*/
class orbital_shipyard extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 21600,
		'linear'		=> 10800,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 50000,
			'linear'			=> 20000,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 50000,
			'linear'			=> 20000,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 20000,
			'linear'			=> 30000,
			'square'			=> 0,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 10,
		'research_center_flat'			=> 5,
		'shipyard_flat'					=> 5,
		'warehouse_flat'				=> 10,
	);

	protected $technologies = array(
		'sublight_engine_flat'			=> 5,
		'warp_engine_flat'				=> 5,
		'hyperspace_engine_flat'		=> 5,
		'specialization_flat'			=> 3,
	);
}
