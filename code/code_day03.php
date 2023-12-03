<?php

/*
Based on the prompt, we're processing a file that has variable-length numbers
in a fixed-width file. We need to be able keep track of where these numbers are
so we can look for adjacent special characters nearby.
*/

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day03.txt", "r");
#$input_file = fopen("../inputs/sample_day03_part1.txt", "r");

# decided to get line count and length dynamically to enable easily
# switching between sample and full input data
$line_count = 0;
$line_length = 0;
# turning the flat file into a grid in hope of that making things easier to work with
$dataset = []; #row_num, column_num
while(!feof($input_file)){
    $current_line = trim(fgets($input_file)); #have to trim() to avoid newline issues
    if($line_count == 0){ $line_length = strlen($current_line); }
    $character_array = str_split($current_line);
    $character_iterator = 0;
    $columns = [];
    foreach($character_array as $current_character){
        $columns[] = $current_character;
        $character_iterator++;
    }
    $dataset[] = $columns;
    $line_count++;
}
# close the input file
fclose($input_file);

$numeric_string_locations = []; #row_num, column_num, length
$numeric_strings = []; #number, use/don't use
# loop through dataset, to get locations of any special characters.
for($y = 0; $y < $line_count; $y++){
    for($x = 0; $x < $line_length; $x++){
        $this_character = $dataset[$y][$x];
        if(is_numeric($this_character)){
            $number_string = $this_character;
            $checking_for_more_numbers = true;
            $number_length = 1;
            while($checking_for_more_numbers && $x + $number_length < $line_length){
                $look_ahead = $dataset[$y][$x + $number_length];
                if(is_numeric($look_ahead)){
                    $number_string = $number_string . $look_ahead;
                    $number_length++;
                } else {
                    $checking_for_more_numbers = false;
                }
            }
            $numeric_string_locations[] = array($y, $x, $number_length);
            $use_in_calculation = number_has_adjacent_special_character($dataset, $y, $x, $number_length, $line_count, $line_length);
            $numeric_strings[] = array((int)$number_string, $use_in_calculation);
            # the $x fixer that prevents double-reading a number
            $x = $x + $number_length - 1;
        }
    }
}

# output the results
$sum_of_used_numbers = 0;
echo "The output: \n";
foreach($numeric_strings as $avalue){
    $use_number = $avalue[1];
    if($use_number){
        $found_number = $avalue[0];
        $sum_of_used_numbers += $found_number;
    }
}
echo "$sum_of_used_numbers\n";

function number_has_adjacent_special_character($dataset, $row, $column, $length, $line_count, $line_length){
    $use_number_in_calculation = false;
    $starting_y = ($row - 1 < 0 ? 0 : $row -1);
    $ending_y = ($row + 1 >= $line_count ? $line_count - 1 : $row + 1);
    $starting_x = ($column - 1 < 0 ? 0 : $column -1);
    $ending_x = ($column + $length >= $line_length ? $line_length - 1 : $column + $length);
    for($y = $starting_y; $y <= $ending_y; $y++){
        for($x = $starting_x; $x <= $ending_x; $x++){
            $tested_character = $dataset[$y][$x];
            if($tested_character <> "." && !is_numeric($tested_character)) { return true; }
        }
    }
    return $use_number_in_calculation;
}
?>  