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
* Produces deuterium at cost of other resources.
* The amount of deuterium producing can be set.
* Can be upgraded to produce larger amounts of deuterium.
* Max upgrade level : 20.
*/
class deuterium_reactor extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 1800,
		'linear'		=> 3600,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 15000,
			'linear'			=> 10000,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 5000,
			'linear'			=> 10000,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 15000,
			'linear'			=> 15000,
			'square'			=> 0,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 5,
		'warehouse_flat'				=> 4,
		'research_center_flat'			=> 2,
		'plasmagenerator_flat'			=> 8,
		'tritanium_mine_flat'			=> 8,
		'mercoxit_mine_flat'			=> 8,
		'isogen_mine_flat'				=> 8,
	);

	protected $technologies = array(
	);
}
