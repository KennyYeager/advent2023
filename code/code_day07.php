<?php

/*
Based on the prompt, we're going to need to do a lot of array manipulation.
There are probably simpler ways to handle the data as presented, but we need several
data points about the "hands" being dealt, including the original order, the calculated
"power" of the hand, the "bid" associated with the hand, and the rank.
A lot of this can be done with PHP's sorting... once the data is in meaningfully
sortable arrays.
*/

# open the input file and make it accessible
#$input_file = fopen("../inputs/input_day07.txt", "r");
$input_file = fopen("../inputs/sample_day07.txt", "r");

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