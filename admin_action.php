<?php 
require_once "connect.php";
require_once "ip.php";

session_start();
$user_id = $_SESSION['user_id'];
$request = 0;
if(isset($_POST['upgrade_P1'])){
	$cash = mysqli_fetch_row(mysqli_query($link, "SELECT Cash FROM users WHERE ID='{$user_id}'"));
	$pastP1P = mysqli_fetch_row(mysqli_query($link, "SELECT P1P FROM users WHERE ID='{$user_id}'"));
	$newcash = $cash[0];
	if ($newcash> 175){
		$newcash = $cash[0] - 100 - $cash[0]*0.25;
		$newP1P = $pastP1P[0] + 1;
		$request = mysqli_query($link, "UPDATE users SET Cash='{$newcash}', P1P='{$newP1P}' WHERE ID='{$user_id}'");
	}
}

if(isset($_POST['upgrade_P3'])){
	$cash = mysqli_fetch_row(mysqli_query($link, "SELECT Cash FROM users WHERE ID='{$user_id}'"));
	$pastP3P = mysqli_fetch_row(mysqli_query($link, "SELECT P3P FROM users WHERE ID='{$user_id}'"));
	$newcash = $cash[0];
	if ($newcash> 175){
		$newcash = $cash[0] - 100 - 0.25*$cash[0];
		$newP3P = $pastP3P[0] + 1;
		$request = mysqli_query($link, "UPDATE users SET Cash='{$newcash}', P3P='{$newP3P}' WHERE ID='{$user_id}'");
	}
}

if(isset($_POST['work_economy'])){
	$cash = mysqli_fetch_row(mysqli_query($link, "SELECT Cash FROM users WHERE ID='{$user_id}'"));
	$P1P = mysqli_fetch_row(mysqli_query($link, "SELECT P1P FROM users WHERE ID='{$user_id}'"));
	$newcash = $cash[0]*(1+$R1*0.05+ $P1P[0]*0.01);
	$request = mysqli_query($link, "UPDATE users SET Cash='{$newcash}' WHERE ID='{$user_id}'");
}

if(isset($_POST['work_leg'])){
	$cash = mysqli_fetch_row(mysqli_query($link, "SELECT Cash FROM users WHERE ID='{$user_id}'"));
	$pastP2 = mysqli_fetch_row(mysqli_query($link, "SELECT P2 FROM users WHERE ID='{$user_id}'"));
	$newP2 = $pastP2[0] + 50;
	$request = mysqli_query($link, "UPDATE users SET P2='{$newP2}'  WHERE ID='{$user_id}'");
}

if(isset($_POST['work_vpk'])){
	$P3 = mysqli_fetch_row(mysqli_query($link, "SELECT P3 FROM users WHERE ID='{$user_id}'"));
	$R3 = mysqli_fetch_row(mysqli_query($link, "SELECT R3 FROM users WHERE ID='{$user_id}'"));
	$P3P = mysqli_fetch_row(mysqli_query($link, "SELECT P3P FROM users WHERE ID='{$user_id}'"));
	$newP3 = $P3[0]*(1+$R3[0]*0.05+$P3P[0]*0.01);
	$request = mysqli_query($link, "UPDATE users SET P3='{$newP3}' WHERE ID='{$user_id}'");
}
if(isset($_POST['move'])){
	$request = mysqli_query($link, "UPDATE users SET Move = 1 WHERE ID<100");
	$sales = mysqli_query($link, "SELECT * FROM coop WHERE EndMove={$move_number}");
	while ($row=mysqli_fetch_row($sales)){
		$saler = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM users WHERE id='{$row[0]}'"));	
		$buyer = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM users WHERE id='{$row[1]}'"));
		$stuffID = $row[2];
		if ($stuffID==1){
			$new_saler_R1 = $saler[3]+1;
			$new_saler_P1P = $saler[7]-3;
			$new_saler_P3P = $saler[8]-3;
			$req1= mysqli_query($link, "UPDATE users SET R1={$new_saler_R1}, P1P={$new_saler_P1P}, P3P={$new_saler_P3P} WHERE ID={$saler[0]}");
			$new_buyer_R1 = $buyer[3]-1;
			$req2 = mysqli_query($link, "UPDATE users SET R1={$new_buyer_R1} WHERE ID={$buyer[0]}");
		}
		if ($stuffID==3){
			$new_saler_R3 = $saler[4]+1;
			$new_saler_P1P = $saler[7]-3;
			$new_saler_P3P = $saler[8]-3;
			$req1= mysqli_query($link, "UPDATE users SET R3={$new_saler_R3}, P1P={$new_saler_P1P}, P3P={$new_saler_P3P} WHERE ID={$saler[0]}");
			$new_buyer_R3 = $buyer[4]-1;
			$req2 = mysqli_query($link, "UPDATE users SET R3={$new_buyer_R3} WHERE ID={$buyer[0]}");
		}
	}

	$del = mysqli_query($link, "DELETE FROM coop WHERE EndMove={$move_number}");
	$passiv = mysqli_query($link, "SELECT * FROM users WHERE ID < 100");
	while($row=mysqli_fetch_row($passiv)){
		$newcash = $row[2]*(1+$row[3]*0.05+$row[7]*0.01);
		$newP2 = $row[5]-20;
		if ($newP2<0){$newP2=0;}
		if ($newP2<50){$newcash = 0.8*$newcash;}

		$request1 = mysqli_query($link, "UPDATE users SET Cash={$newcash}, P2={$newP2}, Move=1 WHERE ID={$row[0]}");
	}
	$request=$req1 && $req2 && $passiv && $del && $request1;
}

if (isset($_POST['point_cost'])){
	$saler = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM users WHERE id='{$_SESSION['prev_user_id']}'"));
	$buyer = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM users WHERE id='{$_SESSION['user_id']}'"));
	
	if ($buyer[2] > $_POST['point_cost']){
		if (isset($_POST['oil'])){
			if ($saler[3] > 0){
				if ($buyer[3]<5){
					$newR1_1 = $saler[3]-1;
					$newR1_2 = $buyer[3]+1; 
					$newP1P = $saler[7] + 3;
					$newP3P = $saler[8] + 3;
					$salereq = mysqli_query($link, "INSERT INTO coop (Saler,Buyer,stuffID, EndMove) VALUES ({$_SESSION['prev_user_id']},{$_SESSION['user_id']},1,($move_number+4))");
					$request1 = mysqli_query($link, "UPDATE users SET R1 = {$newR1_1}, P1P = {$newP1P}, P3P = {$newP3P}, Move = 0 WHERE ID={$_SESSION['prev_user_id']}");
					$request2 = mysqli_query($link, "UPDATE users SET R1 = {$newR1_2}, Move = 0  WHERE ID={$_SESSION['user_id']}");
					$request = $request1 * $request2 * $salereq;	

				}
			}
			
		}
		else if (isset($_POST['metal'])){
			if ($saler[5] > 0){
				$newR3_1 = $saler[4]-1;
				$newR3_2 = $buyer[4]+1;
				$newP1P = $saler[7] + 3;
				$newP3P = $saler[8] + 3;
				$request1 = mysqli_query($link, "UPDATE users SET R3 = {$newR3_1}, P1P = {$newP1P}, P3P = {$newP3P}, Move = 0 WHERE ID={$_SESSION['prev_user_id']}");
				$request2 = mysqli_query($link, "UPDATE users SET R3 = {$newR3_2},Move = 0 WHERE ID={$_SESSION['user_id']}");
				$request = $request1 * $request2;
					 
			}
		}
	}
} 
if ($request){
	$mov = mysqli_query($link, "UPDATE users SET Move=0 WHERE ID={$_SESSION['user_id']}");
}
$_SESSION['last_request'] = $request;
header("Location: http://{$ip}/user.php?user_id={$user_id}");


?>