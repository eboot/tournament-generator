<?php

namespace TournamentGenerator;

/**
 *
 */
class Round
{

	public $name = '';
	public $id = '';
	private $groups = [];
	private $games = [];
	private $teams = [];
	private $allowSkip = false;

	function __construct(string $name = '') {
		$this->id = uniqid();
		$this->name = $name;
	}
	public function __toString() {
		return $this->name;
	}

	public function addGroup(Group ...$groups){
		foreach ($groups as $group) {
			if ($group instanceof Group) $this->groups[] = $group;
			else throw new \Exception('Trying to add group which is not an instance of Group class.');
		}
		return $this;
	}
	public function group(string $name) {
		$g = new Group($name);
		$this->groups[] = $g->setSkip($this->allowSkip);
		return $g;
	}
	public function getGroups(){
		$this->orderGroups();
		return $this->groups;
	}
	public function getGroupsIds() {
		$this->orderGroups();
		return array_map(function($a) { return $a->id; }, $this->groups);
	}
	public function orderGroups() {
		usort($this->groups, function($a, $b){
			return $a->getOrder() - $b->getOrder();
		});
	}

	public function allowSkip(){
		$this->allowSkip = true;
		return $this;
	}
	public function disallowSkip(){
		$this->allowSkip = false;
		return $this;
	}
	public function setSkip(bool $skip = false) {
		$this->allowSkip = $skip;
		return $this;
	}
	public function getSkip() {
		return $this->allowSkip;
	}

	public function genGames(){
		foreach ($this->groups as $group) {
			$group->genGames();
			$this->games = array_merge($this->games, $group->orderGames());
		}
		return $this->games;
	}
	public function getGames() {
		return $this->games;
	}
	public function isPlayed(){
		foreach ($this->groups as $group) {
			if (!$group->isPlayed()) return false;
		}
		return true;
	}

	public function addTeam(...$teams) {
		foreach ($teams as $team) {
			if ($team instanceof Team)  {
				$this->teams[] = $team;
			}
			elseif (gettype($team) === 'array') {
				foreach ($team as $team2) {
					if ($team2 instanceof Team) $this->teams[] = $team2;
					$team2->groupResults[$this->id] = [
						'group' => $this,
						'points' => 0,
						'score'  => 0,
						'wins'   => 0,
						'draws'  => 0,
						'losses' => 0,
						'second' => 0,
						'third'  => 0
					];
				}
			}
			else throw new \Exception('Trying to add team which is not an instance of Team class');
		}
		return $this;
	}
	public function team(string $name = '') {
		$t = new Team($name);
		$this->teams[] = $t;
		return $t;
	}
	public function getTeams(bool $ordered = false, $ordering = \TournamentGenerator\Constants::POINTS) {
		if (count($this->teams) == 0) {
			$teams = [];
			foreach ($this->groups as $group) {
				$teams = array_merge($teams, $group->getTeams());
			}
			$this->teams = $teams;
		}
		if ($ordered) {
			$this->sortTeams($ordering);
		}
		return $this->teams;
	}
	public function sortTeams($ordering = \TournamentGenerator\Constants::POINTS) {
		Utilis\Sorter\Teams::sortRound($this->teams, $this, $ordering);
		return $this->teams;
	}

	public function splitTeams(...$groups) {

		if (count($groups) === 0) $groups = $this->getGroups();

		foreach ($groups as $key => $value) {
			if (gettype($value) === 'array') {
				unset($groups[$key]);
				$groups = array_merge($groups, $value);
			}
		}

		$teams = $this->getTeams();
		shuffle($teams);

		while (count($teams) > 0) {
			foreach ($groups as $group) {
				if ($group instanceof Group) {
					if (count($teams) > 0) $group->addTeam(array_shift($teams));
				}
			}
		}
		return $this;
	}

	public function progress(bool $blank = false){
		foreach ($this->groups as $group) {
			$group->progress($blank);
		}
		return $this;
	}

	public function simulate() {
		Utilis\Simulator::simulateRound($this);
		return $this;
	}
	public function resetGames() {
		foreach ($this->groups as $group) {
			$group->resetGames();
		}
		return $this;
	}
}