<?php
class Sync_Data_Controller extends Controller
{
	function __construct() {
		parent::__construct();
	}

	function _sync() {
		$sync = new G_Sync_Data();
		if($sync->check_connection()) {
			$result = $sync->sync();
			if($result) {
				$return['message'] = "<div class='alert alert-success'><i class='icon icon-ok'></i> Successfully synced data.</div>";	
			}else{
				$return['message'] = "<div class='alert alert-info'><i class='icon icon-ok icon-info'></i> All data are already up-to-date.</div>";	
			}
		}else{
			$return['message'] = "<div class='alert alert-error'> Unable to connect to remote server.</div>";
		}
		echo json_encode($return);
		
	}

	function _ajax_load_confirmation() {
		$sync = new G_Sync_Data();
		if($sync->check_connection()) {
			$total_sync_data	= $sync->countSyncDataFromLive() + $sync->countSyncDataFromLocal();
			$seconds			= $total_sync_data * 4.5; // 4.5 = average second per insert,update,delete
			$has_connection 	= true;

			$at	= gmdate("H:i:s",$seconds);
			$approximate_time_arr = explode(":",$at);

			if($approximate_time_arr[0] > 0) {
				if($approximate_time_arr[0] == 1) {
					$approximate_time = $approximate_time_arr[0] ." hour ";
				}else{
					$approximate_time = $approximate_time_arr[0] ." hours ";
				}
			}

			if($approximate_time_arr[1] > 0) {
				if($approximate_time_arr[1] == 1) {
					$approximate_time .= $approximate_time_arr[1] ." minute ";
				}else{
					$approximate_time .= $approximate_time_arr[1] ." minutes ";
				}
			}

			if($approximate_time_arr[2] > 0) {
				if($approximate_time_arr[2] == 1) {
					$approximate_time .= $approximate_time_arr[2] ." second ";
				}else{
					$approximate_time .= $approximate_time_arr[2] ." seconds ";
				}
			}
		}else{
			$total_sync_data 	= 0;
			$has_connection 	= false;
		}

		$this->var['approximate_time']  = $approximate_time;
		$this->var['has_connection']  	= $has_connection;
		$this->var['total_sync_data'] 	= $total_sync_data;
		$this->view->render('sync_data/confirmation.php',$this->var);
	}

}
?>