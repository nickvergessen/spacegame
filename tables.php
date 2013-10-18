<?php
/**
*
* @package SpaceGame Core
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace schilljs\spacegame;

class tables
{
	/**
	* Constructor
	*
	* @param string $table_prefix
	*/
	public function __construct($table_prefix)
	{
		$this->table_prefix = $table_prefix;
	}

	public function get($table_name)
	{
		return $this->table_prefix . 'space_' . $table_name;
	}
}
