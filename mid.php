<?php


    $url = 'https://cp4.njit.edu/cp/home/login';
    $db = 'https://web.njit.edu/~hy276/back.php';
    
    $to_front = array();
    
    $getdata = file_get_contents('php://input');
    $data = json_decode($getdata, true);
    
    $data_obj = json_encode($data, true);
    
    //init curl to database
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	 CURLOPT_URL => $db,
    	 CURLOPT_POST => 1,
    	 CURLOPT_FOLLOWLOCATION => 1,
    	 CURLOPT_RETURNTRANSFER => 1,
    	 CURLOPT_POSTFIELDS => $data_obj
  	));
 
    
    $response = curl_exec($curl);
    $data_res = json_decode($response, true);
    curl_close($ch);
    
    
    
    $to_front += $data_res;
    
    
    
    if($data_res['msg'] == 'Valid Login!'){
        $to_front += ['db' => 'VALID'];
        #print_r($data_res);
        
    }
    
    else if($data_res['msg'] == 'Invalid Login!'){
        $to_front += ['db' => 'INVALID'];
    }
    else {
        
        echo "Something didn't work right\n";
    }
    
    # Create new session for logging into NJIT
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'user='.$data['Username'].'&pass='.$data['Password'].'&uuid=0xACA021');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if(strpos($response,"Failed Login") != FALSE){
        $to_front += ['njit' => 'INVALID'];
    }
    else if(strpos($response, "Login Successful") != FALSE){
        $to_front += ['njit' => 'VALID'];
    }
    else
    	echo "njit failed to conn";
      
    echo json_encode($to_front, true);
  
