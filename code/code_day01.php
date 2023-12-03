<?php

/*
Based on the prompt, we need to parse the input file line by line. Each line contains an
alphanumeric string with at least one digit in it. We need to create a two-digit value
for each line, where the leftmost digit in the string is the first digit and the
rightmost digit in the string is the second. In the case of only one digit,
the output value will be that same digit twice.
These two-digit values need to be stored in an array because we will later
need to get the sum of all of the extracted two-digit values.
*/

# create the extracted values array
$extracted_values = [];

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day1.txt", "r");

# loop through the input file
while(!feof($input_file)){
    # parse the line
    $raw_string = strtolower(fgets($input_file));
    # we need to replace spelled out numbers with numeric values
    # and there are cases like "eightwo" where 8 is correct, not 2
    # but simple string replacement can grab the wrong value
    # so, we need to build a left-to-right reader to parse
    $parsing_for_spelled_numbers = true;
    $substring_length = 1;
    $corrected_string = $raw_string;
    while($parsing_for_spelled_numbers){
        if($substring_length > strlen($corrected_string)) {
            $parsing_for_spelled_numbers = false;
            break;
        }
        $checked_string = substr($corrected_string, 0, $substring_length);
        $has_match = true;
        if(str_contains($checked_string, "one")){
            $corrected_string = str_replace("one", "1", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "two")){
            $corrected_string = str_replace("two", "2", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "three")){
            $corrected_string = str_replace("three", "3", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "four")){
            $corrected_string = str_replace("four", "4", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "five")){
            $corrected_string = str_replace("five", "5", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "six")){
            $corrected_string = str_replace("six", "6", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "seven")){
            $corrected_string = str_replace("seven", "7", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "eight")){
            $corrected_string = str_replace("eight", "8", $checked_string) . substr($corrected_string, $substring_length - 1);
        }
        elseif(str_contains($checked_string, "nine")){
            $corrected_string = str_replace("nine", "9", $checked_string) . substr($corrected_string, $substring_length - 1);
        } else {
            $has_match = false;
        }

        if($has_match){
            $substring_length = 1;
        } else {
            $substring_length += 1;
        }
    }
    $numeric_string = filter_var($corrected_string, FILTER_SANITIZE_NUMBER_INT);
    $value_string = substr($numeric_string, 0, 1) . substr($numeric_string, -1, 1);
    $final_int = (int)$value_string;

    # add the two-digit values to the array
    $extracted_values[] = $final_int;
}

# close the input file
fclose($input_file);

# sum the values from the array
$sum_of_values = 0;
foreach($extracted_values as $value){
    $sum_of_values += $value;
}

# output the sum of the two-digit values
echo "The sum of the collected values is: $sum_of_values \n";
?>