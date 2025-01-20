<?
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");


?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="background-color: #fff;">
	<table border="1">
		<tr>
<?
			if($header !== null){
			$count = count($header);
			
			foreach ($header as $aheader)
				{
					echo "<th>$aheader</th>";				
				}
			}
?>
		</tr>
		<?
			//print_r($account_list);
			if($account_list !== null){
			if($account_list)
			{	
				foreach ($account_list as $row)
				{
					echo "<tr>";
					for($i=0;$i< $count;$i++){
						echo "<td  nowrap>".$row[$header[$i]]."</td>";
					}
					echo "</tr>";
				}
			}
		}
		?>
	</table>
</body>
</html>
