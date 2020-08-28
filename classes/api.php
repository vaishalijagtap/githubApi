<?php
require_once('database.php');

// Github Api class
class GithubApi extends Database{
	public $context;
    public $options = [
						'http' => [
							'method' => 'GET',
							'header' => [
								'User-Agent: PHP'
							]
						]
					];
  
    function __construct(){
        $this->context = stream_context_create($this->options);
		parent::__construct();
    }
	
	/**
	* Function to fetch repositories listing from databse
	* @access public
	* @return array
	*
	*/
	public function fetchDBRepoList() {
		$query = $this->conn->prepare("SELECT * FROM php_projects ORDER BY number_of_stars DESC");
		$res = $query->execute();
		$data = [];
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $row) {
			$data[]	 = array($row['repository_id'], $row['repository_name'], $row['repository_url'], $row['repository_created_date'], $row['repository_last_push_date'], $row['repository_description'], $row['number_of_stars']) ;
		}

		return $rows;
	}
	
	/**
	* Function to insert repositories info in DB from api response
	* @access public
	* @return array
	*
	*/
	public function insertToDB() {
		$inserted = 0;
		$updated = 0;
		for ($count = 1; $count <= 10; $count++) { 
			$url = "https://api.github.com/search/repositories?q=language:php&page=$count&per_page=10";
 
			$response = file_get_contents($url, false, $this->context);
			if($response){
				$res = json_decode($response,true);
				$data = $res['items'];
				
				for($i = 0; $i < count($data); $i++) {
					$name = $data[$i]['name'];
					$id = $data[$i]['id'];
					$url = $data[$i]['html_url'];
					$description = $data[$i]['description'];
					$created_at = $data[$i]['created_at'];
					$updated_at = $data[$i]['updated_at'];
					$star = $data[$i]['watchers_count'];
					
					$sql = "SELECT * FROM php_projects WHERE repository_id = :id";
					$sth = $this->conn->prepare($sql);
					$sth->bindParam(':id', $id, PDO::PARAM_INT);
					$res = $sth->execute();
					$record = $sth->fetchAll();

			
					if (count($record) == 0) {
						// No data present so insert new row in DB
						$sql = "INSERT INTO php_projects 
								(repository_id, repository_name, repository_url, repository_created_date, repository_last_push_date, repository_description, number_of_stars) 
								VALUES (?,?,?,?,?,?,?)";
						$this->conn->prepare($sql)->execute([$id, $name, $url, $created_at, $updated_at, $description, $star]);
						$inserted++;
					} else {
						// record already present so update row in DB
						$sql = "UPDATE php_projects 
								SET repository_name = ?, repository_url = ? , repository_created_date = ?, repository_last_push_date = ?, repository_description = ?, number_of_stars = ?
								WHERE repository_id = ?";
						$this->conn->prepare($sql)->execute([$name, $url, $created_at, $updated_at, $description, $star, $id]);
						$updated++;
					}
				}
			}
		}
		return ['no_of_rows_inserted' => $inserted, 'no_of_rows_updated' => $updated];
	}
}

?>