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
	'HL_STATISTICS_USERS'		=> 'Statistics &raquo; Players',
	'HL_STATISTICS_ALLIANCES'	=> 'Statistics &raquo; Alliances',

	'ALLIANCE_NAME'			=> 'Alliance name',
	'ALLIANCE_MEMBERS'		=> '#Members',
	'USER_NAME'				=> 'Player name',

	'BATTLE_POINTS'			=> 'Battle',
	'BUILDING_POINTS'		=> 'Building',
	'TECHNOLOGY_POINTS'		=> 'Technology',
	'TOTAL_POINTS'			=> 'Total',
	'PER_MEMBER'			=> 'P / #M',
));
