<?php
session_start();

if(!isset($_SESSION["active"]) || isset($_GET['reset'])) {
	reset_session();
}
$current_player = save_move_change_player();
$status = check_game();
?><!DOCTYPE html>
<html>
<head>
	<title>Tic-tac-toe</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<h1>Tic-Tac-Toe</h1>
<form accept="" method="get">
<?php build_ttt_table();?>
</form>
<?php if($status) { echo "<div class='notice'>".$status." <a href='?reset'>New Game?</a></div>"; }; ?> 
<?php //code_dump('$_GET',$_GET);?>
<?php //code_dump('$_SESSION',$_SESSION);?>
</body>
</html>
<?php
function build_ttt_table(){
	global $current_player;
	echo "<table>\n";
	for ($row=1; $row < 4; $row++) { 
		echo "\t<tr>\n";
		for ($col=1; $col < 4; $col++) {
			echo "\t\t<td>\n";
			if($player = cell_has_value($row,$col)) {
				echo "\t\t\t<div class='cell'><span>" . $player . "</span></div>\n";
			} else {
				echo "\t\t\t<div class='cell'><input type='submit' name='r" . $row . "_c" . $col . "' value='" . $current_player . "'></div>\n";
			}
			echo "\t\t</td>\n";
		}
		echo "\t</tr>\n";
	}
	echo "</table>\n";
}

function save_move_change_player(){
	if(count($_GET) > 0 ){
		foreach ($_GET as $location => $player) {
			if(preg_match("/r(\d)_c(\d)/", $location, $cords)) {
				$_SESSION["active"][$cords[1]][$cords[2]] = $player;
				if ($player == 'X') {
					return 'O';
				}elseif ($player == 'O') {
					return 'X';
				}
			}
		}
	}
	return 'X';
}

function cell_has_value($row,$col){
	return $_SESSION["active"][$row][$col];
}

function reset_session() {
	$_SESSION["active"] = array(
		'1' => array(
			'1' => false,
			'2' => false,
			'3' => false
		),
		'2' => array(
			'1' => false,
			'2' => false,
			'3' => false
		),
		'3' => array(
			'1' => false,
			'2' => false,
			'3' => false
		)
	);
};

function check_game() {
	for ($row=1; $row < 4; $row++) { 
		if ($_SESSION["active"][$row][1] 
			&& $_SESSION["active"][$row][1] == $_SESSION["active"][$row][2] 
			&& $_SESSION["active"][$row][2] == $_SESSION["active"][$row][3]) {
			return "Player " . $_SESSION["active"][$row][1] . " Won";
		}
	}
	for ($column=1; $column < 4; $column++) { 
		if ($_SESSION["active"][1][$column] 
			&& $_SESSION["active"][1][$column] == $_SESSION["active"][2][$column] 
			&& $_SESSION["active"][2][$column] == $_SESSION["active"][3][$column]) {
			return "Player " . $_SESSION["active"][$row][1] . " Won";
		}
	}
		
	//diagonal 1
	if ($_SESSION["active"][1][1] 
		&& $_SESSION["active"][1][1] == $_SESSION["active"][2][2] 
		&& $_SESSION["active"][2][2] == $_SESSION["active"][3][3]){
		return "Player " . $_SESSION["active"][1][1] . " Won";
	}
		
	//diagonal 2
	if ($_SESSION["active"][1][3] 
		&& $_SESSION["active"][1][3] == $_SESSION["active"][2][2] 
		&& $_SESSION["active"][2][2] == $_SESSION["active"][3][1]){
		return "Player " . $_SESSION["active"][1][3] . " Won";
	}
	
	foreach ($_SESSION["active"] as $row) {
		foreach ($row as $col) {
			if(!$col) {
				return false;
			}
		}
	}
	return "Players Tied";
}

// -------------------------------

function code_dump($title,$code) {
	echo '<br/><hr/><br/><h2>'.$title.'</h2><pre style="background-color:#f9f9f9;border:1px solid #ccc;border-bottom:3px solid #c9c9c9;padding:5px 10px;border-radius:5px;width:50%;margin:auto;text-align:left;">';
	var_dump($code);
	echo '</pre>';
}