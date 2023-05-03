$config = include "config.php";
function antiBruteForce($location){
  global $connection;
  global $config;
  $query_attempts_timestamp = $config['antibrute_config']['query_attempts_timestamp'];
  $blocked_ip_timestamp = $config['antibrute_config']['blocked_ip_timestamp'];
  // Set the maximum number of query attempts per minute
  $max_attempts = $config['antibrute_config']['max_query_attempts'];
  // Get the IP address of the user
  $ip_address = $_SERVER['REMOTE_ADDR'];
  // Get the current timestamp
  $now = date("Y-m-d H:i:s");
  $timestamp = date("Y-m-d H:i:s", strtotime("$query_attempts_timestamp seconds"));
  // Check if there are too many query attempts from this IP address within the last minute
  $stmt = $connection->prepare("SELECT COUNT(*) AS count FROM query_attempts WHERE ip_address = ? AND timestamp > ?");
  $stmt->bind_param('ss', $ip_address, $timestamp);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
    if ($row['count'] >= $max_attempts) {
        // Too many query attempts within the last minute, block the IP address
        $stmt = $connection->prepare("INSERT INTO blocked_ips (ip_address, timestamp) VALUES (?, ?)");
        $stmt->bind_param('ss', $ip_address, $now);
        $stmt->execute();
        if(session_status() === PHP_SESSION_ACTIVE){
          session_destroy();
        }
        header('Location: '.$location);
        exit;
    }else{
      $stmt = $connection->prepare("INSERT INTO query_attempts (ip_address, timestamp) VALUES (?, ?)");
      $stmt->bind_param('ss', $ip_address, $now);
      $stmt->execute();
    }

    $timestamp = date("Y-m-d H:i:s", strtotime("$blocked_ip_timestamp seconds"));
    $stmt = $connection->prepare("SELECT COUNT(*) AS count FROM blocked_ips WHERE ip_address = ? AND timestamp > ?");
    $stmt->bind_param('ss', $ip_address, $timestamp ); // Only consider IPs blocked within the last hour
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
    // This IP address is blocked, redirect to an error page
        if(session_status() === PHP_SESSION_ACTIVE){
          session_destroy();
        }
        header('Location: '.$location);
        exit;
    }
}

function DeleteQueryAttempts (){
  global $connection;
  global $config;
  $delete_old_attempts = $config['antibrute_config']['delete_old_attempts'];
  $timestamp = date("Y-m-d H:i:s", strtotime("$delete_old_attempts seconds"));
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $stmt = $connection->prepare("DELETE FROM query_attempts WHERE ip_address = ? AND timestamp < ? ");
  $stmt->bind_param('ss', $ip_address, $timestamp);
  $stmt->execute();
}
