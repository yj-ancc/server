<?php

include 'names.php';

header('Access-Control-Allow-Origin: '.get_server_det());
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');




$post_data = file_get_contents("php://input");
// Decoding the json data to retrieve based on objects
$request = json_decode($post_data, true);

$ref_num = $request['ref_num'];
$list_folders = $request['list_folders'];
$vevo_enabled = $request['vevo_enabled'];
$data_path = get_invoice_file_path();

/* Specific folder creation procedure */
function mkdir_folders($final_path) {
  if (mkdir($final_path, 0777, true)) {
    /* create directory success */
    return 1;
  } else {
      /* create directory failure */
      return 0;
  }
}

function check_status_arr($status_arr) {
  for($i = 0; $i < count($status_arr); $i++) {
    if(!$status_arr[$i]) return 0;
  }
  return 1;
}
/* function to create all the smartid2 folders */
function create_smartid2_folders($smart_id_path, $list_folders, $vevo_enabled, $status_arr) {
  /* Counting the length of the list folder */
  $count_list_folder = count($list_folders);
  for ($i = 0; $i < $count_list_folder; $i++) {
    if (!file_exists($smart_id_path.'/'.$list_folders[$i])) {
      $final_path = $smart_id_path.'/'.$list_folders[$i];
      if (!mkdir_folders($final_path)) {
        return $list_folders[$i].'-create-failure';
      } else {
          $status_arr[$i] = 1;
      }
    } else {
       /* Status array for mapping the information with the list of folders */
       $status_arr[$i] = 1;
    }
  }
  if ($status_arr  && check_status_arr($status_arr)) {
    return 'success';
  } else {
      return 'failure';
  }
}

$list_folders_count = count($list_folders);
$list_folders_creation_status = array();

/* Maintaining the state of the creation status folder for the list of folders */
for ($i = 0; $i < $list_folders_count ; $i++) {
  $list_folders_creation_status[] = 0;
}

if (file_exists($data_path.'/'.$ref_num)) {
  /* Reference number exists within the data folder */
  $ref_num_path = $data_path.'/'.$ref_num;
  if (!file_exists($ref_num_path.'/smartid2')) {
    /* Create the smartid2 folder for the first time */
    $smart_id_path = $ref_num_path.'/smartid2';
    if (mkdir($smart_id_path, 0777, true)) {
      echo json_encode(create_smartid2_folders($smart_id_path, $list_folders, $vevo_enabled, $list_folders_creation_status));
    } else {
        echo json_encode('smartid2-create-fail');
    }
  } else {
      /* SmartID2 folder exists already */
      $smart_id_path = $ref_num_path.'/smartid2';
      echo json_encode(create_smartid2_folders($smart_id_path, $list_folders, $vevo_enabled, $list_folders_creation_status));
  }
} else {
    /* Not a valid entry. Dont create anything */
    echo json_encode('NV');

}

?>
