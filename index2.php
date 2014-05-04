<html>
<head>
	<title>SQLKillers</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	<header>
      <h1 class="reflected">SQLKillers</h1><br>
	  <h3 class="reflected">Behrooz Kamali, Nick Madeira, Paul Sharma</h3>
    </header>
	
	<div class="row">
		<div class="col-xs-1"></div>
		<div class="col-xs-10">
	<!-- List of tables -->
	<p>Click on any of the following relations to see the data in that relation: </p>
	<ul id="relationList">
		<li class="clickable bg-danger">Video</li>
		<li class="clickable bg-danger">Performer</li>
		<li class="clickable bg-danger">UserInfo</li>
		<li class="clickable bg-danger">Director</li>
		<li class="clickable bg-danger">MovieInfo</li>
		<li class="clickable bg-danger">TvEpisodeInfo</li>
		<li class="clickable bg-danger">Certificates</li>
	</ul>

	<!-- List of queries -->
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
		<p class="clickable bg-danger">SELECT title FROM videoinfo WHERE producer='TV UNAM' LIMIT 20;</p></li>	
	</ul>

	<!-- Ad-hoc query form -->
	<form role="form" action="#">
		<div class="form-group">
			<label for="adhocquery">Ad-hoc query: </label>
			<input type="text" class="form-control" id="adhocquery" placeholder="Enter query here">
		</div>
		<button type="submit" class="btn btn-default">Submit</button>
		<button type="reset" class="btn btn-default">Clear</button>
	</form>
	</div>
	<div class="col-xs-1"></div>
	</div>

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

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function($) {
		$('#load-alert').hide(0);
		$('#query-alert').hide(0);
		$("#relationList").click(function(e) {
			var table = e.target.innerHTML.toLowerCase();
			loadTable(table, 20, 1, '', '');
		});
		$("#queryList p").click(function(e) {
			var query = e.target.innerHTML;
			loadQuery(query, 20, 1, '', '');
		});
		$('#resultsModal').on('hide.bs.modal', function(e) {
			$('#pagination').html('');
			$('#results-table').html('');
		});
		document.forms[0].onsubmit = adhocQuery;
	});

	function loadTable(table, limit, page, sort, order) {
		$('#load-alert').hide(0);
		$('#query-alert').hide(0);
		$('#modal-loading').show(0);
		$('#resultsModalTitle').html('Loading ...');
		$('#results-table').html('');
		$('#resultsModal').modal('show');
		$.ajax({
			url: 'process.php',
			type: 'GET',
			data: {o: 's', t: table, l: limit, p: page, so: sort, or: order},
		})
		.done(function(msg) {
			var res = JSON.parse(msg);
			generateTable(res);
			$('#modal-loading').hide(0);
		})
		.fail(function() {
			$('#load-alert').show(0);
		})
		.always(function() {
		});
	}

	function loadQuery(query, limit, page, sort, order) {
		$('#load-alert').hide(0);
		$('#query-alert').hide(0);
		$('#modal-loading').show(0);
		$('#resultsModalTitle').html('Loading ...');
		$('#results-table').html('');
		$('#resultsModal').modal('show');
		$.ajax({
			url: 'process.php',
			type: 'GET',
			data: {o: 'q', q: query, l: limit, p: page, so: sort, or: order},
		})
		.done(function(msg) {
			var res = JSON.parse(msg);
			if(res.rows.length == 0) {
				badQuery();
				return false;
			}
			generateTable2(res);
			$('#modal-loading').hide(0);
		})
		.fail(function() {
			$('#load-alert').show(0);
			$('#resultsModalTitle').html('Error!');
		})
		.always(function() {
		});
	}

	function adhocQuery(e) {
		var field = $('#adhocquery');
		var query = field.val();
		loadQuery(query, 20, 1)
		e.preventDefault();
	}

	function generateTable(data) {
		// modal title
		$('#resultsModalTitle').html(data.table + ' Table');

		// table header
		var content = "<tr>";
		for (var key in data.rows[0]) {
			var order = "";
			if(data.sort == key && data.order != "1") {
				order = "1";
			}
			content += "<th class='clickable' onclick=\"loadTable('" + data.table + "', " + data.limit + ", 1, '" + key + "', '" + order + "')\">";
			content += key;
			content += "</th>";
		};
		content += "</tr>\n";

		// table body
		for(var i = 0; i < data.rows.length; i++) {
			content += "<tr>";
			for(var key in data.rows[i]) {
				content += "<td>";
				if(data.rows[i][key] === "null") {

				} else {
					content += data.rows[i][key];
				}
				content += "</td>";
			}
			content += "</tr>";
		}

		// pagination
		var page = parseInt(data.page);
		var prev = page - 1;
		var next = page + 1;
		var pagination = "";
		if(prev > 0) {
			pagination += "<ul class='pagination'>";
			pagination += "<li><a href='#' onclick=\"loadTable('" + data.table + "', " + data.limit + ", 1, '" + data.sort + "', '" + data.order + "')\">&laquo;</a></li>";
			pagination += "<li><a href='#' onclick=\"loadTable('" + data.table + "', " + data.limit + ", " + prev + ", '" + data.sort + "', '" + data.order + "')\">&lsaquo;</a></li>";
			pagination += "</ul>";
		}
		var pages = Math.ceil(data.size/data.limit);
		pagination += "<ul class='pagination'><li><a style='cursor:text;'>Page " + data.page + " out of " + pages + "</a></li></ul>";
		if(next <= pages) {
			pagination += "<ul class='pagination'>";
			pagination += "<li><a href='#' onclick=\"loadTable('" + data.table + "', " + data.limit + ", " + next + ", '" + data.sort + "', '" + data.order + "')\">&rsaquo;</a></li>";
			pagination += "<li><a href='#' onclick=\"loadTable('" + data.table + "', " + data.limit + ", " + pages + ", '" + data.sort + "', '" + data.order + "')\">&raquo;</a></li>";
			pagination += "</ul>";
		}

		// put in place
		$('#results-table').append(content);
		$('#pagination').html(pagination);
	}

	function generateTable2(data) {
		// modal title
		$('#resultsModalTitle').html(data.query);

		// table header
		var content = "<tr>";
		for (var key in data.rows[0]) {
			var order = "";
			if(data.sort == key && data.order != "1") {
				order = "1";
			}
			content += "<th>";
			// class='clickable' onclick=\"loadQuery('" + data.query + "', " + data.limit + ", 1, '" + key + "', '" + order + "')\"
			content += key;
			content += "</th>";
		};
		content += "</tr>\n";

		// table body
		for(var i = 0; i < data.rows.length; i++) {
			content += "<tr>";
			for(var key in data.rows[i]) {
				content += "<td>";
				if(data.rows[i][key] === "null") {

				} else {
					content += data.rows[i][key];
				}
				content += "</td>";
			}
			content += "</tr>";
		}

		// pagination
		var page = parseInt(data.page);
		var prev = page - 1;
		var next = page + 1;
		var pagination = "";
		if(prev > 0) {
			pagination += "<ul class='pagination'>";
			pagination += "<li><a href='#' onclick=\"loadQuery('" + data.query + "', " + data.limit + ", 1, '" + data.sort + "', '" + data.order + "')\">&laquo;</a></li>";
			pagination += "<li><a href='#' onclick=\"loadQuery('" + data.query + "', " + data.limit + ", " + prev + ", '" + data.sort + "', '" + data.order + "')\">&lsaquo;</a></li>";
			pagination += "</ul>";
		}
		var pages = Math.ceil(data.size/data.limit);
		pagination += "<ul class='pagination'><li><a style='cursor:text;'>Page " + data.page + " out of " + pages + "</a></li></ul>";
		if(next <= pages) {
			pagination += "<ul class='pagination'>";
			pagination += "<li><a href='#' onclick=\"loadQuery('" + data.query + "', " + data.limit + ", " + next + ", '" + data.sort + "', '" + data.order + "')\">&rsaquo;</a></li>";
			pagination += "<li><a href='#' onclick=\"loadQuery('" + data.query + "', " + data.limit + ", " + pages + ", '" + data.sort + "', '" + data.order + "')\">&raquo;</a></li>";
			pagination += "</ul>";
		}

		// put in place
		$('#results-table').append(content);
		$('#pagination').html(pagination);
	}

	function badQuery() {
		$('#resultsModalTitle').html('Error!');
		$('#query-alert').show(0);
		$('#modal-loading').hide(0);
	}
	</script>
</body>
</html>