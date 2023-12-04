<?php

/*
Based on the prompt, we need to parse a file with a number of "winning numbers"
and a second series of numbers that are "held" numbers. This will mean needing
to create two arrays and comparing the content. The question is whether it's
better to create these arrays first then cycle thorugh them or just work through
the file line by line and not keep the arrays. Keeping the arrays is not 
needed for part one, but may become meaningful in part two.
Turns out part two benefits from this approach. The only bit of extra data that
needs to be tracked is the quantity of matches in a given game. The real
addition is the calculations required at the end.
*/

# I worry I have a memory leak somewhere because I got the right answer but
# had to disable my memory_limit since I crashed on a previous run
ini_set("memory_limit", -1);

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day04.txt", "r");
#$input_file = fopen("../inputs/sample_day04_part1.txt", "r");

# define variables needed throughout the script
$games_and_their_values = []; #game number, points, quantity of matches
$sum_of_points = 0;
$total_number_of_cards = 0;

# for now, we're going to do just a little more than necessary
# for part one in hope of it being relevant to part two later
# turns out that helped since only minor tweaks were needed to the main reader
while(!feof($input_file)){
    # parse the line
    $raw_string = str_replace("  ", " ", str_replace("   ", " ", strtolower(fgets($input_file)))); # have to sanitize each line's fixed-width creating whitespace
    # first division separates out game number from the number strings
    $game_info = explode(":", $raw_string)[0];
    $game_number = explode(" ", $game_info)[1];
    $numbers_arrays = explode(" | ", explode(":", $raw_string)[1]);
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
    $games_and_their_values[] = array($game_number, $this_games_points, count($numbers_matched));
}

# close the input file
fclose($input_file);

# final calculations
# sum of points for games is straightforward
foreach($games_and_their_values as $this_game){
    $sum_of_points += $this_game[1];
}
# the logic for calculating matches for total number of cards is trickier
# fortunately, with the way that the matches are recorded, this shouldn't
# require any change to the data beyond tracking the number of matches in a given game
$number_of_unique_cards = count($games_and_their_values);
$loop_iterator = 0;
$still_iterating = true;
while($still_iterating)
{
    $this_game = $games_and_their_values[$loop_iterator];
    $game_number = $this_game[0];
    $number_of_matches = $this_game[2];
    $total_number_of_cards += 1;
    if($number_of_matches > 0){
        for($i = 1; $i <= $number_of_matches; $i++){
            $iterated_array_element = $game_number + $i - 1;
            if($iterated_array_element > $number_of_unique_cards){ break; }
            $games_and_their_values[] = $games_and_their_values[$iterated_array_element];
        }
    }
    $loop_iterator += 1;
    if($loop_iterator >= count($games_and_their_values)){ $still_iterating = false; }
}

# output the results
echo "The output: \n";
echo "The sum of all points is: " . $sum_of_points . "\n";
echo "The number of total held cards is: " . $total_number_of_cards . "\n";
?>