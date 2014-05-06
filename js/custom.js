$(document).ready(function($) {
	$('#load-alert').hide(0);
	$('#query-alert').hide(0);
	$('#iload-alert').hide(0);
	$('#iquery-alert').hide(0);
	$('#iquery-success').hide(0);
	$("#relationList").click(function(e) {
		var table = e.target.innerHTML.toLowerCase();
		loadTable(table, 20, 1, '', '');
		e.preventDefault();
	});
	$("#queryList p").click(function(e) {
		var query = e.target.innerHTML;
		loadQuery(query, 20, 1, '', '');
		e.preventDefault();
	});
	$("#insertionList").click(function(e) {
		var table = e.target.innerHTML.toLowerCase();
		loadInsert(table, 1, 1);
		e.preventDefault();
	});
	$("#deletionList").click(function(e) {
		var table = e.target.innerHTML.toLowerCase();
		loadDelete(table, 20, 1, '', '');
		e.preventDefault();
	});
	$('#resultsModal').on('hide.bs.modal', function(e) {
		$('#pagination').html('');
		$('#results-table').html('');
	});
	$('#insertionModal').on('hide.bs.modal', function(e) {
		$('#modal-form').html('');
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

function loadInsert(table, limit, page) {
	$('#iload-alert').hide(0);
	$('#iquery-alert').hide(0);
	$('#imodal-loading').show(0);
	$('#insertionModalTitle').html('Loading ...');
	$('#modal-form').html('');
	$('#insertionModal').modal('show');
	$.ajax({
		url: 'process.php',
		type: 'GET',
		data: {o: 's', t: table, l: 1, p: 1},
	})
	.done(function(msg) {
		var res = JSON.parse(msg);
		generateInsert(res);
		$('#imodal-loading').hide(0);
	})
	.fail(function() {
		$('#iload-alert').show(0);
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

function loadDelete(table, limit, page, sort, order) {
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
		generateTableDel(res);
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
		content += "<th class='clickable-title' onclick=\"loadTable('" + data.table + "', " + data.limit + ", 1, '" + key + "', '" + order + "')\">";
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

function generateTableDel(data) {
	// modal title
	$('#resultsModalTitle').html(data.table + ' Table');

	// table header
	var content = "<tr>";
	for (var key in data.rows[0]) {
		var order = "";
		if(data.sort == key && data.order != "1") {
			order = "1";
		}
		content += "<th class='clickable-title' onclick=\"loadDelete('" + data.table + "', " + data.limit + ", 1, '" + key + "', '" + order + "')\">";
		content += key;
		content += "</th>";
	};
	content += "<th>Delete</th>";
	content += "</tr>\n";

	// table body
	for(var i = 0; i < data.rows.length; i++) {
		var delFields = {};
		content += "<tr>";
		for(var key in data.rows[i]) {
			content += "<td>";
			if(data.rows[i][key] === "null") {

			} else {
				content += data.rows[i][key];
				delFields[key] = data.rows[i][key];
			}
			content += "</td>";
		}

		// delete button
		delFields = data;
		delFields['deleteRow'] = i;
		var fields = JSON.stringify(delFields);
		content += "<td>";
		content += "<span class='clickable-title glyphicon glyphicon-remove' onclick='deleteRow(" + fields + ")'></span"
		content += "</td>";

		content += "</tr>";
	}

	// pagination
	var page = parseInt(data.page);
	var prev = page - 1;
	var next = page + 1;
	var pagination = "";
	if(prev > 0) {
		pagination += "<ul class='pagination'>";
		pagination += "<li><a href='#' onclick=\"loadDelete('" + data.table + "', " + data.limit + ", 1, '" + data.sort + "', '" + data.order + "')\">&laquo;</a></li>";
		pagination += "<li><a href='#' onclick=\"loadDelete('" + data.table + "', " + data.limit + ", " + prev + ", '" + data.sort + "', '" + data.order + "')\">&lsaquo;</a></li>";
		pagination += "</ul>";
	}
	var pages = Math.ceil(data.size/data.limit);
	pagination += "<ul class='pagination'><li><a style='cursor:text;'>Page " + data.page + " out of " + pages + "</a></li></ul>";
	if(next <= pages) {
		pagination += "<ul class='pagination'>";
		pagination += "<li><a href='#' onclick=\"loadDelete('" + data.table + "', " + data.limit + ", " + next + ", '" + data.sort + "', '" + data.order + "')\">&rsaquo;</a></li>";
		pagination += "<li><a href='#' onclick=\"loadDelete('" + data.table + "', " + data.limit + ", " + pages + ", '" + data.sort + "', '" + data.order + "')\">&raquo;</a></li>";
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

function generateInsert(data) {
	// modal title
	$('#insertionModalTitle').html(data.table + ' Table');

	// form inputs
	var content = "<form role='form' action='#' onsubmit='insertValues(event)'>";
	for(var key in data.rows[0]) {
		content += "<div class='form-group'>";
		content += "<label for='" + key + "input'>" + key + "</label>";
		content += "<input type='text' class='form-control' id='" + key + "input' placeholder='e.g., " + data.rows[0][key] + "'>";
		content += "</div>";
	}

	// hidden input for table name

	content += "<input type='hidden' class='form-control' id='tablename' value='" + data.table + "'>";

	// buttons
	content += "<button type='submit' class='btn btn-default'>Insert</button>";
	content += "<button type='reset' class='btn btn-default'>Clear</button>";
	
	// end form
	content += '</form>';

	// put in place
	$('#modal-form').append(content);
}

function insertValues(e) {
	$('#iquery-alert').hide(0);
	$('#iquery-success').hide(0);
	var inps = e.target.getElementsByTagName('input');
	var table = "";
	var fields = {};
	for(var i = 0; i < inps.length; i++) {
		if(inps[i].id == "tablename") {
			table = inps[i].value;
			fields["table"] = table;
			continue;
		}
		var key = inps[i].id.substr(0,inps[i].id.length-5);
		fields[key] = inps[i].value;
	}
	fields['o'] = 'i';
	// alert(JSON.stringify(fields));

	// ajax insertion
	$.ajax({
		url: 'process.php',
		type: 'POST',
		data: fields,
	})
	.done(function(msg) {
		if(msg == "error") {
			$('#iquery-alert').show(0);
			return;
		}
		for(var i = 0; i < inps.length; i++) {
			inps[i].setAttribute('disabled','disabled');
		}
		$('#iquery-success').show(0);
		setTimeout("$('#insertionModal').modal('hide')",2000);
	})
	.fail(function(msg) {
		$('#iquery-alert').show(0);
		return;
	})
	.always(function() {
	});

	e.preventDefault();
}

function deleteRow(fields) {
	var deletes = {};
	deletes = fields.rows[fields.deleteRow];
	deletes['table'] = fields.table;
	deletes['o'] = 'd';
	if(!confirm("Are you sure you want to delete row " + JSON.stringify(deletes) + " from table '" + fields.table + "'?")) {
		return;
	}

	// ajax deletion
	$.ajax({
		url: 'process.php',
		type: 'POST',
		data: deletes,
	})
	.done(function(msg) {
		if(msg == "error") {
			alert("An error happened, please try again later!");
			return;
		}
		loadDelete(fields.table, fields.limit, fields.page, fields.sort, fields.order);
		return;
	})
	.fail(function(msg) {
		alert("An error happened, please try again later!");
		return;
	})
	.always(function() {
	});
}

$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});
