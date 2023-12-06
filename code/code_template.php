<?php

/*
Based on the prompt...
*/

# open the input file and make it accessible
$input_file = fopen("../inputs/input_day0.txt", "r");
$input_file = fopen("../inputs/sample_day0.txt", "r");

#declare global variables

# do stuff with the input file
while(!feof($input_file)) {
    # set up iteration-specific variables/arrays

    # get the raw line
    $raw_string = strtolower(fgets($input_file));

    # work through the line as needed
}

# close the input file
fclose($input_file);

# output the results
echo "The output: \n";
?>