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
          <input class="btn btn-danger" type="submit" name="update_security" value="Update Security">
      </div>
</div></form></div>
