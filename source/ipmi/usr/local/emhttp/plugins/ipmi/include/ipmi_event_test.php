<?php
/*lun netfn cmd dir evm stype num etype state data data
ipmi-raw 0 4 02 41 04 01 30 01 09 ff ff Temp UC hi
ipmi-raw 0 4 02 41 04 01 30 01 07 ff ff Temp UNC hi
ipmi-raw 0 4 02 41 04 02 60 01 02 ff ff Volt LC lo
ipmi-raw 0 4 02 41 04 0c 53 6f 00 ff ff Mem Correct Error*/

require_once '/usr/local/emhttp/plugins/ipmi/include/ipmi_helpers.php';

$array = [['01 30 01 09 ff ff', 'Temperature - Upper Critical - Going High'],
			 ['01 30 01 07 ff ff', 'Temperature - Upper Non Critical - Going High'],
			 ['02 60 01 00 ff ff', 'Voltage Threshold - Lower Non Critical - Going Low'],
			 ['02 60 01 02 ff ff', 'Voltage Threshold - Lower Critical - Going Low'],
			 ['0c 53 6f 00 ff ff', 'Memory - Correctable ECC']];
$key = rand(0, 4);
$cmd = "ipmi-raw 0 4 02 41 04 ".$array[$key][0]." $options > /dev/null 2>&1";
shell_exec($cmd);
echo json_encode($array[$key][1]);
?>