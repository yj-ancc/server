<?php
include 'names.php';

header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$post_data = file_get_contents('php://input');
if ( !empty( $post_data ) ) {
  echo json_encode(file_put_contents(get_data_path().'/1.pdf', $post_data));
  // echo json_encode(0);
}

?>
