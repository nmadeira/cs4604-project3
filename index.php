<html>
<head>
	<title>SQLKillers</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#pagetop">SQLKillers</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="#predtabl">Relations</a></li>
					<li><a href="#predquer">Preset Queries</a></li>
					<li><a href="#adhoc">Ad-hoc Queries</a></li>
					<li><a href="#insetionSec">Insertion</a></li>
					<li><a href="#deletionSec">Deletion</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#about">About Us</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	<a id="pagetop"></a>
	<header>
		<br><br><br>
		<h1 class="reflected">SQLKillers</h1><br>
		<h3 class="reflected">Behrooz Kamali, Nick Madeira, Paul Sharma</h3>
		<a id="predtabl"></a>
		<br>
	</header>
	
	<div class="row">
		<div class="col-xs-1"></div>
		<div class="col-xs-10">
	<!-- List of tables -->
	<h3>Table Queries</h3>
	<p>Click on any of the following relations to see the data in that relation: </p>
	<div class="list-group" id="relationList" style="width:200px;">
		<a href="#" class="list-group-item">VideoInfo</a>
		<a href="#" class="list-group-item">Performer</a>
		<a href="#" class="list-group-item">UserInfo</a>
		<a href="#" class="list-group-item">Director</a>
		<a href="#" class="list-group-item">MovieInfo</a>
		<a href="#" class="list-group-item">TvEpisodeInfo</a>
		<a class="interlink" id="predquer"></a>
		<a href="#" class="list-group-item">Certificates</a>
	</div>

	<hr>

	<!-- List of queries -->
	<h3>Preset Queries</h3>
	<p>Click on any of the following queries to see the returned rows: </p>
	<ul id="queryList">
	<li>Getting list of 10 users with most friends:<br>
		<p class="clickable bg-danger">SELECT uid1, first_name, last_name, count(uid2) "total" FROM friends,userinfo WHERE friends.uid1=userinfo.uid 
			GROUP BY uid1,first_name,last_name ORDER BY total desc limit 10;</p></li>
	<li>Getting list the top 50 best rated movies:<br>
		<p class="clickable bg-danger">SELECT m.mid, m.mtitle, ROUND(AVG(r.rate_score), 2) AS avg_rate, ROUND(COUNT(r.rate_score), 0) AS rate_count, 
			ROUND((AVG(r.rate_score) * COUNT(r.rate_score)) / COUNT(r.rate_score) * COUNT(r.rate_score)/100, 2) AS score_calc FROM movieinfo AS m, 
			videoinfo AS v, ratings AS r WHERE m.mid = v.vid AND m.mid = r.vid GROUP BY m.mid, m.mtitle ORDER BY score_calc DESC LIMIT 50;</p></li>
	<li>Getting all the movies that Brad Pitt has starred in:<br>
		<p class="clickable bg-danger">SELECT * FROM videoinfo WHERE vid in (SELECT vid FROM actin WHERE 
			pid = (SELECT pid FROM performer WHERE first_name = 'Brad' AND last_name = 'Pitt')) ORDER BY release_year desc LIMIT 10;</p></li>
	<li>Getting all the Sci-Fi movies made in 2001:<br>
		<p class="clickable bg-danger">SELECT v.vid, title, release_year, genre FROM videoinfo AS v, belongtoGenre AS b 
			WHERE v.vid = b.vid AND v.release_year = 2001 AND genre = 'Sci-Fi' LIMIT 10;</p></li>
	<li>Getting all users named Matt:<br>
		<p class="clickable bg-danger">SELECT first_name,last_name FROM userinfo WHERE first_name='Matt' LIMIT 20;</p></li>
	<li>Getting all the movies that were produced by TV UNAM:<br>
	<a id="adhoc"></a>
		<p class="clickable bg-danger">SELECT title FROM videoinfo WHERE producer='TV UNAM' LIMIT 20;</p></li>	
	</ul>

	<hr>

	<!-- Ad-hoc query form -->
	<h3>Ad-hoc Queries</h3>
	<form role="form" action="#">
		<div class="form-group">
			<label for="adhocquery">Ad-hoc query: </label>
			<textarea class="form-control" id="adhocquery" placeholder="Enter query here" rows="6" style="width:75%;"></textarea>
			<!-- <input type="text" class="form-control" id="adhocquery" placeholder="Enter query here"> -->
		</div>
		<a id="insetionSec"></a>
		<button type="submit" class="btn btn-default">Submit</button>
		<button type="reset" class="btn btn-default">Clear</button>
	</form>

	<hr>

	<!-- Data insertion -->
	<h3>Insertion</h3>
	<p>Click on any of the following tables to insert values in them: </p>
	<div class="list-group" id="insertionList" style="width:200px;">
		<a href="#" class="list-group-item">VideoInfo</a>
		<a href="#" class="list-group-item">Performer</a>
		<a href="#" class="list-group-item">UserInfo</a>
		<a href="#" class="list-group-item">Director</a>
		<a href="#" class="list-group-item">MovieInfo</a>
		<a href="#" class="list-group-item">Genre</a>
		<a id="deletionSec"></a>
		<a href="#" class="list-group-item">Certificates</a>
	</div>

	<hr>

	<!-- Data Deletion -->
	<h3>Deletion</h3>
	<p>Click on any of the following tables to delete rows in them: </p>
	<div class="list-group" id="deletionList" style="width:200px;">
		<a href="#" class="list-group-item">VideoInfo</a>
		<a href="#" class="list-group-item">Performer</a>
		<a href="#" class="list-group-item">UserInfo</a>
		<a href="#" class="list-group-item">Director</a>
		<a href="#" class="list-group-item">MovieInfo</a>
		<a href="#" class="list-group-item">Genre</a>
		<a id="about"></a>
		<a href="#" class="list-group-item">Certificates</a>
	</div>

	<hr>

	<!-- About Us -->
	<h3>About Us</h3>
	<h4 class="text-info">Class information</h4>
	<p class="lead">CS 4604: Introduction to Database Management Systems, Spring 2014<br>
	Instructor: Dr. Aditya Prakash</p>
	<h4 class="text-info">Team Members</h4>
	<p class="lead" style="width:300px">Behrooz Kamali<br>
		Nick Madeira<br>
		Paul Sharma</p>

	</div>
	<div class="col-xs-1"></div>
	</div>

	<hr>


	<!-- Results Modal -->
	<div class="modal fade" id="resultsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="resultsModalTitle">Results Table</h4>
				</div>
				<div class="modal-body">
					<div id="modal-loading"><span class="glyphicon glyphicon-refresh rotating"></span> Loading data ...</div>
					<div class="bg-danger" id="load-alert"><p>Data cannot be loaded at this time, please try again later!</p></div>
					<div class="bg-danger" id="query-alert"><p>You either entered a bad query or no rows where returned!</p></div>
					<div class="table-responsive" id="modal-table">
						<div id="pagination" class="text-center"></div>
						<table class="table table-hover table-striped table-condensed" id="results-table">

						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Insertion/Modification Modal -->
	<div class="modal fade" id="insertionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="insertionModalTitle">Results Table</h4>
				</div>
				<div class="modal-body">
					<div id="imodal-loading"><span class="glyphicon glyphicon-refresh rotating"></span> Loading data ...</div>
					<div class="bg-danger" id="iload-alert"><p>Data cannot be loaded at this time, please try again later!</p></div>
					<div class="bg-danger" id="iquery-alert"><p>You entered invalid data, fill all the required fields!</p></div>
					<div class="bg-success" id="iquery-success"><p>Data is inserted successfully!</p></div>
					<div class="" id="modal-form">
						
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/custom.js">	</script>
</body>
</html>
