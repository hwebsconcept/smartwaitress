<?php include ("connect.php")?>
<?php
$get_pending =mysql_query("SELECT DISTINCT(table_number),time, MAX(delivery_time) AS remains FROM order_details WHERE STATUS = 0 GROUP BY ticket_id");
?>
<table class="table table-striped">
	<tr class="warning">
		<th>Table Number</th>
		<th class="text-right">Duration Left</th>
	</tr>
<?php    			
while($pending= mysql_fetch_array($get_pending))		
{
$starttime = date("Y-m-d H:i:s",strtotime($pending['time']) );
$add_min = date("Y-m-d H:i:s", strtotime($pending['time'] . "+".$pending['remains']." minutes"));
/*$interval  = abs($add_min - $starttime);
$minutes   = round($interval / 60);*/

date_default_timezone_set('Africa/Lagos');
$current = date('Y-m-d H:i:s', time());
$dteStart   = new DateTime($current);
$dteEnd   = new DateTime($add_min); 
$dteDiff  = $dteStart->diff($dteEnd); 

$elapsed = $dteDiff->format("%I");
?>
	<tr>
		<td><?php echo $pending['table_number'] ?></td>
		<?php
		if ($dteEnd > $dteStart)
		{
		?>
		<td class="text-right"> <?php echo $elapsed ?> mins Left</td>
		<?php
		}
		else
		{
		?>
		<td class="text-right"> Time Up</td>
		<?php	
		}
		?>
	</tr>
<?php
}
?>	
</table>
