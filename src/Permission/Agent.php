<?php

namespace Railken\Laravel\Manager\Permission;

use Railken\Laravel\Manager\ModelContract;


class Agent implements AgentContract
{

	/**
	 * List of all permission
	 *
	 * @var array
	 */
	protected $permissions = [];

	/**
	 * Construct
	 *
	 * @param UserContract $user
	 */
	public function __construct(UserContract $user = null)
	{

		$this->user = $user;

		$this->p = [];

		foreach ($this->permissions as $permission) {


			$parts = explode("|", $permission);

			$permission = $parts[0];

			$group = isset($parts[1]) ? $parts[1] : "a";

			$this->p[] = (object)[
				'permission' => explode(".", $permission),
				'group' => $group
			];
		}
	}

	/**
	 * Retrieve user
	 * 
	 * @return UserContract
	 */
	public function getUser()
	{
		return $this->user;
	}

	public function is($permission, $permissions)
	{

		foreach ($permissions as $p) {


			$pp = explode(".", $permission);

			$has = false;


			foreach ($p->permission as $k => $in) {

				$has = false;


				if ($p->permission[$k] == '*') {
					$has = true;
					break;
				}

				if (!isset($pp[$k]))
					break;


				if ($pp[$k] != $p->permission[$k]) {
					break;
				} 

				$has = true;
			}

			if ($has)
				return $p;

		}

		return null;
	}

	/**
	 * Retrieve true/false given a permission and resource
	 *
	 * @param string $permission
	 * @param ModelResource $resource
	 *
	 * @return boolean
	 */
	protected function can($permission, ModelContract $resource) {

		$permission = $this->is($permission, $this->p);


		if (!$permission)
			return false;

		$group = $permission->group;

		if (strpos($group, 'a') !== false || $group == '') {
			return true;
		}

		if (strpos($group, 'o') !== false) {

			
			if ($this->getUser() && $this->getUser()->getId() == $resource->getUser()->getId()) {
				return true;
			}

		}

		return false;

	}
	
}