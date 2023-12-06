<?php

/*
Based on the prompt, we need to break apart a list of race times and current record distances.
The race is won by greatest distance traversed within the time limit.
Then we need to calculate how to win those races given the assumptions:
 - race time is fixed per race and measured in whole milliseconds
 - record length is measured in whole millimeters
 - boat speed is set by the length of time the toy boat's go button is held, in whole milliseconds
   - every millisecond (X) the button is pressed results in a speed of Xmm/ms
   - holding the button for 3ms results in the boat having a speed of 3mm/ms
 - we will assume dealing only in whole number with zero waste
 - victory is any distance greater than the existing record, there is more than one way to win a given race
The output data is calculated not by distance or time but by multiplying the possible ways to win each race
Example: race one can be won three ways, race two can be won four ways, so the needed output is 12 (3 x 4)

Part two is actually a lot simpler since it bypasses the need for arrays and multiplication
The downside is that this won't be as DRY as I would like
*/

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day06.txt", "r");
#$input_file = fopen("../inputs/sample_day06.txt", "r");

# declare global variables
$race_times = [];
$record_distances = [];
$part_two_race_time = 0;
$part_two_previous_record = 0;
$time_distance_pairs = []; #race number, time limit in ms, record distance to beat in mm
$race_winning_possibilities = []; #race number, number of ways to win
$calculation_of_possibilities = 0;
$part_two_possible_wins = 0;

# do stuff with the input file
while(!feof($input_file)) {
    # set up iteration-specific variables/arrays

    # get the raw line
    $raw_string = strtolower(fgets($input_file));

    # work through the line as needed
    $which_data = explode(":", $raw_string)[0];
    $array_values_of_data = explode(" ", trim(preg_replace('/\s+/',' ', explode(":", $raw_string)[1])));
    $part_two_trimmed_data = trim(str_replace(" ", "", explode(":", $raw_string)[1]));
    if($which_data == "time"){
        foreach($array_values_of_data as $time_data){
            $race_times[] = $time_data;
        }
        $part_two_race_time = $part_two_trimmed_data;
    } else {
        foreach($array_values_of_data as $distance_data){
            $record_distances[] = $distance_data;
        }
        $part_two_previous_record = $part_two_trimmed_data;
    }
}

# close the input file
fclose($input_file);

# pair the race times and distances
$number_of_races = count($race_times);
for($i = 0; $i < $number_of_races; $i++){
    $race_number = $i + 1;
    $time_distance_pairs[] = array($race_number, $race_times[$i], $record_distances[$i]);
}

# do the actual math to determine how many possible ways to win there are and record it
foreach($time_distance_pairs as $race_data){
    $race_number = $race_data[0];
    $race_time = (int)$race_data[1];
    $race_record = $race_data[2];
    $race_wins = 0;
    for($i = 1; $i < $race_time; $i++){ # no point starting at 0 or running until time limit since both will result in no movement
        $calculated_speed = $i;
        $time_left_for_travel = $race_time - $i;
        $calculated_distance = $calculated_speed * $time_left_for_travel;
        if($calculated_distance > $race_record){
            $race_wins++;
        }
    }
    $race_winning_possibilities[] = array($race_number, $race_wins);
}

# calculate the output data
foreach($race_winning_possibilities as $win_data){
    $race_number = $win_data[0];
    $possible_wins = $win_data[1];
    if($possible_wins == 0){
        continue;
    } else {
        if($calculation_of_possibilities == 0){
            $calculation_of_possibilities = $possible_wins;
        } else {
            $calculation_of_possibilities = $calculation_of_possibilities * $possible_wins;
        }
    }
}


for($i = 1; $i < $part_two_race_time; $i++){ # no point starting at 0 or running until time limit since both will result in no movement
    $calculated_speed = $i;
    $time_left_for_travel = $part_two_race_time - $i;
    $calculated_distance = $calculated_speed * $time_left_for_travel;
    if($calculated_distance > $part_two_previous_record){
        $part_two_possible_wins++;
    }
}

# output the results
echo "The output: \n";
echo "The calculated number derived from possible wins for part one is: " . $calculation_of_possibilities . "\n";
echo "The possible wins for part two is: " . $part_two_possible_wins . "\n";
?>