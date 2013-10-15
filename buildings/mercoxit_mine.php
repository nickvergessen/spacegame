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
* Mines mercoxit.
* Can be upgraded to mine more mercoxit per hour.
* Max upgrade level : 40.
*/
class mercoxit_mine extends \schilljs\spacegame\buildings\base
{
	protected $times = array(
		'flat'			=> 600,
		'linear'		=> 0,
		'square'		=> 300,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 1000,
			'linear'			=> 200,
			'square'			=> 200,
		),

		'resource_two'		=> array(
			'flat'				=> 1500,
			'linear'			=> 200,
			'square'			=> 200,
		),

		'resource_three'	=> array(
			'flat'				=> 500,
			'linear'			=> 100,
			'square'			=> 100,
		),
	);

	protected $buildings = array(
		'planetary_fortress_flat'		=> 1,
	);

	protected $technologies = array(
	);
}
