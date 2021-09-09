<?php

function validate_radio($radio_value) {
  return is_numeric($radio_value) && isset($radio_value);
}

function validate_y($y_coordinate) {
  $y_min = -3;
  $y_max = 3;

  if (!isset($y_coordinate))
    return false;

  $num_y = str_replace(',', '.', $y_coordinate);
  return is_numeric($num_y) && $num_y >= $y_min && $num_y <= $y_max;
}

function validate_form($x_choice, $y_coordinate, $r_choice) {
  //return validateX($x_choice) && validate_y($y_coordinate) && validateR($r_choice);
  return validate_radio($x_choice) && validate_radio($r_choice) && validate_y($y_coordinate);
}

function check_triangle($x_choice, $y_coordinate, $r_choice) {
  return $x_choice >= 0 && $y_coordinate <= 0 &&
    $y_coordinate >= $x_choice - $r_choice/2;
}

function check_squad($x_choice, $y_coordinate, $r_choice) {
  return $x_choice >= 0 && $y_coordinate >= 0 &&
    $x_choice <= $r_choice && $y_coordinate <= $r_choice;
}

function check_circle($x_choice, $y_coordinate, $r_choice) {
  return $x_choice <=0 && $y_coordinate <= 0 &&
    $x_choice*$x_choice + $y_coordinate*$y_coordinate <= $r_choice*$r_choice;
}

function check_hit($x_choice, $y_coordinate, $r_choice) {
  return check_triangle($x_choice, $y_coordinate, $r_choice) || check_squad($x_choice, $y_coordinate, $r_choice) ||
    check_circle($x_choice, $y_coordinate, $r_choice);
}

$x_choice = $_POST['x_choice'];
$y_coordinate = $_POST['y_coordinate'];
$r_choice = $_POST['r_choice'];

$timezone = $_POST['timezone'];

$validate = validate_form($x_choice, $y_coordinate, $r_choice);
$boolean_validate = $validate ? 'true' : 'false';
$hit = check_hit($x_choice, $y_coordinate, $r_choice);
$answer_hit = $hit ? 'hit' : 'miss';

$currentTime = date('H:i:s', time()-$timezone*60);
$executionTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5);

$data = '{' .
  "\"validate\":$boolean_validate," .
  "\"x_value\":\"$x_choice\"," .
  "\"y_value\":\"$y_coordinate\"," .
  "\"r_value\":\"$r_choice\"," .
  "\"current_time\":\"$currentTime\"," .
  "\"time_execute\":\"$executionTime\"," .
  "\"hit_result\":\"$answer_hit\"".
  "}";  

echo $data;
