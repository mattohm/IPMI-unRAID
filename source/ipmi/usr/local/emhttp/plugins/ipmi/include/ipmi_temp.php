<?
require_once '/usr/local/emhttp/plugins/ipmi/include/ipmi_helpers.php';
require_once '/usr/local/emhttp/plugins/ipmi/include/ipmi_settings_display.php';

function format_ipmi_temp($reading, $unit, $dot) {
  return (($reading != 0) ? ($unit==='F' ? round(9/5*$reading+32) : str_replace('.',$dot,$reading))."</font><font><small>&deg;$unit</small>" : '##');
}

$disp_sensors = [$disp_sensor1, $disp_sensor2, $disp_sensor3, $disp_sensor4];

if (!empty($disp_sensors)){
    $readings = ipmi_sensors($ignore);
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

            if($readings[$disp_sensor]['Type'] === 'Temperature'){
                // if temperature is greater than upper non-critical show critical
                if ($disp_reading > $UpperNC && $UpperNC != 0)
                    $Color = 'orange';

                    // if temperature is greater than upper critical show non-recoverable
                if ($disp_reading > $UpperC && $UpperC != 0)
                    $Color = 'red';

                $displays[] = "<span title='$disp_name ($disp_id)'><i class='icon fa fa-thermometer'></i><font color='$Color'>".
                    format_ipmi_temp(floatval($disp_reading), htmlspecialchars($_GET['unit']), htmlspecialchars($_GET['dot'])).'</font></span>';
            }elseif($readings[$disp_sensor]['Type'] === 'Fan'){
                // if Fan RPMs are less than lower non-critical
                if ($disp_reading < $LowerNC || $disp_reading < $LowerC || $disp_reading < $LowerNR)
                    $Color = "red";

                $displays[] = "<span title='$disp_name ($disp_id)'><i class='icon fa fa-tachometer'></i><font color='$Color'>".
                    floatval($disp_reading)."</font><small>rpm</small></span>";
            }elseif($readings[$disp_sensor]['Type'] === 'Voltage'){
                // if Voltage is less than lower non-critical
                if ($disp_reading < $LowerNC || $disp_reading < $LowerC || $disp_reading < $LowerNR)
                    $Color = "red";
                if ($disp_reading > $UpperNC || $disp_reading > $UpperC || $disp_reading > $UpperNR)
                    $Color = "red";

                $displays[] = "<span title='$disp_name ($disp_id)'><i class='icon fa fa-bolt'></i><font color='$Color'>".
                    floatval($disp_reading)."</font><small>v</small></span>";
            }elseif($readings[$disp_sensor]['Type'] === 'OEM Reserved'){
                if($disp_reading === 'Medium')
                    $Color = 'orange';
                if($disp_reading === 'High')
                    $Color = 'Red';
                $displays[] = "<span title='$disp_name ($disp_id)'><i class='icon fa fa-thermometer'></i><font color='$Color'>$disp_reading</font></span>";
            }else{
                $displays[] = "<span title='$disp_name ($disp_id)'><i class='icon fa fa-tachometer'><font color='$Color'>$disp_reading</font></span>";
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
 $displays[] = "<span title='Borg'><font color='$bu_color'>$bu_date</font><small>RClone</small></span>";

}
if ($displays)
    echo "<span id='impitemps' style='margin-right:16px;font-weight: bold;cursor: pointer;'>".implode('&nbsp;', $displays)."</span>";
?>
