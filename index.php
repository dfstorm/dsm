<?php
	header('Content-Type: application/json');
	class dsm {
	
		private	$datadir = "./data/";
		public	$uid_prefix = "dsm_uid_";
		public	$search_id = "uniqid";
		public	$get_param = "query";
		public	$iKey = 1;
		public	$arrKeys = array("abcd");
		public function delete($data, &$iFound)
		{
			$iFound = 1;
			$result = array();
			$result['icode'] = 1;
			if ($this->vtb($data, $r))
			{
				$r['json'] = json_decode($r['json'], true);
				if (isset($r['json'][$this->search_id]))
				{
					if (strlen($r['json'][$this->search_id]) > 0 && strlen($r['database']) > 0 && strlen($r['table']) > 0)
					{
						$d = array_diff(scandir($this->datadir.$r['database'].'/'.$r['table'].'/'.$r['json'][$this->search_id], 1), array('.', '..'));
						foreach ($d as $item => $val)
						{
							unlink($this->datadir.$r['database'].'/'.$r['table'].'/'.$r['json'][$this->search_id].'/'.$val);
						}
						if(rmdir($this->datadir.$r['database'].'/'.$r['table'].'/'.$r['json'][$this->search_id].'/'))
							$result['icode'] = 0;
					}
				}
			}
			echo json_encode($result);
		}
		
		public function update($data, &$iFound)
		{
			$iFound = 1;
			$result = array();
			if ($this->vtb($data, $r))
			{
				$r['json'] = json_decode($r['json'], true);
				if (!isset($r['json'][$this->search_id]))
				{
					$result['icode'] = 1;
					$result['message'] = "Error: no key used";
				}
				else
				{
					$m = explode(",", file_get_contents($this->datadir.$r['database'].'/'.$r['table'].'/meta'));
					foreach ($r['json'] as $kk => $vv)
						if (in_array($kk, $m))
							file_put_contents($this->datadir.$r['database'].'/'.$r['table'].'/'.$r['json'][$this->search_id].'/'.$kk, $vv);
					$result['icode'] = 0;
				}
			}
			echo json_encode($result);
		}
		
		public function select($data, &$iFound)
		{
			$iFound = 1;
			$iMode = 0;
			$itmp = 0;
			if($this->vtb($data, $r))
			{
				$d = array_diff(scandir($this->datadir.$r['database'].'/'.$r['table'], 1), array('.', '..', 'meta'));
				if ($r['json'] != '*')
				{
					$iMode = 1;
					$r['json'] = json_decode($r['json']);
				}
				
				$result = array();
				
				foreach ($d as $key => $value)
				{
					$result[$value] = array();
					$m = explode(",", file_get_contents($this->datadir.$r['database'].'/'.$r['table'].'/meta'));
					// TODO: Will create problem. fetch all then remove. Bad.
					foreach ($m as $skey => $svalue)
						$result[$value][$svalue] = file_get_contents($this->datadir.$r['database'].'/'.$r['table'].'/'.$value.'/'.$svalue);
					// Remove item that dont fit if mode allow it.
					foreach ($m as $skey => $svalue)
						if ($iMode == 1)
							foreach ($r['json'] as $kk => $vv)
								if ($kk == $svalue)
									if ($result[$value][$svalue] != $vv)
										unset($result[$value]);	
				}
				$result['icode'] = 0;
			}
			else
			{
				$result['icode'] = 1;
			}
			echo json_encode($result);
		}
		
		public function insert($data, &$iFound)
		{
			$iFound = 1;
			if($this->vtb($data, $r))
			{
				//print_r(json_encode(array("sName" => "me", "sPassword" => "lol", "sUserName" => "Gabriel")));
				$r['json'] = json_decode($r['json'], true);
				// create a random id.
				$id = uniqid($this->uid_prefix,true);
				// create a blank entry
				mkdir($this->datadir.$r['database'].'/'.$r['table'].'/'.$id.'/', 0777, true);
				// create base from meta
				$m = explode(",", file_get_contents($this->datadir.$r['database'].'/'.$r['table'].'/meta'));
				foreach ($m as $key => $value)
				{
					if (isset($r['json'][$value]))
						file_put_contents($this->datadir.$r['database'].'/'.$r['table'].'/'.$id.'/'.$value, $r['json'][$value]);
					else
						file_put_contents($this->datadir.$r['database'].'/'.$r['table'].'/'.$id.'/'.$value, "");
				}
				echo json_encode(array("icode" => 0, "uid" => $id));
			}
			else
			{
				echo json_encode(array("icode" => 1, "message" => "Structural error."));
			}
			
		}
		// Validate if the table and database exist.
		public function	vtb($data, &$r)
		{
			
			$x = strpos($data, " IN ");
			$y = strpos($data, " (");
			if ($x !== false && $x > 0)
				if ($this->dbexist($r['database'] = substr($data, $x + 4, $y - ($x + 4))))
					if(is_writable($this->datadir))
						if (strlen($r['table'] = substr($data, 0, $x)) > 0)
							if (strlen($r['json'] = substr($data, $y + 2, strlen($data) - $y - 3 )) > 0)
								return (1);
			return (0);
		}
		
		public function create_table($sName, &$iFound)
		{
			$sdbname = "";
			$stbname = "";
			$x = strpos($sName, " IN ");
			$y = strpos($sName, " (");
			if ($x !== false && $x > 0)
			{
				$sdbname = substr($sName, $x + 4, $y - ($x + 4));
				if ($this->dbexist($sdbname))
				{
					if(is_writable($this->datadir))
					{
						$stbname = substr($sName, 0, $x);
						if (mkdir($this->datadir.$sdbname.'/'.$stbname, 0777, true))
						{
							file_put_contents($this->datadir.$sdbname.'/'.$stbname.'/meta', substr($sName, $y + 2, strlen($sName) - $y - 3));
						}
						else
						{
							echo json_encode(array("icode" => 1, "message" => "Table exist"));
						}
					}
				}
				else
				{
					// TODO: Database does not exist or error
				}
			}
			$iFound = 1;
		}
		
		public function create_database($sName, &$iFound)
		{
			$sName = str_replace(' ', '_', $sName);
			$iFound = 1;
			if (!$this->dbexist($sName)) {
				if(is_writable($this->datadir))
				{
					if(mkdir($this->datadir.$sName.'/', 0777, true))
						echo json_encode(array("icode" => 0, "message" => "Database created"));
					else
						echo json_encode(array("icode" => 1, "message" => "Error: Database not created"));
				}
				else
				{
					echo json_encode(array("icode" => 1, "message" => "Error: Database not created : Data folder not writable."));
				}
			} else {
				echo json_encode(array("icode" => 1, "message" => "Database exist"));
			}
		}
		
		public function dbexist($sName)
		{
			if (strlen($sName) > 0)
				if (file_exists($this->datadir.$sName.'/')) 
					return (1);
			return (0);
		}
		
		public function extract_query($query)
		{
			$iFound = 0;
			
			if (($pos = strpos($query, 'CREATE DATABASE ')) === 0)
				$this->create_database(substr($query, 16, strlen($query) - 16), $iFound);
			if (($pos = strpos($query, 'CREATE TABLE ')) === 0)
				$this->create_table(substr($query, 13, strlen($query) - 13), $iFound);
			if (($pos = strpos($query, 'INSERT INTO ')) === 0)
				$this->insert(substr($query, 12, strlen($query) - 12), $iFound);
			if (($pos = strpos($query, 'SELECT ')) === 0)
				$this->select(substr($query, 7, strlen($query) - 7), $iFound);
			if (($pos = strpos($query, 'UPDATE ')) === 0)
				$this->update(substr($query, 7, strlen($query) - 7), $iFound);
			if (($pos = strpos($query, 'DELETE ')) === 0)
				$this->delete(substr($query, 7, strlen($query) - 7), $iFound);
			
			 
			
			if(!$iFound)
				echo json_encode(array("icode" => 1, "message" => "Error while extrapolating the query."));
		}
	
		public function init()
		{
			if(!isset($_GET['query']))
				echo json_encode(array("icode" => -1, "message" => "no query recived."));
			else
				if($this->iKey)
				{
					if(isset($_GET['key']))
						if(in_array($_GET['key'], $this->arrKeys))
							$this->extract_query(base64_decode($_GET[$this->get_param]));
				}
				else
					$this->extract_query(base64_decode($_GET[$this->get_param]));
		}
	}
	
	$dsm = new dsm;
	$dsm->init();
