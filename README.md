[![wakatime](https://wakatime.com/badge/user/d7f8cf89-fee2-46da-89df-70b82216f2c2/project/865369a2-df6a-49f2-b863-924daf5e8036.svg)](https://wakatime.com/badge/user/d7f8cf89-fee2-46da-89df-70b82216f2c2/project/865369a2-df6a-49f2-b863-924daf5e8036)
# Insider Champions League Simulation

This project simulates a football league with four teams using PHP and OOP. It generates match results, calculates the league table, and displays team statistics.

## Installation & Usage

1. **Install PHP** (if not already installed):
   - Download from  [php.net](https://php.net/).
2. **Clone the project:**
   ```shell
   git clone https://github.com/RasimAghayev/task-InsiderChampionsLeague-php.git && cd task-InsiderChampionsLeague-php
    ```
3. **Run the project:**
   ```shell
   php index.php
   ```

## Project Structure

 * ``Team`` Class:
   * Stores team details (name, strength, points, goals, etc.).
   * Calculates goal difference with ```getGoalDifference()```.
 * ``FootballMatch`` Class:
   * Simulates matches between two teams.
   * Generates random goals based on team strength. 
* ``League`` Class:
  * Manages teams and matches.
  * ``playRound()`` simulates all matches in a round.
  * ``getLeagueTable()`` displays the league table.

## Example Usage

**Create Teams**
```php
$chelsea = new Team("Chelsea", 5);
$arsenal = new Team("Arsenal", 4);
$manCity = new Team("Manchester City", 4);
$liverpool = new Team("Liverpool", 3);
```
**Create League and Add Teams**
```php
$league = new League();
$league->addTeam($chelsea);
$league->addTeam($arsenal);
$league->addTeam($manCity);
$league->addTeam($liverpool);
```

**Simulate Matches and Display Table**
```php
$league->playRound();
$league->getLeagueTable();
```

## Sample Output
```
League Table
| Team            | PTS | P | W | D | L | GD |
| Chelsea         | 13  | 5 | 4 | 1 | 0 | 14 |
| Arsenal         | 9   | 5 | 2 | 3 | 0 | 6  |
| Manchester City | 8   | 5 | 2 | 2 | 1 | 4  |
| Liverpool       | 5   | 5 | 1 | 2 | 2 | -2 |
```
