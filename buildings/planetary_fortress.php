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
* Standard building on new planets.
* Can be upgraded to shorten building time of other buildings on the same planet and to unlock new buildings.
* Max upgrade level : 20.
*/
class planetary_fortress extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 600,
		'linear'		=> 0,
		'square'		=> 300,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 2000,
			'linear'			=> 1000,
			'square'			=> 500,
		),

		'resource_two'		=> array(
			'flat'				=> 2000,
			'linear'			=> 1000,
			'square'			=> 500,
		),

		'resource_three'	=> array(
			'flat'				=> 1000,
			'linear'			=> 500,
			'square'			=> 300,
		),
	);

	protected $buildings = array(
	);

	protected $technologies = array(
	);
}
