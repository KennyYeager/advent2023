<?php

/*
Based on the prompt, we need to parse out a list of values. We'll need
to split the string based on a colon to get the "game number" and record that
as a key. Then we'll need to further split the other side of the string into
game outcomes and determine if any of them are impossible.
For ease of reference: an impossible game is one in which any of the handfuls of
cubes has more than 12 red, 13 green, or 14 blue.
*/

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day2.txt", "r");

# create the main array and the max possible
$gamesPossible = [];
$maxBlue = 14;
$maxGreen = 13;
$maxRed = 12;

# loop through the lines in the file
while(!feof($input_file)){
    # get the raw line
    $raw_string = strtolower(fgets($input_file));
    # split each line by the ":" to set up for key:value pairs
    $game_number = explode(" ", explode(":", $raw_string)[0])[1]; # the key
    # create array of reveals for each game
    $reveals_strings = explode(";", explode(": ", $raw_string)[1]);
    $reveal_possible = true;
    # split the reveals into an array for comparison
    foreach($reveals_strings as $raw_reveal){
        $reveal = trim($raw_reveal);
        #var_dump($reveal);
        if(str_contains($reveal, ",")){
            foreach(explode(", ", $reveal) as $pull){
                switch(explode(" ", $pull)[1]){
                    case "blue";
                        if((int)explode(" ", $pull)[0] > $maxBlue){
                            $reveal_possible = false;
                        }
                        break;
                    case "green";
                        if((int)explode(" ", $pull)[0] > $maxGreen){
                            $reveal_possible = false;
                        }
                        break;
                    case "red";
                        if((int)explode(" ", $pull)[0] > $maxRed){
                            $reveal_possible = false;
                        }
                        break;
                    default;
                        echo "failed to find a color name string match for pull[1]: " . explode(" ", $pull)[1] . "\n";
                        break;
                }
            }
        } else {
            switch(explode(" ", $pull)[1]){
                case "blue";
                    if((int)explode(" ", $pull)[0] > $maxBlue){
                        $reveal_possible = false;
                    }
                    break;
                case "green";
                    if((int)explode(" ", $pull)[0] > $maxGreen){
                        $reveal_possible = false;
                    }
                    break;
                case "red";
                    if((int)explode(" ", $pull)[0] > $maxRed){
                        $reveal_possible = false;
                    }
                    break;
                default;
                    echo "failed to find a color name string match for pull[1]: " . explode(" ", $pull)[1] . "\n";
                    break;
            }
        }
    }
    $gamesPossible[] = array($game_number, $reveal_possible);
}

# close the input file
fclose($input_file);

# output the results
$sum_of_game_ids = 0;
foreach($gamesPossible as $game){
    echo "Game number " . $game[0] . " is " . ((bool)$game[1] ? "possible" : "not possible") . "!\n";
    if((bool)$game[1]){
        $sum_of_game_ids += (int)$game[0];
    }
}
echo "The sum of game IDs where the game is possible is: " . $sum_of_game_ids . "\n";
?>