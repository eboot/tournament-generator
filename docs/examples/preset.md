
## Introduction

You also have an ability to use predefined tournament [tempates](/templates/list.md).

[Templates](/templates/list.md) are predefined classes, that make it easier to create commonly used tournament brackets with any number of [Teams](/templates/team.md). All [templates](/templates/list.md) are used in a similar way - see the [list of templates](/templates/list.md).

---

## Creating a tournament

In this example, we will be looking at creating a single elimination bracket.

Start with creating a new [SingleElimination](/templates/singleElim.md) class.

```php
require 'vendor/autoload.md';

// Create a tournament
$tournament = new TournamentGenerator\Preset\SingleElimination('Tournament name');
```

You can also set play time, time between games and time between rounds to later calculate a timetable of the whole tournament

```php
// Set tournament lengths - could be omitted
$tournament
	->setPlay(7) // SET GAME TIME TO 7 MINUTES
	->setGameWait(2) // SET TIME BETWEEN GAMES TO 2 MINUTES
	->setRoundWait(0); // SET TIME BETWEEN ROUNDS TO 0 MINUTES
```

---

## Adding teams

Now, you can add **[Teams](/classes/team.md)** to your tournament.

We will add just a few, but you can populate your tournament with your own teams.

```php
// CREATE 6 TEAMS
for ($i=1; $i <= 6; $i++) {
	$tournament->team('Team '.$i, $i); // Creates a team with name 'Team $i' and id of $i
}
```

Now, we generate all the games using a [generate()](/templates/singleElim.md#generate) method.

```php
// GENERATE ALL GAMES
$tournament->generate();
```

---

## Generating results

At last you set results to all your **[Games](/classes/game.md)** one by one with your own scores.

```php
// Get all rounds
$rounds = $tournament->getRounds();

// Get all games in a round
$games = $round[0]->getGames();

// Set game results
$games[0]->setResults(
	[
		mixed 'teamID' => int 25, // FIRST  TEAM SCORE
		mixed 'teamID' => int 50  // SECOND TEAM SCORE
	]
);
// Continue for all other games and rounds
```

Or you simulate all the **[Rounds](/classes/round.md)**.

```php
// Get all rounds
$rounds = $tournament->getRounds();

//Simulate all rounds
foreach ($rounds as $round) {
	$round->simulate(); // Simulate games without scores, just to save a bracket
}
```

Or you can simulate a whole **[Tournament](/classes/tournament.md)** at once.

```php
// Simulate games
$tournament->genGamesSimulate(); // Simulate only games for example to only save bracket to DB
$tournament->genGamesSimulateReal(); // Simulate games with results like a real tournament
```

---

## Getting final results

Finally, you can get all the **[Teams](/classes/team.md)** ordered by their results.

```php
// GET ALL TEAMS
$teams = $tournament->getTeams(true); // TRUE to get teams ordered by their results
```
