<?php

function substrwords($text, $maxchar, $end='...')
{
    if(strlen($text) == 0 )
    {
        return $text;
    }

    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            }
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    }
    else {
        $output = $text;
    }
    return $output;
}

function convertMileage($mileage)
{
    //input "5001 - 10000"
    //output "5,001 - 10,000"
    $result = "";
    $mileage_range = explode(' - ', $mileage);
    if( count($mileage_range) == 2 )
    {
        $startMileage = number_format($mileage_range[0], 0, '', ',');
        $endMileage = number_format($mileage_range[1], 0, '', ',');
        $result = $startMileage . " - ". $endMileage;
    }
    else
    {
        if( count($mileage_range) == 1 && $mileage_range[0] != 0 )
        {
            $startMileage = number_format($mileage_range[0], 0, '', ',');
            $result = $startMileage;
        }
        else
        {
            $result = "";
        }
    }

    return $result;
}

function convertColor($color)
{
    $result = "";

    $result = strtolower($color);
    $result = ucwords($result);

    return $result;
}