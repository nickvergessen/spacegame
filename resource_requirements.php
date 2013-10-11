<?php
/**
*
* @package SpaceGame Resource Requirements
* @copyright (c) 2013 schilljs
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace schilljs\spacegame;

abstract class resource_requirements
{
	protected $times = array();

	protected $costs = array();

	protected $buildings = array();

	protected $technologies = array();

	/**
	* Returns the time for a given level
	*
	* @param	int		$level	Level which we want the requirements for
	* @return		array	The time for the given level
	*/
	public function get_time($level)
	{
		return $this->calculate_resource($this->times, $level);
	}

	/**
	* Returns the cost requirements for a given level
	*
	* @param	int		$level	Level which we want the requirements for
	* @return		array	The cost requirements for the given level
	*/
	public function get_costs($level)
	{
		return $this->calculate_requirements($this->costs, $level);
	}

	/**
	* Returns the building requirements for a given level
	*
	* @param	int		$level	Level which we want the requirements for
	* @return		array	The building requirements for the given level
	*/
	public function get_buildings($level)
	{
		return $this->calculate_requirements($this->buildings, $level);
	}

	/**
	* Returns the tech requirements for a given level
	*
	* @param	int		$level	Level which we want the requirements for
	* @return		array	The technology requirements for the given level
	*/
	public function get_technologies($level)
	{
		return $this->calculate_requirements($this->technologies, $level);
	}

	/**
	* Loop over a set of requirements and calculate the required resources for the level
	*
	* @param	array	$data	Array with the list of requirements and their details
	* @param	int		$level	Level which we want the requirements for
	* @return		array	The requirements for the given level for each resource
	*/
	public function calculate_requirements($data, $level)
		$requirements = array();

		foreach ($data as $resource => $requirement)
		{
			$requirements[$resource] = $this->calculate_resource($requirement, $level);
		}

		return $requirements;
	}

	/**
	* Calculates the requirement of a resource for a given level
	*
	* Formula: y = flat + linear * x + square * x^2
	*
	* @param	array	$data	Array with the requirements, possible keys flat, linear and square
	* @param	int		$level	Level which we want the requirements for
	* @return		int		The resource requirements for the given level
	*/
	protected function calculate_resource($data, $level)
	{
		$resource = 0;

		if (isset($data['flat']))
		{
			$resource += $data['flat'];
		}

		if (isset($data['linear']))
		{
			$resource += $data['linear'] * $level;
		}

		if (isset($data['square']))
		{
			$resource += $data['square'] * pow($level, 2);
		}

		return $resource;
	}
}
