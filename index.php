<?php

require 'classes/Team.php';
require 'classes/FootballMatch.php';
require 'classes/League.php';


$chelsea = new Team("Chelsea", 5);
$arsenal = new Team("Arsenal", 4);
$manCity = new Team("Manchester City", 4);
$liverpool = new Team("Liverpool", 3);


$league = new League();
$league->addTeam($chelsea);
$league->addTeam($arsenal);
$league->addTeam($manCity);
$league->addTeam($liverpool);


$league->playRound();


$league->getLeagueTable();
