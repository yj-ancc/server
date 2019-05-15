<?php

include 'names.php';

$post_data = file_get_contents('php://input');
if ( !empty( $post_data ) ) {
  echo json_encode(file_put_contents(get_data_path().'/1.pdf', $post_data));
  // echo json_encode(0);
}

?>
