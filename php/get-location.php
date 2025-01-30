<?php
function getGeoLocation($ip) {
  $url = "http://ip-api.com/json/{$ip}";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);
}
?>
