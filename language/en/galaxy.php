<?php
/**
*
* @package SpaceGame Navigation
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'HL_GALAXY_FAVORITES'	=> 'Favorite Planets',
	'HL_GALAXY_MAP'			=> 'Map',

	'GALAXY_FAVORITES'		=> 'With the favorites you can have a quick jump to the right place in the map.',
	'GALAXY_FAVORITE_NONE'	=> 'You did not add any favorite quadrants yet.',

	'MAP_ACTION'			=> 'Action',
	'MAP_ACTION_ATTACK'		=> 'Attack',
	'MAP_ACTION_COLONISE'	=> 'Colonise',
	'MAP_ACTION_DESTROY'	=> 'Destroy',
	'MAP_ACTION_SPY'		=> 'Spy',
	'MAP_ACTION_TRADE'		=> 'Trade',

	'GALAXY_FAVORITE_ADD'		=> 'Add to favorites',
	'GALAXY_FAVORITE_REMOVE'	=> 'Remove from favorites',
));
