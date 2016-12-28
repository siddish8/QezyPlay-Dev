<?php
// functions.php

function check_txnid($tnxid){
	
	global $dbcon;	
	
	$sql = "SELECT * FROM `payments2` WHERE txnid = '$tnxid'";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();			
		$result = $stmt->fetch(PDO::FETCH_ASSOC);		
		if(count($result) > 1){
			return false;
		}		

	}catch (PDOException $e){
		return $e->getMessage();
	}
	
	return true;	
}

function updatePayments($data){
	global $dbcon;
	
	if (is_array($data)) {		
		
		$created_datetime = gmdate("Y-m-d H:i:s");
		
		$sql = "INSERT INTO payments2(txnid,payment_amount,payment_status,itemid,createdtime) VALUES(?,?,?,?,?)";		
		try {
			$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute(array($data['txn_id'],$data['payment_amount'],$data['payment_status'],$data['item_number'],$created_datetime));				
			return $dbcon->lastInsertId(); 			
			
		}catch (PDOException $e){
			return $e->getMessage();
		}
	}
}

function generate_coupon()
{
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
	$length=6;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $cpn="QZ".$randomString;
if(confirm_coupon($cpn))
{
return $cpn;
}
else
{
generate_coupon();
}
}

function confirm_coupon($cpnI)
{
global $dbcon;

$sql = "SELECT * FROM `buy_a_friend` WHERE coupon_code = '$cpnI'";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute();			
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
		if(count($result) > 0){
			return false;
		}		

	}catch (PDOException $e){
		return $e->getMessage();
	}

return true;
}

