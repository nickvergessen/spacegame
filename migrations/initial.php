<?php
/**
*
* @package SpaceGame Migration
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace schilljs\spacegame\migrations;

class initial extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'space_user_technologies');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'space_alliances' => array(
					'COLUMNS'		=> array(
						'ally_id'				=> array('UINT', NULL, 'auto_increment'),
						'ally_tag'				=> array('VCHAR', ''),
						'ally_name'				=> array('VCHAR', ''),
						'ally_name_clean'		=> array('VCHAR', ''),
						'ally_desc'				=> array('MTEXT_UNI', ''),
						'ally_desc_uid'			=> array('VCHAR:8', ''),
						'ally_desc_bitfield'	=> array('VCHAR:255', ''),

						'ally_time'				=> array('TIMESTAMP', 0),
						'ally_members'			=> array('UINT', 0),

						'leaders_group_id'		=> array('UINT', 0),
						'members_group_id'		=> array('UINT', 0),
						'leaders_forum_id'		=> array('UINT', 0),
						'members_forum_id'		=> array('UINT', 0),


						'building_points'		=> array('UINT:12', 0),
						'technology_points'		=> array('UINT:12', 0),
						'battle_points'			=> array('UINT:12', 0),
						'total_points'			=> array('UINT:12', 0),
					),
					'PRIMARY_KEY'		=> 'ally_id',
					'KEYS'			=> array(
						'atp'		=> array('INDEX', array('total_points')),
					),
				),

				$this->table_prefix . 'space_favorites' =>  array(
					'COLUMNS'		=> array(
						'user_id'			=> array('UINT', 0),
						'mquad_id'			=> array('UINT', 0),
						'quad_id'			=> array('UINT', 0),
						'row_id'			=> array('UINT', 0),
					),
					'KEYS'		=> array(
						'umq'		=> array('UNIQUE', array('user_id', 'mquad_id', 'quad_id', 'row_id')),
					),
				),

				$this->table_prefix . 'space_fleets' => array(
					'COLUMNS'		=> array(
						'fleet_id'			=> array('UINT', NULL, 'auto_increment'),
						'user_id'			=> array('UINT', 0),
						'ally_id'			=> array('UINT', 0),

						'fleet_titan'		=> array('UINT', 0),
						'fleet_carbon'		=> array('UINT', 0),
						'fleet_lithium'		=> array('UINT', 0),
					),
					'PRIMARY_KEY'		=> 'fleet_id',
					'KEYS'		=> array(
						'fui'		=> array('INDEX', array('user_id')),
					),
				),

				$this->table_prefix . 'space_fleet_ships' => array(
					'COLUMNS'		=> array(
						'fleet_id'			=> array('UINT', 0),
						'ship_name'			=> array('VCHAR', ''),
						'num_ships'			=> array('UINT', 0),
					),
					'PRIMARY_KEY'	=> array('fleet_id', 'ship_name'),
				),

				$this->table_prefix . 'space_planets' => array(
					'COLUMNS'		=> array(
						'planet_id'			=> array('UINT', 0),
						'mquad_id'			=> array('UINT', 0),
						'quad_id'			=> array('UINT', 0),
						'row_id'			=> array('UINT', 0),

						'user_id'			=> array('UINT', 0),
						'ally_id'			=> array('UINT', 0),
						'fleet_id'			=> array('UINT', 0),

						'planet_name'		=> array('VCHAR', ''),
						'planet_base'		=> array('BOOL', 0),
						'planet_status'		=> array('UINT:4', 0),

						'resource_one'		=> array('UINT', 0),
						'resource_two'		=> array('UINT', 0),
						'resource_three'	=> array('UINT', 0),
						'resource_four'		=> array('UINT', 0),

						'planet_time'		=> array('TIMESTAMP', 0),
						'last_fill_stocks'	=> array('TIMESTAMP', 0),
						'productivity'		=> array('UINT:4', 100),
					),
					'PRIMARY_KEY'		=> 'planet_id',
					'KEYS'		=> array(
						'mqq'			=> array('INDEX', array('mquad_id', 'quad_id')),
						'uid'		=> array('INDEX', 'user_id'),
					),
				),

				$this->table_prefix . 'space_planet_buildings' => array(
					'COLUMNS'		=> array(
						'planet_id'			=> array('UINT', 0),
						'building_name'		=> array('VCHAR', ''),
						'building_level'	=> array('UINT', 0),
					),
					'PRIMARY_KEY'	=> array('planet_id', 'building_name'),
				),

				$this->table_prefix . 'space_queue' =>  array(
					'COLUMNS'		=> array(
						'queue_id'			=> array('UINT', NULL, 'auto_increment'),

						'planet_id'			=> array('UINT', 0),
						'user_id'			=> array('UINT', 0),
						'ally_id'			=> array('UINT', 0),
						'fleet_id'			=> array('UINT', 0),

						'scnd_planet_id'	=> array('UINT', 0),
						'scnd_user_id'		=> array('UINT', 0),
						'scnd_ally_id'		=> array('UINT', 0),
						'scnd_fleet_id'		=> array('UINT', 0),

						'queue_mode'		=> array('TINT:3', 0),
						'queue_task'		=> array('VCHAR', ''),
						'queue_extra'		=> array('VCHAR', ''),
						'run_time'			=> array('TIMESTAMP', 0),
					),
					'PRIMARY_KEY'		=> 'queue_id',
					'KEYS'		=> array(
						'pid'		=> array('INDEX', 'planet_id'),
						'spid'=> array('INDEX', 'scnd_planet_id'),
					),
				),

				$this->table_prefix . 'space_spy' => array(
					'COLUMNS'		=> array(
						'user_id'			=> array('UINT', 0),
						'ally_id'			=> array('UINT', 0),

						'mquad_id'			=> array('UINT', 0),
						'quad_id'			=> array('UINT', 0),
						'row_id'			=> array('UINT', 0),

						'spy_time'			=> array('TIMESTAMP', 0),
						'permanent_spy'		=> array('BOOL', 0),
					),
					'KEYS'		=> array(
						'umq'		=> array('UNIQUE', array('user_id', 'mquad_id', 'quad_id', 'row_id')),
						'amq'		=> array('INDEX', array('ally_id', 'mquad_id', 'quad_id')),
					),
				),

				$this->table_prefix . 'space_statistics' => array(
					'COLUMNS'		=> array(
						'user_id'						=> array('UINT', 0),


						'building_resource_one'		=> array('BINT', 0),
						'building_resource_two'		=> array('BINT', 0),
						'building_resource_three'	=> array('BINT', 0),
						'building_resource_four'	=> array('BINT', 0),

						'technology_resource_one'		=> array('BINT', 0),
						'technology_resource_two'		=> array('BINT', 0),
						'technology_resource_three'		=> array('BINT', 0),
						'technology_resource_four'		=> array('BINT', 0),

						'battle_resource_one'		=> array('BINT', 0),
						'battle_resource_two'		=> array('BINT', 0),
						'battle_resource_three'		=> array('BINT', 0),
						'battle_resource_four'		=> array('BINT', 0),
					),
					'PRIMARY_KEY'		=> 'user_id',
				),

				$this->table_prefix . 'space_users' => array(
					'COLUMNS'		=> array(
						'user_id'				=> array('UINT', 0),
						'username'				=> array('VCHAR', ''),
						'username_clean'		=> array('VCHAR', ''),

						'ally_id'				=> array('UINT', 0),
						'ally_pending'			=> array('UINT', 0),
						'ally_tag'				=> array('VCHAR', ''),
						'ally_leader'			=> array('BOOL', 0),

						'user_cur_quad'			=> array('VCHAR:64', '0:0'),
						'user_cur_planet'		=> array('UINT', 0),
						'num_planets'			=> array('UINT', 0),
						'max_planets'			=> array('UINT', 0),

						'user_gameplay_start'	=> array('TIMESTAMP', 0),
						'user_vacation'			=> array('BOOL', 0),
						'vacation_start'		=> array('TIMESTAMP', 0),
						'vacation_minimum'		=> array('TIMESTAMP', 0),

						'building_points'		=> array('BINT', 0),
						'technology_points'		=> array('BINT', 0),
						'battle_points'			=> array('BINT', 0),
						'total_points'			=> array('BINT', 0),
						'total_place'			=> array('UINT', 0),

						'last_building_points'	=> array('BINT', 0),
						'last_technology_points'=> array('BINT', 0),
						'last_battle_points'	=> array('BINT', 0),
						'last_total_points'		=> array('BINT', 0),
						'last_total_place'		=> array('UINT', 0),

						'notify_quad_spy'		=> array('BOOL', 1),
					),
					'PRIMARY_KEY'	=> 'user_id',
					'KEYS'			=> array(
						'uaid'			=> array('INDEX', array('ally_id')),
						'utp'		=> array('INDEX', array('total_points')),
					),
				),

				$this->table_prefix . 'space_user_technologies' => array(
					'COLUMNS'		=> array(
						'user_id'			=> array('UINT', 0),
						'technology_name'	=> array('VCHAR', ''),
						'technology_level'	=> array('UINT', 0),
					),
					'PRIMARY_KEY'	=> array('user_id', 'technology_name'),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'space_alliances',
				$this->table_prefix . 'space_favorites',
				$this->table_prefix . 'space_fleets',
				$this->table_prefix . 'space_fleet_ships',
				$this->table_prefix . 'space_planets',
				$this->table_prefix . 'space_planet_buildings',
				$this->table_prefix . 'space_queue',
				$this->table_prefix . 'space_spy',
				$this->table_prefix . 'space_statistics',
				$this->table_prefix . 'space_users',
				$this->table_prefix . 'space_user_technologies',
			),
		);
	}
}
