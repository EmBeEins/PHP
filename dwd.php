<?php
/* Unwetterwarnung für Sylt auslesen

$jsonp = file_get_contents("http://www.wettergefahren.de/DWD/warnungen/warnapp/warnings.json");

$json = extract_unit($jsonp, 'warnWetter.loadWarnings(', ');');


$alert_list = json_decode($json, true);

// Kreis Unna: 105978000

$relevant_alerts = $alert_list["warnings"]["901054002"]; 
$relevant_prealerts = $alert_list["vorabInformation"]["901054002"]; 

if($relevant_alerts == null) $relevant_alerts = array();
if($relevant_prealerts == null) $relevant_prealerts = array();

$relevant_alerts = array_merge($relevant_prealerts, $relevant_alerts);

$sortArray = array();
foreach($relevant_alerts as $key => $array) {
        $sortArray[$key] = $array['level'];
} 

array_multisort($sortArray, SORT_ASC, SORT_NUMERIC, $relevant_alerts);

$numberOfAlerts = count($relevant_alerts);

if($numberOfAlerts == 1) {
echo "<div class='list-group'><a href='#' class='list-group-item active'>Wetterwarnung</a>";

} elseif($numberOfAlerts > 0) {

echo "<div class='list-group'><a href='#' class='list-group-item active'>".count($relevant_alerts)." Warnungen</a>";

} else {
echo "<div class='list-group'><a href='#' class='list-group-item list-group-item-success'>Keine Wetterwarnung</a>";
}


$cnt = 0;
foreach($relevant_alerts as $alert) {
	$event = $alert['event'];
	$headline = $alert['headline'];
	$description = $alert['description'];
	$regionName = $alert['regionName'];
	$level = $alert['level'];
	$start = utf8_decode(date('d.m.Y H:i', substr($alert['start'], 0, 10)));
	$end = utf8_decode(date('d.m.Y H:i', substr($alert['end'], 0, 10)));
	$hilferuf ="$headline<br />($start bis $end)<hr>$description";
	echo "<a target='_blank' class='list-group-item list-group-item-danger' href='#'>$hilferuf</a>";
 //    echo "<a href='whatsapp://send?text=".$description."' data-action='share/whatsapp/share' class='list-group-item list-group-item-danger'>$hilferuf</a>";
	$cnt += 1;
}


function extract_unit($string, $start, $end) {
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); // remove whitespaces

	return $unit;
}
echo "</div>";
?>
