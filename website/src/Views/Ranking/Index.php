<h1>Ranking</h1>

<table>
	<tr>
		<th>Rank</th>
		<th>Name</th>
		<th>Points</th>
	</tr>
	<?php 
	foreach ($users as $key => $user){
	?>
		<tr>
			<td><?=$key + 1?></td>
			<td><?=htmlentities($user["username"])?></td>
			<td><?=$user["points"]?></td>
		</tr>
	<?php 
	}
	?>
</table>