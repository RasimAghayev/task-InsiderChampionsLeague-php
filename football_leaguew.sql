CREATE TABLE teams
(
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(50) NOT NULL,
    strength      INT         NOT NULL,
    points        INT DEFAULT 0,
    played        INT DEFAULT 0,
    won           INT DEFAULT 0,
    drawn         INT DEFAULT 0,
    lost          INT DEFAULT 0,
    goals_for     INT DEFAULT 0,
    goals_against INT DEFAULT 0
);

CREATE TABLE matches
(
    id           SERIAL PRIMARY KEY,
    home_team_id INT REFERENCES teams (id),
    away_team_id INT REFERENCES teams (id),
    home_goals   INT,
    away_goals   INT,
    played_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);