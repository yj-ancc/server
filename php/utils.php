<?php

function get_month_string($month) {
  $array_month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug', 'Sep', 'Oct', 'Nov', 'Dec');
  return $array_month[(int)$month - 1];
}

function get_data_format($date_val) {
  $final_date_part = explode('/', $date_val);

  $day = $final_date_part[0];
  $month = get_month_string($final_date_part[1]);
  $year = $final_date_part[2];
  $final_date = $day.' '.$month.' '.$year;
  return $final_date;
}

?>
