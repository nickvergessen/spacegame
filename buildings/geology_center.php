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
* Can be upgraded to improve the digging for resources.
* Max upgrade level : 5.
*/
class geology_center extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 21600,
		'linear'		=> 21600,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 30000,
			'linear'			=> 50000,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 30000,
			'linear'			=> 50000,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 30000,
			'linear'			=> 50000,
			'square'			=> 0,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 10,
		'plasmagenerator_flat'			=> 10,
		'warehouse_flat'				=> 15,
		'research_center_flat'			=> 10,
	);

	protected $technologies = array(
	);
}
