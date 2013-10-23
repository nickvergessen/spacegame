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
	'SPACEGAME'					=> 'SpaceGame',

	'NAV_ALLIANCE'			=> 'Alliance',
	'NAV_ALLIANCE_OVERVIEW'	=> 'Overview',
	'NAV_ALLIANCE_FOUND'	=> 'Found',
	'NAV_ALLIANCE_MANAGE'	=> 'Manage',
	'NAV_ALLIANCE_USERS'	=> 'Members',
	'NAV_ALLIANCE_FORUM'	=> 'Forum',

	'NAV_GALAXY'			=> 'Galaxy',
	'NAV_GALAXY_FAVORITES'	=> 'Favorites',
	'NAV_GALAXY_MAP'		=> 'Map',

	'NAV_GENERAL'			=> 'General',
	'NAV_GENERAL_PLANETS'	=> 'Planets',
	'NAV_GENERAL_SETTINGS'	=> 'Settings',

	'NAV_PLANET'			=> 'Planet',
	'NAV_PLANET_OVERVIEW'	=> 'Overview',
	'NAV_PLANET_BUILDINGS'	=> 'Buildings',
	'NAV_PLANET_DOCKYARD'	=> 'Dockyard',
	'NAV_PLANET_LABORATORY'	=> 'Laboratory',
	'NAV_PLANET_FLEET'		=> 'Fleet',

	'NAV_STATISTICS'			=> 'Statistics',
	'NAV_STATISTICS_USERS'		=> 'Players',
	'NAV_STATISTICS_ALLIANCES'	=> 'Alliances',
));
