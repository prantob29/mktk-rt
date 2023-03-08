<?php

// Define SNMP community string and router IP address
$community = 'public';
$router_ip = '192.168.1.1';

// Define SNMP OIDs for download and upload bandwidth on ether1 interface
$download_oid = '1.3.6.1.2.1.2.2.1.10.2'; // OID for inbound traffic on ether1
$upload_oid = '1.3.6.1.2.1.2.2.1.16.2'; // OID for outbound traffic on ether1

// Get the initial download and upload bandwidth values
$snmp_download = snmpget($router_ip, $community, $download_oid);
$snmp_upload = snmpget($router_ip, $community, $upload_oid);
$download_start = (int) substr($snmp_download, strpos($snmp_download, ':') + 1);
$upload_start = (int) substr($snmp_upload, strpos($snmp_upload, ':') + 1);

// Wait for 1 second to get new bandwidth values
sleep(1);

// Get the new download and upload bandwidth values
$snmp_download = snmpget($router_ip, $community, $download_oid);
$snmp_upload = snmpget($router_ip, $community, $upload_oid);
$download_end = (int) substr($snmp_download, strpos($snmp_download, ':') + 1);
$upload_end = (int) substr($snmp_upload, strpos($snmp_upload, ':') + 1);

// Calculate the download and upload bandwidth in Kbps
$download_kbps = ($download_end - $download_start) * 8 / 1024;
$upload_kbps = ($upload_end - $upload_start) * 8 / 1024;

// Convert to Mbps if greater than or equal to 1000Kbps
if ($download_kbps >= 1000) {
    $download_speed = round($download_kbps / 1000, 2) . " Mbps";
} else {
    $download_speed = round($download_kbps, 2) . " Kbps";
}
if ($upload_kbps >= 1000) {
    $upload_speed = round($upload_kbps / 1000, 2) . " Mbps";
} else {
    $upload_speed = round($upload_kbps, 2) . " Kbps";
}

// Display the download and upload bandwidth
echo "Download: " . $download_speed . "<br>";
echo "Upload: " . $upload_speed;

?>
