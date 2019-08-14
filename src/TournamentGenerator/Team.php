<?php

namespace TournamentGenerator;

/**
 *
 */
class Team
{

	public $name = 'team';
	public $id = '';
	public $games = [];
	public $gamesWith = [];
	public $sumPoints = 0;
	public $sumScore = 0;

	/**
	* ARRAY WITH GROUPS AND IT'S RESULTS
	* array (
	* * groupId => array (
	* * * "group"  => Group, # GROUP OBJECT
	* * * "points" => int 0, # NUMBER OF POINTS AQUIRED
	* * * "score"  => int 0, # SUM OF SCORE AQUIRED
	* * * "wins"   => int 0, # NUMBER OF WINS
	* * * "draws"  => int 0, # NUMBER OF DRAWS
	* * * "losses" => int 0, # NUMBER OF LOSSES
	* * * "second" => int 0, # NUMBER OF TIMES BEING SECOND (ONLY FOR INGAME OPTION OF 3 OR 4)
	* * * "third"  => int 0  # NUMBER OF TIMES BEING THIRD  (ONLY FOR INGAME OPTION OF 4)
	* * )
	*)
	*/
	public $groupResults = [];

	function __construct(string $name = 'team', $id = null) {
		$this->name = $name;
		$this->id = (isset($id) ? $id : uniqid());
	}
	public function __toString() {
		return $this->name;
	}
	public function getGamesInfo($groupId) {
		return array_filter($this->groupResults[$groupId], function($k) { return $k !== 'group'; }, ARRAY_FILTER_USE_KEY);
	}

	public function addGameWith(Team $team, Group $group) {
		if (!isset($this->gamesWith[$group->id][$team->id])) $this->gamesWith[$group->id][$team->id] = 0;
		$this->gamesWith[$group->id][$team->id]++;
		return $this;
	}
	public function addGroup(Group $group) {
		if (!isset($this->games[$group->id])) $this->games[$group->id] = [];
		return $this;
	}
	public function addGame(Game $game) {
		$group = $game->getGroup();
		if (!isset($this->games[$group->id])) $this->games[$group->id] = [];
		$this->games[$group->id][] = $game;
		return $this;
	}

	public function addWin(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] += $this->groupResults[$groupId]['group']->winPoints;
		$this->sumPoints += $this->groupResults[$groupId]['group']->winPoints;
		$this->groupResults[$groupId]['wins']++;
		return $this;
	}
	public function removeWin(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] -= $this->groupResults[$groupId]['group']->winPoints;
		$this->sumPoints -= $this->groupResults[$groupId]['group']->winPoints;
		$this->groupResults[$groupId]['wins']--;
		return $this;
	}
	public function addDraw(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] += $this->groupResults[$groupId]['group']->drawPoints;
		$this->sumPoints += $this->groupResults[$groupId]['group']->drawPoints;
		$this->groupResults[$groupId]['draws']++;
		return $this;
	}
	public function removeDraw(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] -= $this->groupResults[$groupId]['group']->drawPointsPoints;
		$this->sumPoints -= $this->groupResults[$groupId]['group']->drawPoints;
		$this->groupResults[$groupId]['draws']--;
		return $this;
	}
	public function addLoss(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] += $this->groupResults[$groupId]['group']->lostPoints;
		$this->sumPoints += $this->groupResults[$groupId]['group']->lostPoints;
		$this->groupResults[$groupId]['losses']++;
		return $this;
	}
	public function removeLoss(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] -= $this->groupResults[$groupId]['group']->lostPoints;
		$this->sumPoints -= $this->groupResults[$groupId]['group']->lostPoints;
		$this->groupResults[$groupId]['losses']--;
		return $this;
	}
	public function addSecond(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] += $this->groupResults[$groupId]['group']->secondPoints;
		$this->sumPoints += $this->groupResults[$groupId]['group']->secondPoints;
		$this->groupResults[$groupId]['second']++;
		return $this;
	}
	public function removeSecond(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] -= $this->groupResults[$groupId]['group']->secondPoints;
		$this->sumPoints -= $this->groupResults[$groupId]['group']->secondPoints;
		$this->groupResults[$groupId]['second']--;
		return $this;
	}
	public function addThird(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] += $this->groupResults[$groupId]['group']->thirdPoints;
		$this->sumPoints += $this->groupResults[$groupId]['group']->thirdPoints;
		$this->groupResults[$groupId]['third']++;
		return $this;
	}
	public function removeThird(string $groupId = ''){
		if (!isset($this->groupResults[$groupId])) throw new \Exception('Group '.$groupId.' is not set for this team ('.$this->name.')');
		$this->groupResults[$groupId]['points'] -= $this->groupResults[$groupId]['group']->thirdPoints;
		$this->sumPoints -= $this->groupResults[$groupId]['group']->thirdPoints;
		$this->groupResults[$groupId]['third']--;
		return $this;
	}
	public function sumPoints(array $groupIds = []) {
		if (count($groupIds) === 0) return $this->sumPoints;
		$sum = 0;
		foreach ($groupIds as $gid) {
			if (isset($this->groupResults[$gid])) $sum += $this->groupResults[$gid]['points'];
		}
		return $sum;
	}
	public function sumScore(array $groupIds = []) {
		if (count($groupIds) === 0) return $this->sumScore;
		$sum = 0;
		foreach ($groupIds as $gid) {
			if (isset($this->groupResults[$gid])) $sum += $this->groupResults[$gid]['score'];
		}
		return $sum;
	}
}