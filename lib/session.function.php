<?php 
function get_session_number(){
	return count(scandir(session_save_path())) - 2 ;
}
	