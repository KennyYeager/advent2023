<?php

/*
Based on the prompt, we need to parse multi-line data, which includes breaks for different
types of data relationships. It seems the established pattern of reading line-by-line
then breaking data into arrays based on presence of space. We'll want to parse out the
input file and populate the arrays first. Then we'll do a second loop to trace the data
relationships to the end, storing the data in another array. Finally, we'll compare the
values in that array to choose the lowest result for the final result.
*/

# define the script-wide variables and arrays
$seed_numbers = [];
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
#$input_file = fopen("../inputs/input_day05.txt", "r");
$input_file = fopen("../inputs/sample_day05.txt", "r");

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
                    default;
                        echo "No map match. Shouldn't have gotten here!";
                        break; #exit;
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
# close the input file
fclose($input_file);

# output the results
echo "The output: \n";
var_dump($seed_numbers);
var_dump($seed_to_soil_maps);
?>