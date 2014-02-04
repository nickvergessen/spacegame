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
* Increases amount of recources, that can be stored on the planet.
* Can be upgraded to increase amount of recources stored.
* Max upgrade level : 50.
*/
class warehouse extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 3600,
		'linear'		=> 3600,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 5000,
			'linear'			=> 0,
			'square'			=> 150,
		),

		'resource_two'		=> array(
			'flat'				=> 5000,
			'linear'			=> 0,
			'square'			=> 150,
		),

		'resource_three'	=> array(
			'flat'				=> 1500,
			'linear'			=> 300,
			'square'			=> 80,
		),
	);

	protected $buildings = array(
		'tritanium_mine_flat'			=> 1,
		'mercoxit_mine_flat'			=> 1,
		'isogen_mine_flat'				=> 1,
		'planetary_fortress_flat'		=> 2,
	);

	protected $technologies = array(
	);
}
