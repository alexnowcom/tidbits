<?php

/*
CREATE TABLE `profile_clients` (
  `idprofile` int(10) unsigned NOT NULL,
  `idclient` int(10) unsigned NOT NULL,
  `percent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idprofile`,`idclient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

class profile_clients extends Model {
	
	function updateProfile($id, $data, $detail = false){
		$id = intval($id);
		$prevdata = array();

		$sql = "-- profile_clients->updateProfile() get list
			SELECT idclient, percent
			FROM profile_clients
			WHERE idprofile = ?";
		$get = $this->db->query($sql, $id);
		foreach($get->result() as $row){
			$prevdata[$row->idclient] = $row->percent;
		}
		
		$process = differences($prevdata, $data);
		
		if($process->differences === 0){
			if(!$detail)
				return $process->differences; // 0
			else
				return $process;
		}
		
		$addupdate = $process->add + $process->update;

		foreach($addupdate as $idc=>$val){
		$sql = "-- profile_clients->updateProfile()
		INSERT INTO profile_clients (idprofile,idclient,percent) VALUES (?,?,?)
			 ON DUPLICATE KEY UPDATE percent= ?
			";

		$query = $this->db->query($sql, array($id, $idc, intval($val), intval($val)));

		}


		if(!empty($process->delete)){
			$sql = "-- profile_clients->updateProfile() delete subroutine
			DELETE FROM profile_clients
				WHERE idprofile = ? AND idclient IN (" . implode(', ',	array_keys($process->delete)) . ")
				";
			$delete = $this->db->query($sql, array($id));
		}

		if(!$detail)
			return $process->differences;
		else
			return $process;

	}
	
}
