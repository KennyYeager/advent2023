<?php

/*
Based on the prompt, we're going to need to do a lot of array manipulation.
There are probably simpler ways to handle the data as presented, but we need several
data points about the "hands" being dealt, including the original order, the calculated
"power" of the hand, the "bid" associated with the hand, and the rank.
A lot of this can be done with PHP's sorting... once the data is in meaningfully
sortable arrays.
We need the raw data in an array simply to get it out of the input files.
We need a processed array that contains the raw hand, the bid, the power of the hand,
and possibly the rank. The rank may be better left to a sorting function than trying
to get the rank calculated every step of the way.
*/


#declare global variables
$input_array = []; # raw hand, raw bid
$hand_data = []; # hand number, bid value, power level, processed hand array, card 1, card 2, card 3, card 4, card 5
$ranking_data = []; # hand number, rank, bid value

# open the input file and make it accessible
#$input_file = fopen("../inputs/input_day07.txt", "r");
$input_file = fopen("../inputs/sample_day07.txt", "r");

# load the input into main array, do processing of data after file load
while(!feof($input_file)) {
    # get the raw line
    $raw_string = strtoupper(fgets($input_file));

    # work through the line as needed
    $raw_hand = (string)explode(" ", $raw_string)[0];
    $raw_bid = (int)explode(" ", $raw_string)[1];
    $input_array[] = array($raw_hand, $raw_bid);
}

# close the input file
fclose($input_file);

# loop through the input array to parse the power level of a given hand
for($i = 0; $i < count($input_array); $i++){
    $this_hand = $input_array[$i];
    $hand_as_string = $this_hand[0];
    $bid_value = $this_hand[1];
    $hand_as_string_array = [];
    foreach(str_split($hand_as_string) as $this_card){
        $hand_as_string_array[] = (string)$this_card;
    }
    $hand_as_converted_ints = convert_cards_to_numbers($hand_as_string_array);
    $power_level = calculate_power_of_hand($hand_as_string_array);
    $hand_data[] = array($i, $bid_value, $power_level, $hand_as_converted_ints, $hand_as_converted_ints[0],
        $hand_as_converted_ints[1], $hand_as_converted_ints[2], $hand_as_converted_ints[3], $hand_as_converted_ints[4]);
}

# we know have all the power levels, so now it's time to rank everything
# going to lean on array_multisort to get this started
$power_level_column = array_column($hand_data, 2);
#$hand_details_column = array_column($hand_data, 3);
$hand_details_card1 = array_column($hand_data, 4);
$hand_details_card2 = array_column($hand_data, 5);
$hand_details_card3 = array_column($hand_data, 6);
$hand_details_card4 = array_column($hand_data, 7);
$hand_details_card5 = array_column($hand_data, 8);
array_multisort($power_level_column, SORT_DESC, $hand_details_card1, SORT_DESC,
    $hand_details_card2, SORT_DESC, $hand_details_card3, SORT_DESC, $hand_details_card4, SORT_DESC,
    $hand_details_card5, SORT_DESC, $hand_data);
for($i = 0; $i < count($hand_data); $i++){
    $this_hand_number = $hand_data[$i][0];
    $this_power_level = $hand_data[$i][2];
    $this_bid = $hand_data[$i][1];
    $this_rank = count($hand_data) - $i;
    /* by just sorting by card from the start, the hand comparison is handled by the sort function
    if($i < count($hand_data) - 1){
        $next_hand_number = $hand_data[$i+1][0];
        $next_power_level = $hand_data[$i+1][1];
        $next_rank = $this_rank - 1;
        if($this_power_level == $next_power_level){
            $winning_hand = compare_hands_for_high_card($this_hand_number, $next_hand_number);
            if($winning_hand <> $this_hand_number){
                $ranking_data[] = array($next_hand_number, $this_rank);
                $ranking_data[] = array($this_hand_number, $next_rank);
                $i++;
                continue;
            }
        }
    }*/
    $ranking_data[]  = array($this_hand_number, $this_rank, $this_bid);
}
#var_dump($ranking_data);
$calculated_values = 0;
# finally total up the bids * ranks
for($i = 0; $i < count($ranking_data); $i++){
    $this_rank = $ranking_data[$i][1];
    $this_hand = $ranking_data[$i][0];
    $this_bid = $ranking_data[$i][2];
    $product_of_bid_and_rank = $this_rank * $this_bid;
    $calculated_values += $product_of_bid_and_rank;
    #echo("\nHand #" . $this_hand . " is ranked #". $this_rank . " and has a bid of " . $this_bid . " for a winning value of " . $product_of_bid_and_rank . "\n");
}



# output the results
echo "The output: \n";
echo "The calculated values for bids and ranks is: " . $calculated_values . "\n";

function convert_cards_to_numbers($hand_array): array {
    $converted_hand = [];
    foreach($hand_array as $this_card){
        $card_value = 0;
        if($this_card == "A"){
            $card_value = 14;
        } elseif($this_card == "K"){
            $card_value = 13;
        } elseif($this_card == "Q"){
            $card_value = 12;
        } elseif($this_card == "J"){
            $card_value = 11;
        } elseif($this_card == "T"){
            $card_value = 10;
        } else {
            $card_value = (int)$this_card;
        }
        $converted_hand[] = $card_value;
    }
    return $converted_hand;
}

function calculate_power_of_hand($hand_array): int{
    $hand_power = 0;
    $unique_cards = []; # card value, quantity of that card in hand
    foreach($hand_array as $this_card){
        if(count($unique_cards) == 0){
            $unique_cards[] = array($this_card, 1);
        } else {
            $needs_to_be_added = true;
            for($i = 0; $i < count($unique_cards); $i++){
                if($unique_cards[$i][0] == $this_card){
                    $new_total = $unique_cards[$i][1] + 1;
                    $unique_cards[$i][1] = $new_total;
                    $needs_to_be_added = false;
                    break;
                }
            }
            if($needs_to_be_added){
                $unique_cards[] = array($this_card, 1);
            }
        }
    }
    $quantity_column = array_column($unique_cards, 1);
    array_multisort($quantity_column,SORT_DESC, $unique_cards);
    for($j = 0; $j < count($unique_cards); $j++){
        $card_type = $unique_cards[$j];
        $held_copies = $card_type[1];
        if($held_copies == 5){
            $hand_power = 7;
        } elseif($held_copies == 4){
            $hand_power = 6;
        } elseif($held_copies == 3){
            if($unique_cards[$j+1][1] == 2){
                $hand_power = 5;
            } else {
                $hand_power = 4;
            }
        } elseif($held_copies == 2){
            if($j == 2 && ( $unique_cards[0][1] == 2 || $unique_cards[1][1] == 2) ){
                $hand_power = 3;
            } else {
                $hand_power = 2;
            }
        } else {
            if($hand_power == 0){$hand_power = 1;}
        }
    }
    return $hand_power;
}

function compare_hands_for_high_card($hand_number1, $hand_number2): int {
    global $hand_data;
    $winning_hand = 0;
    $hand1 = $hand_data[$hand_number1][2];
    $hand2 = $hand_data[$hand_number2][2];
    for($i = 0; $i < 5; $i++){ # hard coding the 5 since hand size is fixed
        if($hand1[$i] == $hand2[$i]){ continue; }
        else {
            echo "comparing $hand1[$i] > $hand2[$i]\n\n";
            $winning_hand = ($hand1[$i] > $hand2[$i] ? $hand_number1 : $hand_number2);
            break;
        }
    }
    echo "comparing hand $hand_number1 and $hand_number2; $winning_hand is the winner!";
    sleep(3);
    return $winning_hand;
}
?>