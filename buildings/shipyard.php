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
* Can produce small, medium and large ships.
* Can be upgraded to provide more facility slots and to unlock new ships and researches.
* Max upgrade level : 20.
*/
class shipyard extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 3600,
		'linear'		=> 3600,
		'square'		=> 600,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 12000,
			'linear'			=> 3000,
			'square'			=> 1000,
		),

		'resource_two'		=> array(
			'flat'				=> 12000,
			'linear'			=> 3000,
			'square'			=> 1000,
		),

		'resource_three'	=> array(
			'flat'				=> 5000,
			'linear'			=> 3000,
			'square'			=> 500,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 4,
		'plasmagenerator_flat'			=> 2,
		'warehouse_flat'				=> 1,
	);

	protected $technologies = array(
	);
}
