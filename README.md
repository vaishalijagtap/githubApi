## Popular PHP Repositories on GitHub
---

### Assessment Details:

> Using PHP and MySQL, complete the following: 
> * Use the GitHub API to retrieve the most starred public PHP projects. Store 
the list of repositories in a MySQL table. The table must contain the 
repository ID, name, URL, created date, last push date, description, and 
number of stars. This process should be able to be run repeatedly and 
update the table each time.
> * Using the data in the table created in step 1, create an interface that 
displays a list of the GitHub repositories and allows the user to click 
through to view details on each one. Be sure to include all of the fields in 
step 1 – displayed in either the list or detailed view.
> * Create a README file with a description of your architecture and notes on 
installation of your application. You are free to use any PHP, JavaScript, or 
CSS frameworks as you see fit. 


### Project Requirements:
>
* Apache or NGINX web server
* PHP 7.0
* Bootstrap
* Javascript
* PDO Extension

## Technologies:
> 
* PHP 
* MySQL 
* Bootstrap 
* jQuery


--- 
 
 
* **Step 1 : CREATE DATABASE** 

``` 
CREATE DATABASE github_api;
```

```
CREATE TABLE IF NOT EXISTS `repositories`.`php_projects` (
  `id` int(11) NOT NULL,
  `repository_id` varchar(255) NOT NULL,
  `repository_name` varchar(255) NOT NULL,
  `repository_url` varchar(255) NOT NULL,
  `repository_created_date` varchar(255) NOT NULL,
  `repository_last_push_date` varchar(255) NOT NULL,
  `repository_description` varchar(255) NOT NULL,
  `number_of_stars` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 ```

* **Step 2 : Create DB Connection using PDO class File**
 
```
try{
	$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
	$this->conn->exec("set names utf8");
}catch(PDOException $exception){
	echo "Connection error: " . $exception->getMessage();
}
```

* **Step3 : Store Data in DB** 
GitHub API documentation: 
[https://developer.github.com/v3/](https://developer.github.com/v3/) 
[https://developer.github.com/v3/search/](https://developer.github.com/v3/search/) 
Get response from github for popular PHP respositories

``` 
header('Content-Type: application/json');
require_once('classes/api.php');

$api = new GithubApi();
// Call Github API and insert response in DB
$result = $api->insertToDB();

echo json_encode($result);
```

Access http://localhost/github/githubApi.php. Below is result screen:
![api_result](https://raw.githubusercontent.com/vaishalijagtap/githubApi/master/screens/api_result.PNG)

* **Step 4 : Fetch Data From DB**

``` 
require_once('../classes/api.php');

header('Content-Type: application/json');

$api = new GithubApi();

// Fetch records from DB to display in listing area
$data = $api->fetchDBRepoList();
echo json_encode(['aaData'=>$data]);
```

Used jQuery DataTables plugin to display records, fetched from DB using ajax, added sorting, pagination, search and View detail section. By default records are ordered by number of stars desending

``` 
$(document).ready(function() {
	var dt = $('#example').DataTable( {
		"ajax":           "data/repoList.php",
		"columns": [
			{
				"class":          "details-control",
				"orderable":      false,
				"data":           null,
				"defaultContent": ""
			},
			{ "data": "repository_id" },
			{ "data": "repository_name" },
			{ "data": "repository_url" },
			{ "data": "repository_created_date" },
			{ "data": "repository_last_push_date" },
			{ 
				"data": "repository_description",
				"render": function(data) {
					return data.substring(0,15)+"..."; // Display short description
				}
			},
			{ "data": "number_of_stars" }	
		],
		"order": [[7, 'desc']] // Order by number_of_stars in descending 
	} );
	...
```

Access http://localhost/github/githubApi.php. Below is result screen:
![repo_listing](https://raw.githubusercontent.com/vaishalijagtap/githubApi/master/screens/repo_listing.PNG)