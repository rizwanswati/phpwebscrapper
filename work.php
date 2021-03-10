<?php
/**
 * Created by PhpStorm.
 * User: Rizwan
 * Date: 5/16/2019
 * Time: 2:51 AM
 */
include ("simple_html_dom.php");


$address    = $_POST['add'];
$text       = $_POST['txt'];

echo $address.'<br/>';


$preped_str = strtolower($text);
$preped_str = trim(preg_replace('/\s+/', ' ', $preped_str));
$needle_length = strlen($preped_str);

$chunk_length = $needle_length/5;
settype($chunk_length,"integer");


$chunked_array = str_split($preped_str,$chunk_length);


echo "<h3> chunked array </h3>";
echo '<pre>';
print_r($chunked_array);
echo '</pre>';
echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<br/>";



$rawText = file_get_html($address)->plaintext;
$prep_raw = strtolower($rawText);
$prep_raw = trim(preg_replace('/\s+/', ' ', $prep_raw));
echo "<h3> Scrapped Site </h3>";
echo $prep_raw;
echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<br/>";


$matchedsub = null;
$unmatched_chunk = null;
foreach ($chunked_array as $item)
{
    $matched = get_longest_common_subsequence($prep_raw,$item);
    if (!empty($matched))
    {
        $matchedsub = $matchedsub.$matched;
    }else{
        echo $item;
        $unmatched_chunk = $unmatched_chunk.$item;
    }
}



echo "<h3> Input String </h3>";
echo $preped_str;


echo "<h3> Matched data with Input data </h3>";
echo $matchedsub;
echo "<br/>";
echo "<br/>";
echo "<br/>";

similar_text($preped_str,$matchedsub,$percentage);
echo "input value matched : %$percentage";

echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<h3> unmatched part of the text </h3>";
echo $unmatched_chunk;

//$result = strpos($prep_raw,$preped_str);


//echo "The result of string match is = ".$result ."<br/>";
//
//if($result)
//{
//    $matched_part=null;
//    $index = $result;
//    $needle_length = strlen($preped_str);
//    for($i=0; $i<$needle_length; $i++)
//    {
//        if ($prep_raw[$index] == $preped_str[$i] ){
//            $matched_part = $matched_part.$preped_str[$i];
//        }
//        $index++;
//    }
//
//    echo "<br/>".$matched_part;
//} else{
//    echo "Input text does not match anything...!";
//}

//settype($result, "integer");
//$needle_length = strlen($preped_str);
//echo $needle_length."<br/>";
//$found_substr = substr($prep_raw,$result,$needle_length);
//
//echo $found_substr;

//echo $prep_raw;

//https://www.youtube.com/watch?v=j8QCNDuz0jw&list=PL9ac6isfrJo1pjw1B18D302uQhEtMWDUT&index=3

function show($data)
{
    echo '<pre>';
    print_r($data);
    exit();

}

function get_longest_common_subsequence($string_1, $string_2)
{
    $string_1_length = strlen($string_1);
    $string_2_length = strlen($string_2);
    $return          = '';

    if ($string_1_length === 0 || $string_2_length === 0)
    {
        // No similarities
        return $return;
    }

    $longest_common_subsequence = array();

    // Initialize the CSL array to assume there are no similarities
    $longest_common_subsequence = array_fill(0, $string_1_length, array_fill(0, $string_2_length, 0));

    $largest_size = 0;

    for ($i = 0; $i < $string_1_length; $i++)
    {
        for ($j = 0; $j < $string_2_length; $j++)
        {
            // Check every combination of characters
            if ($string_1[$i] === $string_2[$j])
            {
                // These are the same in both strings
                if ($i === 0 || $j === 0)
                {
                    // It's the first character, so it's clearly only 1 character long
                    $longest_common_subsequence[$i][$j] = 1;
                }
                else
                {
                    // It's one character longer than the string from the previous character
                    $longest_common_subsequence[$i][$j] = $longest_common_subsequence[$i - 1][$j - 1] + 1;
                }

                if ($longest_common_subsequence[$i][$j] > $largest_size)
                {
                    // Remember this as the largest
                    $largest_size = $longest_common_subsequence[$i][$j];
                    // Wipe any previous results
                    $return       = '';
                    // And then fall through to remember this new value
                }

                if ($longest_common_subsequence[$i][$j] === $largest_size)
                {
                    // Remember the largest string(s)
                    $return = substr($string_1, $i - $largest_size + 1, $largest_size);
                }
            }
            // Else, $CSL should be set to 0, which it was already initialized to
        }
    }

    // Return the list of matches
    return $return;
}