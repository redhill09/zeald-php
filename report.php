<?php

/**
 * Use this file to output reports required for the SQL Query Design test.
 * An example is provided below. You can use the `asTable` method to pass your query result to,
 * to output it as a styled HTML table.
 */

require_once('vendor/autoload.php');
require_once('include/utils.php');
require_once('classes/Database.php');
$connect = new Database();

/*
 * Example Query
 * -------------
 * Retrieve all team codes & names
 */
echo '<h1>Example Query</h1>';
$teamSql = "SELECT * FROM team";
$teamResult = $connect->mysqli_db->query($teamSql);
// dd($teamResult);
echo asTable($teamResult);

/*
 * Report 1
 * --------
 * Produce a query that reports on the best 3pt shooters in the database that are older than 30 years old. Only 
 * retrieve data for players who have shot 3-pointers at greater accuracy than 35%.
 * 
 * Retrieve
 *  - Player name
 *  - Full team name
 *  - Age
 *  - Player number
 *  - Position
 *  - 3-pointers made %
 *  - Number of 3-pointers made 
 *
 * Rank the data by the players with the best % accuracy first.
 */
echo '<h1>Report 1 - Best 3pt Shooters</h1>';
// write your query here
$stmt = " SELECT
            r.name AS 'Player Name',
            t.name AS 'Team Name',
            pt.age AS 'Age',
            r.number AS 'Player NumBer',
            r.pos AS 'Position',
            ROUND((pt.3pt/pt.3pt_attempted)*100,2) AS '3pt%',
            pt.3pt as '3pt'
        FROM
            roster r
        LEFT JOIN
            team t ON r.team_code = t.code
        LEFT JOIN
            player_totals pt ON r.id = pt.player_id
        WHERE 
            pt.age > 30
        AND
            (pt.3pt/pt.3pt_attempted)*100 > 35
        ORDER BY
            ROUND((pt.3pt/pt.3pt_attempted)*100,2) DESC

        ";
$output = $connect->mysqli_db->query($stmt);
echo asTable($output);


/*
 * Report 2
 * --------
 * Produce a query that reports on the best 3pt shooting teams. Retrieve all teams in the database and list:
 *  - Team name
 *  - 3-pointer accuracy (as 2 decimal place percentage - e.g. 33.53%) for the team as a whole,
 *  - Total 3-pointers made by the team
 *  - # of contributing players - players that scored at least 1 x 3-pointer
 *  - of attempting player - players that attempted at least 1 x 3-point shot
 *  - total # of 3-point attempts made by players who failed to make a single 3-point shot.
 * 
 * You should be able to retrieve all data in a single query, without subqueries.
 * Put the most accurate 3pt teams first.
 */
echo '<h1>Report 2 - Best 3pt Shooting Teams</h1>';
// write your query here
$stmt = " SELECT
            t.name AS 'Team Name',
            
            ROUND(SUM(pt.3pt)/SUM(pt.3pt_attempted)*100,2) as '3pt%',
            SUM(pt.3pt) as '3pt',
            COUNT(case when pt.3pt > 0 then 1 else null end) as '3pt made',
            COUNT(case when pt.3pt_attempted > 0 then 1 else null end) as '3pt Attempt',
            COUNT(case when pt.3pt_attempted = 0 then 1 else null end) as 'Failed 3pt'
        FROM
            roster r
        LEFT JOIN
            team t ON r.team_code = t.code
        LEFT JOIN
            player_totals pt ON r.id = pt.player_id
        GROUP BY
            t.code
        ORDER BY
            ROUND(SUM(pt.3pt)/SUM(pt.3pt_attempted)*100,2) DESC
        
        ";
$output = $connect->mysqli_db->query($stmt);
echo asTable($output);

?>