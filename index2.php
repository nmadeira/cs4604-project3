<html>
<head>
	<title>SQLKillers</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>
	
	<div class="row">
		<div class="col-xs-1"></div>
		<div class="col-xs-10">
	<!-- List of tables -->
	<p>Click on any of the following relations to see the data in that relation: </p>
	<ul id="relationList">
		<li>Video</li>
		<li>Performer</li>
		<li>UserInfo</li>
		<li>Director</li>
		<li>MovieInfo</li>
		<li>TvEpisodeInfo</li>
		<li>Certificates</li>
	</ul>

	<!-- List of queries -->
	<p>Click on any of the following queries to see the returned rows: </p>
	<ul id="queryList">
	<li>Getting list of 10 users with most friends:<br>
		<code>SELECT uid1, first_name, last_name, count(uid2) "total" FROM friends,userinfo WHERE friends.uid1=userinfo.uid GROUP BY uid1,first_name,last_name ORDER BY total desc limit 10;</code></li>
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
		$("#relationList").click(function(e) {
			var table = e.target.innerHTML.toLowerCase();
			loadTable(table, 20, 1, '', '');
		});
		$("#queryList code").click(function(e) {
			var query = e.target.innerHTML;
			loadQuery(query, 20, 1, '', '');
		});
		document.forms[0].onsubmit = adhocQuery;
	});

	function loadTable(table, limit, page, sort, order) {
		$('#modal-loading').show(0);
		//$('#resultsModalTitle').html('Loading ...');
		$('#results-table').html('');
		$('#pagination').html('');
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
		$('#modal-loading').show(0);
		$('#resultsModalTitle').html('Loading ...');
		$('#results-table').html('');
		$('#pagination').html('');
		$('#resultsModal').modal('show');
		$.ajax({
			url: 'process.php',
			type: 'GET',
			data: {o: 'q', q: query, l: limit, p: page, so: sort, or: order},
		})
		.done(function(msg) {
			var res = JSON.parse(msg);
			generateTable2(res);
			$('#modal-loading').hide(0);
		})
		.fail(function() {
			$('#load-alert').show(0);
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
	</script>
</body>
</html>