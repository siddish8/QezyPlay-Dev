<?php
/*
Copyright (c) 2011, The Pickling Jar Ltd <code@thepicklingjar.com>

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted, provided that the above
copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
*/

function wordpress_new_category($xmlrpcurl, $username, $password, $blogid = 0, $name, $slug = "", $parent_id = "", $description = "", $proxyipports = ""){
	global $globalerr;
	$client = new xmlrpc_client($xmlrpcurl);
    $client->setSSLVerifyPeer(false);
	$params[] = new xmlrpcval($blogid);
	$params[] = new xmlrpcval($username);
	$params[] = new xmlrpcval($password);

	$rpcstruct= new xmlrpcval(
		array(
			"name" => new xmlrpcval($name, "string"),
			"slug" => new xmlrpcval($slug, "string"),
			"parent_id" => new xmlrpcval($parent_id, "int"),
			"description" => new xmlrpcval($description, "string")
  		),
		"struct");
	$params[] = $rpcstruct;
	
	$msg = new xmlrpcmsg("wp.newCategory",$params);
        if(is_array($proxyipports)){
                $proxyipport = $proxyipports[array_rand($proxyipports)];
        }
        elseif($proxyipports != ""){
                $proxyipport = $proxyipports;
        }
        else {
                $proxyipport = "";
        }
        if($proxyipport != ""){
                if(preg_match("/@/", $proxyipport)){
                        $proxyparts = explode("@", $proxyipport);
                        $proxyauth = explode(":",$proxyparts[0]);
                        $proxyuser = $proxyauth[0];
                        $proxypass = $proxyauth[1];
                        $proxy = explode(":", $proxyparts[1]);
                        $proxyip = $proxy[0];
                        $proxyport = $proxy[1];
                        $client->setProxy($proxyip, $proxyport, $proxyuser, $proxypass);
                }
                else {
                        $proxy = explode(":",$proxyipport);
                        $proxyip = $proxy[0];
                        $proxyport = $proxy[1];
                        $client->setProxy($proxyip, $proxyport);
                }
        }

	$r = $client->send($msg);
	if($r === false){
                $globalerr = "XMLRPC ERROR - Could not send xmlrpc message";
		return(false);
	}
	if (!$r ->faultCode()) {
		return(php_xmlrpc_decode($r->value()));
	}
	else {
                $globalerr = "XMLRPC ERROR - Code: " . htmlspecialchars($r->faultCode()) . " Reason: '" . htmlspecialchars($r->faultString()). "'";
	}
	return(false);
}

function wordpress_create_categories($blogurl, $username, $password, $blogid, $categoryarray, $catseperator, $proxyipports = ""){
        $c = count($categoryarray);
        for($i = 0; $i < $c; $i++){
                $name = $categoryarray[$i]['name'];
                $slug = $categoryarray[$i]['slug'];
                $parent_id = $categoryarray[$i]['parent_id'];
                $description = $categoryarray[$i]['description'];
                $result = wordpress_new_category($blogurl, $username, $password, $blogid, $name, $slug, $parent_id, $description, $proxyipports);
                if($result == FALSE){
                        echo "new category '$name' failed\n";
                }
                else {
                        print_r($result);
                }
        }
}
?>