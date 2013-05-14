<?php
require_once 'Project/Code/System/Settings/settings.php';


$my_email = "name@domain.com"; 

$continue = "/"; 

$errors = array(); 

// Remove $_COOKIE elements from $_REQUEST. 


// Attachment 

function email_attachment($my_email, $file_location, $default_filetype='application/zip') { 

$fileatt = $file_location; 
    if(function_exists(mime_content_type)){ 
        $fileatttype = mime_content_type($file_location);  
    }else{ 
        $fileatttype = $default_filetype;; 
    } 
    $fileattname =$_REQUEST['attachment']($file_location); 
    //prepare attachment 
    $file = fopen( $fileatt, 'rb' );  
    $data = fread( $file, filesize( $fileatt ) );  
    fclose( $file ); 
    $data = chunk_split( base64_encode( $data ) ); 
    //create mime boundary 
    $semi_rand = md5( time() );  
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

// Build message. 

function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");} 

$message = build_message($_REQUEST); 

$message = stripslashes($message); 

$message = "This is a multi-part message in MIME format.\n\n" .  
"--{$mime_boundary}\n" .  
"Content-type: text/html; charset=us-ascii\n" .  
"Content-Transfer-Encoding: 7bit\n\n" . 

//create attachment section 
$message .= "--{$mime_boundary}\n" .  
"Content-Type: {$fileatttype};\n" .  
" name=\"{$fileattname}\"\n" .  
"Content-Disposition: attachment;\n" .  
" filename=\"{$fileattname}\"\n" .  
"Content-Transfer-Encoding: base64\n\n" .  
$data . "\n\n" .  
"--{$mime_boundary}--\n"; 

$subject = "General Enquiry"; 

$subject = stripslashes($subject); 

$headers .= "Date: ".date("r")."\n"; 

$headers .= "MIME-Version: 1.0\n" .  
                "Content-Type: multipart/mixed;\n" .  
                    " boundary=\"{$mime_boundary}\""; 

$from_Name = ""; 

if(isset($_REQUEST['Name']) && !empty($_REQUEST['Name'])){$from_Name = stripslashes($_REQUEST['Name']);} 

$headers = "From: {$from_Name} <{$_REQUEST['Email']}>"; 

mail($my_email,$subject,$message,$headers); 

$message = "Thank you for your enquiry. We will be in touch with you as soon as possible. 

Regards, 

Company Name 
http://www.domain.com"; 

$subject = "General Enquiry"; 

$headers = "From: Company Name <noreply@domain.com>"; 

mail($_REQUEST['Email'],$subject,$message,$headers); } 

?>