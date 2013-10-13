<?php
/**
*
* @package SpaceGame Buildings
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace schilljs\spacegame\buildings;

/**
* Mines isogen.
* Can be upgraded to mine more isogen per hour.
* Max upgrade level : 40.
*/
class isogen_mine extends \schilljs\spacegame\buildings\base
{
  protected $times = array(
		'flat'			=> 1800,
		'linear'		=> 600,
		'square'		=> 0,
	);

	protected $costs = array(
		'resource_one'		=> array(
			'flat'				=> 5000,
			'linear'			=> 500,
			'square'			=> 0,
		),

		'resource_two'		=> array(
			'flat'				=> 5000,
			'linear'			=> 500,
			'square'			=> 0,
		),

		'resource_three'	=> array(
			'flat'				=> 1500,
			'linear'			=> 300,
			'square'			=> 100,
		),
	);

	protected $buildings = array(
		'isogen_mine_flat'		=> 2,
	);

	protected $technologies = array(
	);
}
