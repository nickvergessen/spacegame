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
* Provides energy to power other buildings on the planet.
* Can be upgraded to provide more energy.
* Max upgrade level : 20.
*/
class plasmagenerator extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 900,
		'linear'		=> 900,
		'square'		=> 200,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 3000,
			'linear'			=> 0,
			'square'			=> 1000,
		),

		'resource_two'		=> array(
			'flat'				=> 2000,
			'linear'			=> 0,
			'square'			=> 1000,
		),

		'resource_three'	=> array(
			'flat'				=> 1000,
			'linear'			=> 500,
			'square'			=> 300,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 1,
	);

	protected $technologies = array(
	);
}
