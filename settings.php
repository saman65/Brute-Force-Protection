<?php
ob_start();
include "db.php"
include "functions.php"
DeleteQueryAttempts();

if(isset($_POST['update_security'])){
   // Perform validation and anti-brute force measures
   antiBruteForce("../error.php");
  // Update the users online timeout setting in the configuration array
  $max_query_attempts = (int) $_POST['max_query_attempts'];
  $config['antibrute_config']['max_query_attempts'] = $max_query_attempts;
  $query_attempts_timestamp = (int) $_POST['query_attempts_timestamp'];
  $config['antibrute_config']['query_attempts_timestamp'] = -$query_attempts_timestamp;
  $blocked_ip_timestamp = (int) $_POST['blocked_ip_timestamp'];
  $config['antibrute_config']['blocked_ip_timestamp'] = -$blocked_ip_timestamp;
  $delete_old_attempts = (int) $_POST['delete_old_attempts'];
  $config['antibrute_config']['delete_old_attempts'] = -$delete_old_attempts;

     if($max_query_attempts < 3){
        redirect("settings.php?message=error_max_query_attempts");
     }elseif($query_attempts_timestamp < 0){
        redirect("settings.php?message=error_query_attempts_timestamp");
     }elseif($blocked_ip_timestamp < 0){
        redirect("settings.php?message=error_blocked_ip_timestamp");
     }elseif($delete_old_attempts < 0){
        redirect("settings.php?message=error_delete_old_attempts");
     }else{
        // Write the updated configuration array back to config.php
        $config_file_contents  = "<?php\n\$config = ". var_export($config, true) . ";\n";
        $config_file_contents .= 'return $config;';
        file_put_contents('config.php', $config_file_contents);
        redirect("settings.php?message=brute_success");

     }
  }else{
     echo '<script type="text/javascript">';
     echo 'alert("All Fields required!");'; 
     echo '</script>';
     $message = "";
  }
}elseif(isset($_GET['message']) && $_GET['message'] == 'brute_success'){
  echo "<div class='alert alert-danger'>Security Updated</div>";
  $message = "";
}elseif(isset($_GET['message']) && $_GET['message'] == 'error_max_query_attempts'){
  $message = "Maximum Query Attempts should be equal or more than 3 (Risk of getting blocked at low values). The default value is 15 (Secure)";
}elseif(isset($_GET['message']) && $_GET['message'] == 'error_query_attempts_timestamp'){
  $message = "Query Attempts Timestamp should be equal or more than 0 second (0 is not secure). The default value is 60 seconds (Secure)";
}elseif(isset($_GET['message']) && $_GET['message'] == 'error_blocked_ip_timestamp'){
  $message = "Blocked IP Timestamp should be equal or more than 0 second (0 is not secure). The default value is 3600 seconds (Secure)";
}elseif(isset($_GET['message']) && $_GET['message'] == 'error_delete_old_attempts'){
  $message = "Delete-Old-Attempts should be equal or more than 0 second (0 is not secure). The default value is 302400 seconds (Secure)";
}

 if (isset($message)){ echo "<div class='text-center bg-success' style='color: red; height: 40px text-align: center'><h5>{$message}</h5></div><br>";
}else{
   echo "<div class='text-center bg-success' style='color: orange; height: 30px; padding: 0'><h3 class='modal-title'>Cahange Settings carefully</h3></div><br>";
}

?>

<div class='col-lg-6 col-md-6'><form action="" method="post" enctype="multipart/form-data" onSubmit="return confirm('Change security settings carefully!');" ?>
               <h3 class="modal-title">
            Security
            </h3>
   <div class='well'>
      <div class="form-group">
         <label for="Maximum Query Attempts">Maximum Query Attempts&nbsp;&nbsp;&nbsp;<small style='color: #777'>Default:30 attempts per 60 seconds</small></label>
          <input value="<?php echo $max_query_attempts; ?>" type="number" class="form-control" name="max_query_attempts" required>
      </div>

      <div class="form-group">
         <label for="Query Attempts Timestamp">Query Attempts Timestamp&nbsp;&nbsp;&nbsp;<small style='color: #777'>Default: 60 seconds for 30 attempts</small></label>
          <input value="<?php echo $query_attempts_timestamp*(-1); ?>" type="number" class="form-control" name="query_attempts_timestamp" required>
      </div>

      <div class="form-group">
         <label for="Blocked IP Timestamp">Blocked IP Timestamp&nbsp;&nbsp;&nbsp;<small style='color: #777'>Default: 3600 seconds</small></label>
          <input value="<?php echo $blocked_ip_timestamp*(-1); ?>" type="number" class="form-control" name="blocked_ip_timestamp" required>
      </div>
      
      <div class="form-group">
         <label for="Delete-Old-Attempts Timestamp">Delete-Old-Attempts Timestamp&nbsp;&nbsp;&nbsp;<small style='color: #777'>Default: 172800 seconds</small></label>
         <input value="<?php echo $delete_old_attempts*(-1); ?>" type="number" class="form-control" name="delete_old_attempts" required>
      </div>

      <div class="form-group">
          <input class="btn btn-danger" type="submit" name="update_security" value="Update Security">
      </div>
</div></form></div>
