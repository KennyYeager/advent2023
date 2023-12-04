<?php

/*
Based on the prompt, we need to parse a file with a number of "winning numbers"
and a second series of numbers that are "held" numbers. This will mean needing
to create two arrays and comparing the content. The question is whether it's
better to create these arrays first then cycle thorugh them or just work through
the file line by line and not keep the arrays. Keeping the arrays is not 
needed for part one, but may become meaningful in part two.
*/

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day04.txt", "r");
#$input_file = fopen("../inputs/sample_day04_part1.txt", "r");

# define variables needed throughout the script
$games_and_their_values = [];
$sum_of_points = 0;

# do stuff with the input file
# for now, we're going to do just a little more than necessary
# for part one in hope of it being relevant to part two later
while(!feof($input_file)){
    # parse the line
    $raw_string = strtolower(fgets($input_file));
    # first division separates out game number from the number strings
    $game_info = explode(":", $raw_string)[0];
    $numbers_arrays = explode(" | ", explode(":", $raw_string)[1]);
    $game_number = explode(" ", $game_info)[1];
    # second division separates winning numbers from losing numbers
    $winning_numbers = explode(" ", str_replace("  ", " ", trim($numbers_arrays[0])));
    $held_numbers = explode(" ", str_replace("  ", " ", trim($numbers_arrays[1])));
    # now we need to do the main logic: comparing held numbers to winning numbers
    $numbers_matched = [];
    $this_games_points = 0;
    foreach($held_numbers as $held_number){
        # iterate through each winning number
        foreach($winning_numbers as $winning_number){
            # if the held number equals the winning number
            if ($held_number == $winning_number){
                # add the number to the numbers_matched array
                $numbers_matched[] = $held_number;
            }
        }
    }
    # do the math to find out how many points are earned
    foreach($numbers_matched as $number){
        if($this_games_points == 0){
            $this_games_points = 1;
        } else {
            $this_games_points = $this_games_points * 2;
        }
    }
    # add the final result to the $games_and_their_values array
    $games_and_their_values[] = array($game_number, $this_games_points);
}

# close the input file
fclose($input_file);

#final calculations
foreach($games_and_their_values as $this_game){
    $sum_of_points += $this_game[1];
}

# output the results
echo "The output: \n";
echo "The sum of all points is: " . $sum_of_points . "\n";

?>