<?php

/*
Based on the prompt, we need to parse multi-line data, which includes breaks for different
types of data relationships. It seems the established pattern of reading line-by-line
then breaking data into arrays based on presence of space. We'll want to parse out the
input file and populate the arrays first. Then we'll do a second loop to trace the data
relationships to the end, storing the data in another array. Finally, we'll compare the
values in that array to choose the lowest result for the final result.
*/

# I'm not sure it's a memory leak at this point, though it may be
# Still, I need to increase available memory again
#ini_set("memory_limit", -1);
# define the script-wide variables and arrays
$seed_numbers = [];
$expanded_seed_numbers = [];
$seed_to_soil_maps = [];
$soil_to_fertilizer_maps = [];
$fertilizer_to_water_maps = [];
$water_to_light_maps = [];
$light_to_temperature_maps = [];
$temperature_to_humidity_maps = [];
$humidity_to_location_maps = [];
$current_category = "";
$current_operating_array = [];

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day05.txt", "r");
#$input_file = fopen("../inputs/sample_day05.txt", "r");

# cycle through the lines of the file to parse and populate initial arrays
while(!feof($input_file)){
    # get the raw string
    $raw_string = strtolower(trim(fgets($input_file)));
    # we need special handling for the seeds line; it's small enough it may as well be
    # hard-coded for now
    if(str_contains($raw_string,"seeds: ")){
        $seed_numbers = explode(" ", trim(explode(":", $raw_string)[1]));
        $current_category = "seeds";
    } else {
        if($raw_string == "") {
            if($current_category <> "seeds") {
                # clobber the appropriate empty placeholder array with the operating array
                switch ($current_category) {
                    case "seed-to-soil";
                        $seed_to_soil_maps = $current_operating_array;
                        break;
                    case "soil-to-fertilizer";
                        $soil_to_fertilizer_maps = $current_operating_array;
                        break;
                    case "fertilizer-to-water";
                        $fertilizer_to_water_maps = $current_operating_array;
                        break;
                    case "water-to-light";
                        $water_to_light_maps = $current_operating_array;
                        break;
                    case "light-to-temperature";
                        $light_to_temperature_maps = $current_operating_array;
                        break;
                    case "temperature-to-humidity";
                        $temperature_to_humidity_maps = $current_operating_array;
                        break;
                    default;
                        echo "No map match. Shouldn't have gotten here!";
                        exit;
                }
                # empty the operating array
                $current_operating_array = [];
            }
        }
        elseif(!is_numeric(substr($raw_string, 0, 1))){
            # we get here when we're dealing with a category change, so we need to change the category
            $current_category = explode(" ", $raw_string)[0];
        }
        else{
            # just needing to add this line to our operating array
            $current_operating_array[] = explode(" ", $raw_string);
        }
    }
}
# need to store the final set in its array (this happens because there's no
# empty line to trigger the assignment logic
$humidity_to_location_maps = $current_operating_array;
# might help clean up a little memory to empty current_operating_array
$current_operating_array = null;

# close the input file
fclose($input_file);

# in the name of not repeating myself, I've moved the foreach logic to a function
# begin iterating through the seed -> soil -> fertilizer -> water -> light -> temperature -> humidity -> location logic
$lowest_location_part1 = iterate_through_arrays($seed_numbers);

# part two logic simply requires changing the seed numbers starting point
# to use ranges rather than singular values; beyond that, the preceding works as is.

for($i = 0; $i < count($seed_numbers); $i+=2){
    $starting_number = $seed_numbers[$i];
    $working_range = $seed_numbers[$i + 1];
    for($j = 0; $j < $working_range; $j++){
        $expanded_seed_numbers[] = $starting_number + $j;
    }
}
$lowest_location_part2 = iterate_through_arrays($expanded_seed_numbers);

# output the results
echo "The output: \n";
echo "The seed with the lowest location number in part one is: " . $lowest_location_part1 . "\n";
echo "The seed with the lowest location number in part two is: " . $lowest_location_part2 . "\n";

# moving this to a function clears the need of nested foreach loops
function check_within_range_and_return_mapped_value($source_value, $array_of_arrays){
    $destination_value = $source_value;
    foreach($array_of_arrays as $this_array){
        $destination_start = $this_array[0];
        $source_start = $this_array[1];
        $this_range = $this_array[2];
        $destination_offset = $source_start - $destination_start;
        if($source_start <= $source_value && $source_value <= $source_start + $this_range){
            $destination_value = $source_value - $destination_offset;
            break;
        }
    }
    return $destination_value;
}

function iterate_through_arrays($seed_array){
    global $seed_to_soil_maps, $soil_to_fertilizer_maps, $fertilizer_to_water_maps;
    global $water_to_light_maps, $light_to_temperature_maps, $temperature_to_humidity_maps, $humidity_to_location_maps;
    $lowest_location = null;
    foreach($seed_array as $seed){
        $soil = check_within_range_and_return_mapped_value($seed, $seed_to_soil_maps);
        $fertilizer = check_within_range_and_return_mapped_value($soil, $soil_to_fertilizer_maps);
        $water = check_within_range_and_return_mapped_value($fertilizer, $fertilizer_to_water_maps);
        $light = check_within_range_and_return_mapped_value($water, $water_to_light_maps);
        $temperature = check_within_range_and_return_mapped_value($light, $light_to_temperature_maps);
        $humidity = check_within_range_and_return_mapped_value($temperature, $temperature_to_humidity_maps);
        $location = check_within_range_and_return_mapped_value($humidity, $humidity_to_location_maps);
        #echo("for seed #" . $seed . ", the location match would be: " .  $location . "\n");
        if($lowest_location == null || $location < $lowest_location){
            $lowest_location = $location;
        }
    }
    return $lowest_location;
}

?>