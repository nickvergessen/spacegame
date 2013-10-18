<?php
/**
*
* @package SpaceGame Queue
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace schilljs\spacegame;

class queue
{
	/** @var \phpbb\db\driver\driver */
	protected $db;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver	$db
	* @param \phpbb\user				$user
	*/
	public function __construct(\phpbb\db\driver\driver $db)
	{
		$this->db = $db;
	}

	public function run()
	{
		return;
	}
}
