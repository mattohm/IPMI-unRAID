<?

$disp_sensors = [$disp_sensor1, $disp_sensor2, $disp_sensor3, $disp_sensor4];

if (!empty($disp_sensors)){
    $displays = [];
    foreach($disp_sensors as $disp_sensor){
        if (!empty($readings[$disp_sensor])){
            $disp_name    = $readings[$disp_sensor]['Name'];
            $disp_id      = $readings[$disp_sensor]['ID'];
            $disp_reading = ($readings[$disp_sensor]['Type'] === 'OEM Reserved') ? $readings[$disp_sensor]['Event'] : $readings[$disp_sensor]['Reading'];
            $LowerNR = floatval($readings[$disp_sensor]['LowerNR']);
            $LowerC  = floatval($readings[$disp_sensor]['LowerC']);
            $LowerNC = floatval($readings[$disp_sensor]['LowerNC']);
            $UpperNC = floatval($readings[$disp_sensor]['UpperNC']);
            $UpperC  = floatval($readings[$disp_sensor]['UpperC']);
            $UpperNR = floatval($readings[$disp_sensor]['UpperNR']);
            $Color = ($disp_reading === 'N/A') ? 'blue' : 'green';

        }
    }

}
 $bu_date = shell_exec("/usr/bin/stat  /mnt/user/scripts/backupstatus.txt | grep Modify | awk '{print $2}'");
 $bu_code = shell_exec("/usr/bin/cat  /mnt/user/scripts/backupstatus.txt");
 $bu_color = 'green';
 if ($bu_code != 0)
   $bu_color = 'red';
 $displays[] = "<span title='Borg'><font color='$bu_color'>$bu_date</font><small>Backup</small></span>";

 $rc_date = shell_exec("/usr/bin/stat  /mnt/user/scripts/rclonestatus.txt | grep Modify | awk '{print $2}'");
 $rc_code = shell_exec("/usr/bin/cat  /mnt/user/scripts/rclonestatus.txt");
 $rc_color = 'green';
 if ($rc_code != 0)
   $rc_color = 'red';
 $displays[] = "<span title='RClone'><font color='$rc_color'>$rc_date</font><small>RClone</small></span>";

if ($displays)
    echo "<span id='impitemps' style='margin-right:16px;font-weight: bold;cursor: pointer;'>".implode('&nbsp;', $displays)."</span>";
?>
