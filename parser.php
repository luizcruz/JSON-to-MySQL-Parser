<?php

## Parse larger JSON Files
## Author: Luiz Cruz
## Date: 10/2017

require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ERROR | E_PARSE);

## General config
$debug = false;
$limit_parsed_itens = 10000;

## Clean output SQL file
$sql = fopen("output.sql", "a+") or die("Unable to open file!");
@ftruncate($sql, 0);
fclose($sql);




## Parse large JSON file
$handle = fopen("data.json", "r");
if ($handle) {


    while (($line = fgets($handle)) !== false) {
        $json = json_decode("[".$line."]",true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
               //  echo '[JSON] No errors';
            break;
            case JSON_ERROR_DEPTH:
            echo '[JSON] Maximum stack depth exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
            echo '[JSON] Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR:
            echo '[JSON] Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
            echo '[JSON] Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
            echo '[JSON] Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
            default:
            echo '[JSON] Unknown error';
            break;
        }

            ## Debug input JSON if needed
            if (true === $debug){

                echo "<pre>";
                print_r($json);
                echo "</pre>";

            }


            $sql = fopen("output.sql", "a+") or die("Unable to open file!");

            $txt = 'INSERT INTO Dumper (input1,input2,input3)
            VALUES (\''.($json[0][item1]).'\',\''.$json[0][item2].'\',\''.$json[0][item2].'\');'.PHP_EOL;

            fwrite($sql, $txt);
            fclose($sql);


        $limit_parsed_itens--;
        if(0 === $limit_parsed_itens){
            die();
        }
        

    }

    fclose($handle);
} else {
    echo "Error opening the file";
} 




?>