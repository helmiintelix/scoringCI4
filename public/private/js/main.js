window.history.forward();



var itest = 0;
function noBack() {
	window.history.forward();
}

var preview_sp = function (id) {
	showBillingPrintDialog2(1000, 600, 'Print Preview', GLOBAL_MAIN_VARS["SITE_URL"] + "penanganan/review_sp/?id=" + id);
}

var preview_mpa = function (id) {
	showBillingPrintDialog2(1000, 600, 'Print Preview', GLOBAL_MAIN_VARS["SITE_URL"] + "penanganan/review_mpa/?id=" + id);
}

var removeMandatory = function (data) {
	$(data).removeClass("mandatory_invalid").parent().removeClass("error-state");
}



/**
 * jQuery Form Validation
 */
var jqformValidate = function (jqForm) {
	var passed = true;

	$('.mandatory', $(jqForm)).each(function () {
		if (!$(this).val()) {
			$(this).addClass("mandatory_invalid");
			passed = false;
		}
		else {
			$(this).removeClass("mandatory_invalid");
		}
	});

	if (!passed) {
		alert('Please enter a value for mandatory fields');
	}
	return passed;
}

/**
 * Validate numeric input
 */
var validateNumericInput = function (obj) {
	if (obj.value == "< type a number in here >") {
		obj.value = "";
	}
	else {
		validateMsisdn(obj);
		var input = obj.value;
		if (input.length == 0) {
			obj.value = "< type a number in here >";
		}
	}
};

/**
 * Validate MSISDN
 */
var validateMsisdn = function (obj) {
	var input = obj.value;
	var output = "";
	if (isNaN(input)) {
		for (var i = 0; i < input.length; i++) {
			if (!isNaN(input.charAt(i)) && (input.charAt(i) != ' ')) {
				output = output + input.charAt(i);
			}
		}
		obj.value = output;
	}
}

/**
 * Validate DTMF input
 */
var validateDTMF = function (obj) {
	var input = obj.value;
	var output = "";
	for (var i = 0; i < input.length; i++) {
		if (!isNaN(input.charAt(i)) || (input.charAt(i) == '#') || (input.charAt(i) == '*')) {
			output = output + input.charAt(i);
		}
	}
	obj.value = output;
};

var uuid = function () {
	//var s = [], itoh = '0123456789ABCDEF';
	var s = [], itoh = '0123456789abcdef';
	// Make array of random hex digits. The UUID only has 32 digits in it, but we
	// allocate an extra items to make room for the '-'s we'll be inserting.
	for (var i = 0; i < 36; i++) s[i] = Math.floor(Math.random() * 0x10);

	// Conform to RFC-4122, section 4.4
	s[14] = 4;	// Set 4 high bits of time_high field to version
	s[19] = (s[19] & 0x3) | 0x8;	// Specify 2 high bits of clock sequence

	// Convert to hex chars
	for (var i = 0; i < 36; i++) s[i] = itoh[s[i]];

	// Insert '-'s
	//s[8] = s[13] = s[18] = s[23] = '-';

	return s.join('');
}

var addZero = function (i) {
	if (i < 10) {
		i = "0" + i;
	}
	return i;
};

var getQueryVariable = function (variable, queryString) {
	var vars = queryString.split('&');
	for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split('=');
		if (decodeURIComponent(pair[0]) == variable) {
			return decodeURIComponent(pair[1]);
		}
	}
};

var formatNumber = function (num, decpoint, sep) {
	// check for missing parameters and use defaults if so
	if (arguments.length == 2) {
		sep = ",";
	}
	if (arguments.length == 1) {
		sep = ",";
		decpoint = ".";
	}
	// need a string for operations
	num = num.toString();
	// separate the whole number and the fraction if possible
	a = num.split(decpoint);
	x = a[0]; // decimal
	y = a[1]; // fraction
	z = "";

	if (typeof (x) != "undefined") {
		// reverse the digits. regexp works from left to right.
		for (i = x.length - 1; i >= 0; i--)
			z += x.charAt(i);
		// add seperators. but undo the trailing one, if there
		z = z.replace(/(\d{3})/g, "$1" + sep);
		if (z.slice(-sep.length) == sep)
			z = z.slice(0, -sep.length);
		x = "";
		// reverse again to get back the number
		for (i = z.length - 1; i >= 0; i--)
			x += z.charAt(i);
		// add the fraction back in, if it was there
		if (typeof (y) != "undefined" && y.length > 0)
			x += decpoint + y;
	}
	return x;
}

/**
 * Numbers Only
 */
var numbersOnly = function (myfield, e, dec) {
	var key;
	var keychar;

	if (window.event) {
		key = window.event.keyCode;
	}
	else if (e) {
		key = e.which;
	}
	else {
		return true;
	}

	keychar = String.fromCharCode(key);
	if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27)) {
		return true;
	}
	else if ((("0123456789").indexOf(keychar) > -1)) {
		return true;
	}
	else if (dec && (keychar == ".")) {
		myfield.form.elements[dec].focus();
		return false;
	}
	else {
		return false;
	}
}

/**
 * Alphabets Only
 */
var alphabetOnly = function (myfield, e, dec) {
	var key;
	var keychar;

	if (window.event) {
		key = window.event.keyCode;
	}
	else if (e) {
		key = e.which;
	}
	else {
		return true;
	}

	keychar = String.fromCharCode(key);
	if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27)) {
		return true;
	}
	else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ").indexOf(keychar) > -1)) {
		return true;
	}
	else if (dec && (keychar == ".")) {
		myfield.form.elements[dec].focus();
		return false;
	}
	else {
		return false;
	}
}

// Prevent space
var noSpace = function (evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	//alert(charCode);
	if (charCode == 32) {
		return false;
	}
	return true;
}

/**
 * Alphanumberics Only
 */
var alphanumericsonly = function (myfield, e, dec) {
	var key;
	var keychar;

	if (window.event) {
		key = window.event.keyCode;
	}
	else if (e) {
		key = e.which;
	}
	else {
		return true;
	}

	keychar = String.fromCharCode(key);

	// control keys
	if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27) || (key == 46)) {
		return true;
	}
	else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ").indexOf(keychar) > -1)) { // alphabets
		return true;
	}
	else if ((("0123456789").indexOf(keychar) > -1)) { // numbers
		return true;
	}
	else if (dec && (keychar == ".")) { // decimal point jump
		myfield.form.elements[dec].focus();
		return false;
	}
	else {
		return false;
	}
}

var enableButtonLoading = function (data, mode) {
	if (mode == 'add') {
		let htmlClean = DOMPurify.sanitize('Save	<i class="icon-floppy bg-lightBlue"></i>');
		$(data).removeAttr('disabled', '').html(htmlClean);
	}
	else if (mode == 'update') {
		let htmlClean = DOMPurify.sanitize('Update	<i class="icon-pencil bg-lightBlue"></i>');
		$(data).removeAttr('disabled', '').html(htmlClean);
	}
	else {
		let htmlClean = DOMPurify.sanitize('Save	<i class="icon-floppy bg-lightBlue"></i>');
		$('button:contains(loading...)').removeAttr('disabled', '').html(htmlClean);
		let htmlClean2 = DOMPurify.sanitize('Update	<i class="icon-pencil bg-lightBlue"></i>');
		$('button:contains(Update Loading...)').removeAttr('disabled', '').html(htmlClean2);
	}
}

var buttonLoading = function (data, mode) {
	if (mode == 'add') {
		constain = 'loading...';
	}
	else if (mode == 'update') {
		constain = 'Update Loading...';
	}
	else {
		constain = 'loading...';
	}
	
	$(data).attr('disabled', 'disabled').html(DOMPurify.sanitize(constain));
}

var RefreshTable = function (tableId, urlData) {
	$.getJSON(urlData, null, function (json) {
		table = $(tableId).dataTable();
		oSettings = table.fnSettings();
		table.fnClearTable(this);
		for (var i = 0; i < json.aaData.length; i++) {
			table.oApi._fnAddData(oSettings, json.aaData[i]);
		}
		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		table.fnDraw();
	});
}

var showInfo = function (message, period) {
	if (period == '' || period < 3000) {
		period = 3000
	}
	$("#showinfo").toast('hide');
	$("#showinfo").parent().attr('style', 'z-index:99999');

	setTimeout(() => {

		$("#toast-header-title").html('Information');
		$("#toast-header").attr('class', 'toast-header text-white bg-primary');
		$("#showinfo").attr('data-bs-delay', period);
		$("#body-toast-info").html(DOMPurify.sanitize(message));
		$("#showinfo").toast('show');
	}, 300);


	// $("#showinfo").addEventListener('hidden.bs.toast', () => {
	// 	console.log('toast done');
	// })

}

var showWarning = function (message, period) {
	$("#showinfo").toast('hide');

	setTimeout(() => {
		$("#toast-header-title").html('Warning');
		$("#toast-header").attr('class', 'toast-header text-white bg-danger');
		$("#showinfo").attr('data-bs-delay', period);
		$("#body-toast-info").html(DOMPurify.sanitize(message));
		$("#showinfo").toast('show');
	}, 300);
}

var showRinger = function (period) {
	var unique_id = $.gritter.add({
		title: 'Call in!',
		text: 'Customer tidak terdaftar.&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-bell-alt icon-animated-bell bigger-230"></i>',
		//sticky: true,
		//time: '',
		time: (period !== undefined ? period : 5000),
		class_name: 'gritter-error gritter-light'
	});
}

var showBreakDialog = function (width, height, title, source, button, modal) {
	this.blur();
	$("body").append("<div id='CommonDialog' title='" + title + "'></div>");
	$("#CommonDialog").html(GLOBAL_MAIN_VARS["SPINNER"]);
	var buttons = {}
	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}
	$("#CommonDialog").dialog({
		bgiframe: true,
		autoOpen: true,
		width: width,
		height: height,
		resizable: false,
		modal: modal,
		position: 'center',
		buttons: buttons,
		close: function (event, ui) {
			$(this).dialog('destroy');
			$(this).remove();
		},
		beforeclose: function () {
			$("#ui-datepicker-div").hide();
		}
	});

	$.post(source, {}, function (htm) {
		$("#CommonDialog").html(DOMPurify.sanitize(htm));
	}, "html");

};

var warningDialog = function (width, height, title, message) {
	var period;
	$.gritter.add({
		title: 'Warning',
		text: message,
		time: (period !== undefined ? period : 3000),
		class_name: 'gritter-error gritter-light'
	});
}

var broadcastMessage = function (bodyMessage) {
	var message = $msg({ to: "all@broadcast.chatting", "type": "chat" }).c('body').t('[BROADCAST]' + bodyMessage).up().c('active', { xmlns: "http://jabber.org/protocol/chatstates" });
	Gab.connection.send(message);
}

var showStickyWarning = function (message, period) {
	$.gritter.removeAll();
	$.gritter.add({
		title: 'Warning!',
		text: message,
		sticky: true,
		time: '',
		class_name: 'gritter-error gritter-light'
	});
}

/**
 * Commond Dialog
 */
//Fixed width, dynamic height
var showCommonDialog = function (width, height, title, source, buttons) {
	if (width > 0) {
		$('#modalForm').attr('style', 'max-width:' + width + 'px');
	}
	$("#header-modal").html('')
	if (title != '') {
		$("#header-modal").html(title)
	}


	$("#modal-body").html('');
	let loading = '<p class="card-text placeholder-glow">' +
		'	<span class="placeholder col-7"></span>' +
		'	<span class="placeholder col-4"></span>' +
		'	<span class="placeholder col-4"></span>' +
		'	<span class="placeholder col-6"></span>' +
		'	<span class="placeholder col-8"></span>' +
		'</p>';

	// $.get(source, {}, function (htm) {


	// }, "html");
	$.ajax({
		url: source,
		type: 'GET',
		beforeSend: function (xhr) {
			$("#modal-body").html(DOMPurify.sanitize(loading));
		},
		success: function (msg) {
			$("#modal-body").html('');
			$("#modal-body").html(DOMPurify.sanitize(msg));
		}
	})
		.done(function (data) {
			if (console && console.log) {
				console.log("Sample of data:", data.slice(0, 100));
			}
		});


	var footer = $("#modal-footer");
	footer.html('');
	$.each(buttons, function (i, item) {

		if (item.label.toUpperCase() == 'CLOSE') {
			var button = $("<button type='button' class='btn btn-secondary  " + item.className + "' data-bs-dismiss='modal'>" + item.label + "</button>");

		} else {
			var button = $("<button type='button' class='btn " + item.className + "' >" + item.label + "</button>");

		}

		button.on("click", (event) => {

			if (checkValidate()) {
				try {
					item.callback();

				} catch (error) {
					console.log('error', error);
				}
				setTimeout(() => {
					myModal.hide();
				}, 200);
			}
		});

		footer.prepend(button);
	})
	changeTheme(GLOBAL_THEME_MODE);
	myModal.toggle();

};

var showFullCommonDialog = function (width, height, title, source, buttons) {
	if (width > 0) {
		// $('#modalFormFull').attr('style', 'max-width:' + width + 'px');
	}
	$("#header-modal-full").html('')
	if (title != '') {
		$("#header-modal-full").html(title)
	}


	$("#modal-body-full").html('');
	$.get(source, {}, function (htm) {
		$("#modal-body-full").html(DOMPurify.sanitize(htm));
	}, "html");
	var footer = $("#modal-footer-full");
	footer.html('');
	$.each(buttons, function (i, item) {
		let id = '';
		if (item.id) {
			id = 'id="' + item.id + '"';
		}

		if (item.label.toUpperCase() == 'CLOSE') {

			var button = $("<button type='button' " + id + " class='btn btn-secondary  " + item.className + "' data-bs-dismiss='modal'>" + item.label + "</button>");

		} else {
			var button = $("<button type='button' " + id + " class='btn " + item.className + "' >" + item.label + "</button>");

		}

		// button.on("click", (event) => {

		// 	if (checkValidate()) {
		// 		try {
		// 			item.callback();

		// 		} catch (error) {
		// 			console.log('error', error);
		// 		}
		// 		setTimeout(() => {
		// 			myFullModal.hide();
		// 		}, 200);
		// 	}
		// });

		footer.prepend(button);
	})

	changeTheme(GLOBAL_THEME_MODE);
	myFullModal.toggle();

};

var showCommonDialogpic = function (width, height, title, source, buttons) {
	//$("body").append("<div id='CommonDialog'></div>");
	//$("#CommonDialog").html(GLOBAL_MAIN_VARS["SPINNER"]);

	bootbox.dialog({
		title: title,
		onEscape: function () {
			return false;
		},
		closeButton: false,
		message: "<div id='CommonDialogpic'></div>",
		buttons: buttons
	});

	$.post(source, {}, function (htm) {
		$("#CommonDialogpic").html(DOMPurify.sanitize(htm));
	}, "html");

	$("#CommonDialogpic").parent().closest('div').parent().closest('div').parent().closest('div').parent().closest('div').css("width", width + "px");
	//$(".modal-dialog").css("width", width + "px");
};

//Not bootbox
var showCommonDialog2 = function (width, height, title, source, button) {
	//override dialog's title function to allow for HTML titles
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
		_title: function (title) {
			var $title = this.options.title || '&nbsp;'
			if (("title_html" in this.options) && this.options.title_html == true)
				title.html($title);
			else title.text($title);
		}
	}));

	this.blur();
	$("#CommonDialog2").dialog("close");
	$("#CommonDialog2").dialog("destroy");
	$("body").append("<div id='CommonDialog2'></div>");
	$("#CommonDialog2").html(GLOBAL_MAIN_VARS["SPINNER"]);

	var buttons = {};

	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}

	var dialog = $("#CommonDialog2").removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='icon-ok'></i> " + title + "</h4></div>",
		title_html: true,
		width: width,
		height: height,
		buttons: buttons,
		beforeClose: function (event, ui) {
			// alert("before close");
		},
		close: function (event, ui) {
			// alert("close");
			$(this).dialog('destroy');
			$(this).remove();
		}
	});

	$.post(source, {}, function (htm) {
		$("#CommonDialog2").html(DOMPurify.sanitize(htm));
	}, "html");
};

var showCommonDialog3 = function (width, height, title, source, buttons) {
	//$("body").append("<div id='CommonDialog3'></div>");
	//$("#CommonDialog3").html(GLOBAL_MAIN_VARS["SPINNER"]);

	bootbox.dialog({
		title: title,
		onEscape: function () {
			return false;
		},
		closeButton: false,
		message: "<div id='CommonDialog3'></div>",
		buttons: buttons
	});

	$.get(source, {}, function (htm) {
		$("#CommonDialog3").html(DOMPurify.sanitize(htm));
	}, "html");

	$("#CommonDialog3").parent().closest('div').parent().closest('div').parent().closest('div').parent().closest('div').css("width", width + "px");
	//$(".modal-dialog").css("width", width + "px");
};

var showCommonDialog3y = function (width, height, title, source, button) {
	//override dialog's title function to allow for HTML titles
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
		_title: function (title) {
			var $title = this.options.title || '&nbsp;'
			if (("title_html" in this.options) && this.options.title_html == true)
				title.html($title);
			else title.text($title);
		}
	}));

	this.blur();
	$("#CommonDialog3").dialog("close");
	$("#CommonDialog3").dialog("destroy");
	$("body").append("<div id='CommonDialog3'></div>");
	$("#CommonDialog3").html(GLOBAL_MAIN_VARS["SPINNER"]);

	var buttons = {};

	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}

	var dialog = $("#CommonDialog3").removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='icon-ok'></i> " + title + "</h4></div>",
		title_html: true,
		width: width,
		height: height,
		buttons: buttons,
		close: function (event, ui) {
			$(this).dialog('destroy');
			$(this).remove();
		}
	});

	$.post(source, {}, function (htm) {
		$("#CommonDialog3").html(DOMPurify.sanitize(htm));
	}, "html");
};


var showCommonDialog_phdp = function (width, height, title, source, button) {
	//override dialog's title function to allow for HTML titles
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
		_title: function (title) {
			var $title = this.options.title || '&nbsp;'
			if (("title_html" in this.options) && this.options.title_html == true)
				title.html($title);
			else title.text($title);
		}
	}));

	this.blur();
	$("#CommonDialog2").dialog("close");
	$("#CommonDialog2").dialog("destroy");
	$("body").append("<div id='CommonDialog2'></div>");
	$("#CommonDialog2").html(GLOBAL_MAIN_VARS["SPINNER"]);

	var buttons = {};

	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}

	var dialog = $("#CommonDialog2").removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='icon-ok'></i> " + title + "</h4></div>",
		title_html: true,
		width: width,
		height: height,
		buttons: buttons,
		beforeClose: function (event, ui) {
			//alert("before close");
		},
		close: function (event, ui) {
			//alert("close");
			$(this).dialog('destroy');
			$(this).remove();
			// alert('close');
			// console.log('close phdp');
			$.ajax({
				url: GLOBAL_MAIN_VARS["SITE_URL"] + "lelang/lelang_master/unlinkphdp",
				dataType: "json",
				type: "POST",
				async: false,
				success: function (msg) {
					console.log('unlink success');
				}
			});
		}
	});

	$.post(source, {}, function (htm) {
		$("#CommonDialog2").html(DOMPurify.sanitize(htm));
	}, "html");
};

var showImage = function (width, height, title, source, buttons) {
	bootbox.dialog({
		title: title,
		onEscape: function () { },
		message: "<div id='ImageDialog'><iframe src='http://127.0.0.1:88/bnp_loanos/assets/js/pdfjs/web/viewer.html' width='100%' height='480'></iframe></div>",
		buttons: buttons
	});

	//$.post(source, {}, function(htm)
	//{
	$("#ImageDialog").html(htm);
	//}, "html");

	$("#ImageDialog").parent().closest('div').parent().closest('div').parent().closest('div').parent().closest('div').css("width", width + "px");
};

var showImageY = function (width, height, title, source, button) {
	//override dialog's title function to allow for HTML titles
	title = "Image Viewer";
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
		_title: function (title) {
			var $title = this.options.title || '&nbsp;'
			if (("title_html" in this.options) && this.options.title_html == true)
				title.html($title);
			else title.text($title);
		}
	}));

	this.blur();
	$("#ImageDialog").dialog("close");
	$("#ImageDialog").dialog("destroy");
	$("body").append("<div id='ImageDialog'><iframe src='http://127.0.0.1:88/bnp_loanos/assets/js/pdfjs/web/viewer.html' width='100%' height='480'></iframe></div>");
	$("#ImageDialog").html(GLOBAL_MAIN_VARS["SPINNER"]);

	var buttons = {};

	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}

	var dialog = $("#ImageDialog").removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='icon-ok'></i> " + title + "</h4></div>",
		title_html: true,
		width: width,
		height: height,
		buttons: buttons,
		beforeClose: function (event, ui) {
			alert("before close");
		},
		close: function (event, ui) {
			alert("close");
			$(this).dialog('destroy');
			$(this).remove();
		}
	});

	//$.post(source,{},function(htm){
	//$("#ImageDialog").html(htm);
	//}, "html");
};

var showPdf = function (width, height, title, source, button) {
	//override dialog's title function to allow for HTML titles
	title = "PDF Viewer";
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
		_title: function (title) {
			var $title = this.options.title || '&nbsp;'
			if (("title_html" in this.options) && this.options.title_html == true)
				title.html($title);
			else title.text($title);
		}
	}));

	this.blur();
	$("#PdfDialog").dialog("close");
	$("#PdfDialog").dialog("destroy");
	$("body").append("<div id='PdfDialog'></div>");
	$("#PdfDialog").html(GLOBAL_MAIN_VARS["SPINNER"]);

	var buttons = {};

	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}

	var dialog = $("#PdfDialog").removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='icon-ok'></i> " + title + "</h4></div>",
		title_html: true,
		width: width,
		height: height,
		buttons: buttons,
		beforeClose: function (event, ui) {
			alert("before close");
		},
		close: function (event, ui) {
			alert("close");
			$(this).dialog('destroy');
			$(this).remove();
		}
	});

	$.post(source, {}, function (htm) {
		$("#PdfDialog").html(DOMPurify.sanitize(htm));
	}, "html");
};

var popUp = function (source, title, width, height, button) {
	//override dialog's title function to allow for HTML titles
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype,
		{
			_title: function (title) {
				var $title = this.options.title || '&nbsp;'
				if (("title_html" in this.options) && this.options.title_html == true)
					title.html($title);
				else title.text($title);
			}
		}));

	this.blur();
	$("#CommonDialog").dialog("close");
	$("#CommonDialog").dialog("destroy");
	$("body").append("<div id='CommonDialog'></div>");
	$("#CommonDialog").html(GLOBAL_MAIN_VARS["SPINNER"]);

	var buttons = {};

	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}

	var dialog = $("#CommonDialog").removeClass('hide').dialog({
		modal: true,
		title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='icon-ok'></i> " + title + "</h4></div>",
		title_html: true,
		width: width,
		height: height,
		buttons: buttons,
		close:
			function (event, ui) {
				$(this).dialog('destroy');
				$(this).remove();
			}
	});

	$.post(source, {}, function (htm) {
		$("#CommonDialog").html(DOMPurify.sanitize(htm));
	}, "html");
};

//unlike navButtons icons, action icons in rows seem to be hard-coded
//you can change them like this in here if you want
var updateActionIcons = function (table) {
	var replacement = {
		'ui-icon-pencil': 'icon-pencil blue',
		'ui-icon-trash': 'icon-trash red',
		'ui-icon-disk': 'icon-ok green',
		'ui-icon-cancel': 'icon-remove red'
	};
	/*
	$(table).find('.ui-pg-div span.ui-icon').each(function()
	{
		var icon = $(this);
		var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
		if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
	})
	*/
}

//replace icons with FontAwesome icons like above
var updatePagerIcons = function (table) {
	var replacement = {
		'ui-icon-seek-first': 'ace-icon fa fa-angle-double-left bigger-140',
		'ui-icon-seek-prev': 'ace-icon fa fa-angle-left bigger-140',
		'ui-icon-seek-next': 'ace-icon fa fa-angle-right bigger-140',
		'ui-icon-seek-end': 'ace-icon fa fa-angle-double-right bigger-140'
	};

	$('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function () {
		var icon = $(this);
		var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

		if ($class in replacement) icon.attr('class', 'ui-icon ' + replacement[$class]);
	})
}

var enableTooltips = function (table) {
	$('.navtable .ui-pg-button').tooltip({ container: 'body' });
	$(table).find('.ui-pg-div').tooltip({ container: 'body' });
}

var jsCurrentDate = function () {
	d = new Date();
	vDate = d.getFullYear() + "-" + addZero((d.getMonth() + 1)) + "-" + addZero(d.getDate()) + " " + addZero(d.getHours()) + ":" + addZero(d.getMinutes()) + ":" + addZero(d.getSeconds());

	return vDate;
}

/**
 * Load telephony
 */
var loadTelephony = function () {
	GLOBAL_MAIN_VARS["telephony_module"] = GLOBAL_MAIN_VARS["SITE_URL"] + "telephony";
	$("#telephony_wrapper").load("telephony_node");

	//FLASH
	// $("#telephony_wrapper").load('telephony?agent_id=' + GLOBAL_SESSION_VARS["USER_ID"] + "&ip_origin=" + GLOBAL_SESSION_VARS["IP_ORIGIN"] + "&ext=" + GLOBAL_SESSION_VARS["EXT"] + "&ip_address=" + GLOBAL_SESSION_VARS["IP_ADDRESS"]);

	//ECENTRIX6
	// $("#telephony_wrapper").load('Telephony');
};

var loadConfiguration = function () {
	$.ajax({
		type: "POST",
		url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/get_system_configuration/",
		async: false,
		dataType: "json",
		success: function (msg) {
			if (msg.success == true) {
				for (var i = 0; i < msg.data.length; i++) {
					//alert(msg.data[i].id + " : " + msg.data[i].value);
					GLOBAL_MAIN_VARS[msg.data[i].id] = msg.data[i].value;
				};
			}
			else {
				//	
			}
		},
		error: function (e) {
			console.log('error', e);
		}
	});
}

/**
 * jQuery Form Validation
 */
var passwordValidate = function (formData, jqForm, options) {
	itest++;
	//alert(itest);
	v_pass_min_length = GLOBAL_MAIN_VARS["PASSWORD_MIN_LENGTH"];
	v_pass_max_length = GLOBAL_MAIN_VARS["PASSWORD_MAX_LENGTH"];
	v_use_alphanumeric = GLOBAL_MAIN_VARS["PASSWORD_INCLD_NUMERIC"];
	v_use_alphabet = GLOBAL_MAIN_VARS["PASSWORD_INCLD_ALPHABET"];
	v_case_sensitive = GLOBAL_MAIN_VARS["PASSWORD_CASE_SENSITIVE"];
	v_use_special_char = GLOBAL_MAIN_VARS["PASSWORD_INCLD_SPECIAL_CHAR"];
	console.log("passwordValidate");
	p_new = $("#txt-security-pass-new").val()
	v_new_password_2 = $("#txt-security-pass-confirm").val()

	var pass = false;

	//Validate mandatory fields
	$('.mandatory', $(jqForm)).each(function () {
		if (!$(this).val()) {
			$(this).addClass("mandatory_invalid");
			pass = false;
		}
		else {
			$(this).removeClass("mandatory_invalid");
			pass = true;
		}
	});

	if (!pass) {
		showWarning("Please enter a value for mandatory fields.", 1500);
	}

	//Validate old password
	//if(GLOBAL_MAIN_VARS["USER_GROUP"] != "ADMIN"){
	if (GLOBAL_MAIN_VARS['CHANGE_PASS_STATE'] != "RESET") {
		v_old_password = $("#txt-security-pass-old").val();

		if (pass) {
			var is_valid = true;
			ci_url = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/check_current_password/";
			$.ajax({
				type: "POST",
				url: ci_controller,
				async: false,
				dataType: "json",
				data: { user_id: GLOBAL_SESSION_VARS["USER_ID"], current_password: $("#txt-security-pass-old").val() },
				success: function (msg) {
					if (msg.success == true) {
						pass = true;
						console.log("passwordValidate");
					}
					else {
						showWarning("Wrong old password", 1500);
						pass = false;
					}
				},
				error: function () {
					showWarning("Failed: " + ci_controller, 1500);
				}
			});
		}
	}

	if (pass) {
		ci_url = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/check_password_history/";
		$.ajax({
			type: "POST",
			url: ci_controller,
			async: false,
			dataType: "json",
			data: { user_id: GLOBAL_SESSION_VARS["USER_ID"], current_password: $("#txt-security-pass-old").val() },
			success: function (msg) {
				if (msg.success == true) {
					pass = true;
				}
				else {
					showWarning(msg.message, 1500);
					pass = false;
				}
			},
			error: function () {
				showWarning("Failed: " + ci_controller, 1500);
			}
		});
	}

	//cannot use  old password
	if (pass) {
		if (p_new == $("#txt-security-pass-old").val()) {
			$("#txt-security-pass-new").focus();
			showWarning("Cannot use old password!", 1500);
			pass = false;
		}
		else {
			pass = true;
		}
	}

	//Validate enter and re-enter password
	if (pass) {
		if (p_new != v_new_password_2) {
			$("#txt-security-pass-new").focus();
			showWarning("Confirmation password unequal!", 1500);
			pass = false;
		}
		else {
			pass = true;
		}
	}

	//Validate password length
	if (pass) {
		pass_length = p_new.length;
		//Jika kurang dari 7 digit
		if (pass_length < v_pass_min_length) {
			$("#txt-security-pass-new").focus();
			showWarning("Password can not less than " + v_pass_min_length + " digit characters!", 1500);
			pass = false;
		}
		else {
			pass = true;
		}
	}

	if (pass) {
		pass_length = p_new.length;
		//Jika kurang dari 7 digit
		if (pass_length > v_pass_max_length) {
			$("#txt-security-pass-new").focus();
			showWarning("Password can not more than " + v_pass_max_length + " digit characters!", 1500);
			pass = false;
		}
		else {
			pass = true;
		}
	}

	//Validate numeric
	if (pass) {
		if (v_use_alphanumeric == "Y") {
			/*
			if(p_new.match(/[^0-9a-z]/i)){
				alert("Only letters and digits allowed!");
				pass = false;  
			}
			else 
			*/
			/*			if(!p_new.match(/\d/)){
							showWarning("At least one numeric required!", 1500);	
							pass = false;  
						}
						else if(!p_new.match(/[a-z]/i)){
							showWarning("At least one letter required!", 1500); 
							pass = false;  
						}
						else{
							pass = true;
						}
			*/
			if (!p_new.match(/\d/)) {
				showWarning("At least one numeric required!", 1500);
				pass = false;
			}
			else {
				pass = true;
			}
		}
	}

	//Validate alphabet
	if (pass) {
		if (v_use_alphabet == "Y") {
			/*
			if(p_new.match(/[^0-9a-z]/i)){
				alert("Only letters and digits allowed!");
				pass = false;  
			}else 
			*/
			if (!p_new.match(/[a-z]/i)) {
				showWarning("At least one letter required!", 1500);
				pass = false;
			}
			else {
				pass = true;
			}
		}
	}

	//Validate case sensitive
	if (pass) {
		if (v_case_sensitive == "Y") {
			if (!p_new.match(/[a-z]/)) {
				showWarning("At least one lower case required!", 1500);
				pass = false;
			}
			else if (!p_new.match(/[A-Z]/)) {
				showWarning("At least one upper case required!", 1500);
				pass = false;
			}
			else {
				pass = true;
			}
		}
	}

	//Validate special char
	if (pass) {
		if (v_use_special_char == "Y") {
			if (!p_new.match(/[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/\@\#]/)) {
				showWarning("At least one special character required!", 1500);
				pass = false;
			}
			else {
				pass = true;
			}
		}
	}

	return pass;
}

var showBillingPrintDialog2 = function (width, height, title, source, button) {
	this.blur();
	//$("#PrintDialog").dialog("close");
	$("body").append("<div id='PrintDialog2' title='" + title + "'></div>");
	$("#PrintDialog2").html(GLOBAL_MAIN_VARS["progress_indicator"]);

	var buttons = {
		Close: function () {
			$(this).dialog('destroy');
			$(this).remove();
		}

	}
	if (button) {
		for (i in button) {
			buttons[i] = button[i];
		}
	}
	$("#PrintDialog2").dialog({
		bgiframe: true,
		autoOpen: true,
		width: width,
		height: height,
		resizable: false,
		modal: true,
		position: 'center',
		buttons: buttons,
		open: function (event, ui) {
			$(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').hide();
		},
		close: function (event, ui) {
			$(this).dialog('destroy');
			$(this).remove();
		},
		beforeclose: function () {
			//$("#ui-datepicker-div").hide();
		}
	});
	$.post(source, {}, function (htm) {
		$("#PrintDialog2").html(DOMPurify.sanitize(htm));
	}, "html");
};

var changeSecurityPass = function (cause) {
	v_pass_min_length = GLOBAL_MAIN_VARS["PASSWORD_MIN_LENGTH"];
	v_pass_max_length = GLOBAL_MAIN_VARS["PASSWORD_MAX_LENGTH"];
	v_use_alphanumeric = GLOBAL_MAIN_VARS["PASSWORD_INCLD_NUMERIC"];
	v_use_alphabet = GLOBAL_MAIN_VARS["PASSWORD_INCLD_ALPHABET"];
	v_case_sensitive = GLOBAL_MAIN_VARS["PASSWORD_CASE_SENSITIVE"];
	v_use_special_char = GLOBAL_MAIN_VARS["PASSWORD_INCLD_SPECIAL_CHAR"];
	console.log("changeSecurityPass");
	var buttons = {
		"success": {
			"label": "<i class='icon-ok'></i> Apply",
			"className": "btn-sm btn-success",
			"callback": function () {
				p_old = $("#txt-security-pass-old").val();
				p_new = $("#txt-security-pass-new").val()
				p_con = $("#txt-security-pass-confirm").val()

				var pass = false;

				if (p_old == p_new) {
					showWarning("New Password should not equal with old password", 1500);
					pass = false;
					return pass

				}
				//Validate mandatory fields
				if (!$("#txt-security-pass-old").val()) {
					$("#txt-security-pass-old").addClass("mandatory_invalid");
					showWarning("Please enter a value for mandatory fields.", 1500);
					pass = false;
				}
				else {
					$("#txt-security-pass-old").removeClass("mandatory_invalid");
					pass = true;

					if (!$("#txt-security-pass-new").val()) {
						$("#txt-security-pass-new").addClass("mandatory_invalid");
						showWarning("Please enter a value for mandatory fields.", 1500);
						pass = false;
					}
					else {
						$("#txt-security-pass-new").removeClass("mandatory_invalid");
						pass = true;

						if (!$("#txt-security-pass-confirm").val()) {
							$("#txt-security-pass-confirm").addClass("mandatory_invalid");
							showWarning("Please enter a value for mandatory fields.", 1500);
							pass = false;
						}
						else {
							$("#txt-security-pass-confirm").removeClass("mandatory_invalid");
							pass = true;
						}
					}
				}

				//Validate current password
				if (GLOBAL_MAIN_VARS['CHANGE_PASS_STATE'] != "RESET") {
					if (pass) {
						var is_valid = true;

						ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/check_current_password/";
						$.ajax({
							type: "POST",
							url: ci_controller,
							async: false,
							dataType: "json",
							data: { user_id: GLOBAL_SESSION_VARS["USER_ID"], current_password: p_old },
							success: function (msg) {
								if (msg.success == true) {
									pass = true;
									console.log("changeSecurityPassResponse");
								}
								else {
									showWarning("Wrong old password", 1500);
									pass = false;
								}
							},
							error: function () {
								showWarning("Failed:  " + ci_controller, 1500);
							}
						});
					}
				}

				if (pass) {
					ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/check_password_history/";
					$.ajax({
						type: "POST",
						url: ci_controller,
						async: false,
						dataType: "json",
						data: { user_id: GLOBAL_SESSION_VARS["USER_ID"], current_password: p_new },
						success: function (msg) {
							if (msg.success == true) {
								pass = true;
							}
							else {
								showWarning(msg.message, 1500);
								pass = false;
							}
						},
						error: function () {
							showWarning("Failed: " + ci_controller, 1500);
						}
					});
				}

				//Validate confirmation password
				if (pass) {
					if (p_new != p_con) {
						$("#txt-security-pass-new").focus();
						showWarning("Confirmation password not match", 1500);
						pass = false;
					}
					else {
						pass = true;
					}
				}

				//Validate password length
				if (pass) {
					pass_length = p_new.length;
					//Jika kurang dari 7 digit
					if (pass_length < v_pass_min_length) {
						$("#txt-security-pass-new").focus();
						showWarning("Passwords can not less than " + v_pass_min_length + " digit characters!", 1500);
						pass = false;
					}
					else {
						pass = true;
					}
				}

				if (pass) {
					pass_length = p_new.length;
					//alert(v_pass_max_length);
					//Jika kurang dari 7 digit
					if (pass_length > v_pass_max_length) {
						$("#txt-security-pass-new").focus();
						showWarning("Password can not more than " + v_pass_max_length + " digit characters!", 1500);
						pass = false;
					}
					else {
						pass = true;
					}
				}

				//Validate numeric
				if (pass) {
					if (v_use_alphanumeric == "Y") {
						/*
						if(p_new.match(/[^0-9a-z]/i)){
							alert("Only letters and digits allowed!");
							pass = false;  
						}else 
						*/
						/*if(!p_new.match(/\d/)){
							showWarning("At least one numeric required!", 1500);	
							pass = false;  
						}else if(!p_new.match(/[a-z]/i)){
							showWarning("At least one letter required!", 1500); 
							pass = false;  
						}else{
							pass = true;
						}
						*/
						if (!p_new.match(/\d/)) {
							showWarning("At least one numeric required!", 1500);
							pass = false;
						}
						else {
							pass = true;
						}
					}
				}

				//Validate alphabet
				if (pass) {
					if (v_use_alphabet == "Y") {
						/*
						if(p_new.match(/[^0-9a-z]/i)){
							alert("Only letters and digits allowed!");
							pass = false;  
						}else 
						*/
						if (!p_new.match(/[a-z]/i)) {
							showWarning("At least one letter required!", 1500);
							pass = false;
						}
						else {
							pass = true;
						}
					}
				}

				//Validate case sensitive
				if (pass) {
					if (v_case_sensitive == "Y") {
						if (!p_new.match(/[a-z]/)) {
							showWarning("At least one lower case required!", 1500);
							pass = false;
						}
						else if (!p_new.match(/[A-Z]/)) {
							showWarning("At least one upper case required!", 1500);
							pass = false;
						}
						else {
							pass = true;
						}
					}
				}

				//Validate special char
				if (pass) {
					if (v_use_special_char == "Y") {
						if (!p_new.match(/[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/\@\#]/)) {
							showWarning("At least one special character required!", 1500);
							pass = false;
						}
						else {
							pass = true;
						}
					}
				}
				/*
				ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/change_security_pass";
				var options = {
					url : ci_controller,
					type: "POST",
					success	: function(msg){
						if(msg.success == true){
							showInfo("Password sudah ter-update.", 1500);
						}else{
							showWarning("Password gagal ter-update.", 1500);
						}
					},
					error: function(){
						showWarning("Failed: " + url, 1500);
					}
				};
				
				$('#form-change-security-pass').bind('submit', function(){
					$(this).ajaxSubmit(options);
					return false; // <-- important!
				});
				
				$("#form-change-security-pass").submit();
				*/
				if (pass) {
					ci_controller = GLOBAL_MAIN_VARS["SITE_URL"] + "user_and_group/change_security_pass";
					$.ajax({
						type: "POST",
						url: ci_controller,
						async: false,
						dataType: "json",
						data: { user_id: GLOBAL_SESSION_VARS["USER_ID"], new_password: p_new },
						success: function (msg) {
							if (msg.success == true) {
								showInfo("Password sudah ter-update.", 1500);
								pass = true;
								GLOBAL_MAIN_VARS["PASSWORD_AGE"] = -10;
								GLOBAL_MAIN_VARS["FIRST_LOGIN"] = 1;
							}
							else {
								showWarning("Wrong old password", 1500);
								pass = false;
							}
						},
						error: function () {
							showWarning("Failed: " + ci_controller, 1500);
						}
					});
				}

				return pass;
			}
		},
		"button": {
			"label": "Cancel",
			"className": "btn-sm",
			"callback": function () {
				if ((GLOBAL_MAIN_VARS["FIRST_LOGIN"] == 0) || (cause == "EXPIRED")) {
					$(location).attr("href", "login/logout");
				}
			}
		}
	}

	if (cause == "DEFAULT") {
		// showWarning("Please change your default password.", 1500);
	}
	else if (cause == "EXPIRED") {
		// showWarning("The validity period of your password has ended, please change your password.", 1500);
		// showCommonDialog(500, 500, 'Change Password', GLOBAL_MAIN_VARS["SITE_URL"] + 'user_and_group/change_security_pass_form/', buttons);
	}
	else if (cause == "FIRST_LOGIN") {
		// showWarning("first login on system, please change your password.", 1500);
		// showCommonDialog(500, 500, 'Change Password', GLOBAL_MAIN_VARS["SITE_URL"] + 'user_and_group/change_security_pass_form/', buttons);
		//Tambahan Iqbal 21082015 17:05
	}
	else if (cause == "MANUAL") {
		showCommonDialog(500, 500, 'Change Password', GLOBAL_MAIN_VARS["SITE_URL"] + 'user_and_group/change_security_pass_form/', buttons);
	}
	//End Iqbal

	//showCommonDialog(500, 500, 'Change Password', GLOBAL_MAIN_VARS["SITE_URL"] + 'user_and_group/change_security_pass_form/', buttons);	
};

function getAge(dateString) {
	vDay = (dateString.substr(0, 2));
	vMonth = (dateString.substr(3, 2));
	vYear = (dateString.substr(6, 4));

	var today = new Date();
	var birthDate = new Date(vYear, vMonth, vDay);
	//var birthDate = new Date(vMonth + "/" + vDay + "/" + vYear).getTime();
	var age = today.getFullYear() - birthDate.getFullYear();
	var m = today.getMonth() - birthDate.getMonth();
	if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
		age--;
	}
	return age;
}

function getAge2(dateString, ageOutput) {
	//ageOutput = "d", "m" , "y"
	vDay = (dateString.substr(0, 2));
	vMonth = (dateString.substr(3, 2));
	vYear = (dateString.substr(6, 4));

	var birthdate = new Date(vMonth + "/" + vDay + "/" + vYear).getTime();
	var now = new Date().getTime();
	// now find the difference between now and the birthdate
	var n = (now - birthdate) / 1000;

	if (n < 604800) { // less than a week
		var day_n = Math.floor(n / 86400);
		return day_n + ' day' + (day_n > 1 ? 's' : '');
	}
	else if (n < 2629743) {  // less than a month
		var week_n = Math.floor(n / 604800);
		return week_n + ' week' + (week_n > 1 ? 's' : '');
	}
	else if (n < 63113852) { // less than 24 months
		var month_n = Math.floor(n / 2629743);
		return month_n + ' month' + (month_n > 1 ? 's' : '');
	}
	else {
		var year_n = Math.floor(n / 31556926);
		return year_n + ' year' + (year_n > 1 ? 's' : '');
	}
}

function getAgeRemnant(dateString) {
	vDay = (dateString.substr(0, 2));
	vMonth = (dateString.substr(3, 2));
	vYear = (dateString.substr(6, 4));

	var today = new Date();
	var futureDate = new Date(vYear, vMonth, vDay);
	//var birthDate = new Date(vMonth + "/" + vDay + "/" + vYear).getTime();
	var age = futureDate.getFullYear() - today.getFullYear();
	var m = futureDate.getMonth() - today.getMonth();
	if (m < 0 || (m === 0 && futureDate.getDate() < today.getDate())) {
		age--;
	}
	return age;
}

var pmtx = function (r, nper, pv, fv, type) {
	pmt = r / (Math.pow(1 + r, nper) - 1) * -(pv * Math.pow(1 + r, nper) + fv);
	if (type == 1) {
		pmt = pmt / (1 + r);
	}
	return pmt;
};

jQuery(function ($) {
	if ($('body').find('#module_telephony').length != 0) {
		$("#module_telephony").load('telephony');
	}

	$("#knowledge_button").click(function () {
		window.open(GLOBAL_MAIN_VARS["BASE_URL"] + "knowledge/");
	});

	$("#application_log_button").click(function () {
		loadMenu('Application Log', 'reports/application_log');
	});

	$("#change_security_pass_button").click(function () {
		changeSecurityPass("MANUAL");
	});

	$("#logout_button").click(function () {


	});




	//Load System Configuration
	loadConfiguration();

	//Check default password and password age
	if (GLOBAL_MAIN_VARS["PASSWORD_STATUS"] == "default") {
		changeSecurityPass("DEFAULT");
		GLOBAL_MAIN_VARS['CHANGE_PASS_STATE'] = "";
	}
	else if (GLOBAL_MAIN_VARS["FIRST_LOGIN"] == 0) {
		changeSecurityPass("FIRST_LOGIN");
		GLOBAL_MAIN_VARS['CHANGE_PASS_STATE'] = "";
	}
	else if ((parseInt(GLOBAL_MAIN_VARS["PASSWORD_AGE"]) > parseInt(GLOBAL_MAIN_VARS["PASSWORD_EXPIRE_DURATION"])) || (GLOBAL_MAIN_VARS["PASSWORD_AGE"] == '')) {
		changeSecurityPass("EXPIRED");
		GLOBAL_MAIN_VARS['CHANGE_PASS_STATE'] = "";
	}
});

var predialAction = function (callerId, number, customerData, recordingId) {
	console.log('========predial====================');
	vAutoDial = 'true';
	var preNumber = number.replace('1850062', '0');
	number = preNumber;

	GLOBAL_MAIN_VARS['TELEPHONY_CALLER_ID'] = callerId;
	GLOBAL_MAIN_VARS['CLASS_ID'] = customerData['class_id'];
	GLOBAL_MAIN_VARS['ACTIVE_CALL_NO'] = number;
	GLOBAL_MAIN_VARS['PHONE_TYPE'] = customerData['phone_type'];

	console.log('arrCustomerData', customerData);

	$("#DialedPhoneNumber").val(number);

	loadDataCustomer(customerData['contract_number'], null);

}

function show_current_handling() {

	if (GLOBAL_MAIN_VARS['CONTRACT_NUMBER'] == '') {
		showWarning('current handling is empty', 1000);
	} else {
		loadDataCustomer(GLOBAL_MAIN_VARS['CONTRACT_NUMBER']);
	}
}


function loadDataCustomer(cm_card_nmbr) {
	$("#btnGetAccount").attr('disabled', true).html('GET ACCOUNT');
	$("#btnStopGetAccount").hide();

	GLOBAL_MAIN_VARS['CONTRACT_NUMBER'] = cm_card_nmbr;


	if (GLOBAL_MAIN_VARS['CONTRACT_NUMBER'] != "") {
		$("#lastAccountHandling").show(1000);
		console.log('show lastAccountHandling');
	}

	var buttons = {
		"button":
		{
			"label": "Close",
			"className": "btn-sm btn-close",
			"callback": function () {
				console.log('back');
				$.ajax({
					url: GLOBAL_MAIN_VARS["SITE_URL"] + "detail_account/update_account_handling?contract_number=" + cm_card_nmbr,
					type: "get",
					success: function (msg) {

					},
					dataType: 'json',
				});

				if (GLOBAL_MAIN_VARS['CONTRACT_NUMBER'] == '') {
					$("#lastAccountHandling").hide(1000);
				}
				try {
					if (TELEPHONY_CURRENT_STATUS != 'AGENT_TALKING') {
						GLOBAL_MAIN_VARS['ACTIVE_CALL_NO'] = '';
					}
				} catch (error) {
					GLOBAL_MAIN_VARS['ACTIVE_CALL_NO'] = '';
				}


			}
		}
	}
	showFullCommonDialog(window.innerWidth, window.innerHeight, 'Followup', GLOBAL_MAIN_VARS["SITE_URL"] + 'detail_account/detail_account?account_id=' + cm_card_nmbr + "&cm_card_nmbr=" + cm_card_nmbr, buttons);
}


function checkValidate() {
	var forms = document.querySelectorAll('.needs-validation')
	let valid = true;
	// Loop over them and prevent submission
	Array.prototype.slice.call(forms)
		.forEach(function (form) {

			if (!form.checkValidity()) {

				valid = false;
				console.log('form', form);

				try {
					$('.modal').animate({
						scrollTop: $('#9C').offset().top
					}, 1000);

				} catch (error) {

				}

			}

			form.classList.add('was-validated')

		})

	return valid;
}

// ttl in second
function setWithExpiry(key, value, ttl) {
	const now = new Date()
	let newttl = ttl * 1000;
	// `item` is an object which contains the original value
	// as well as the time when it's supposed to expire
	const item = {
		value: value,
		expiry: now.getTime() + newttl,
	}
	localStorage.setItem(key, JSON.stringify(item))
}

function getWithExpiry(key) {
	const itemStr = localStorage.getItem(key)
	// if the item doesn't exist, return null
	if (!itemStr) {
		return null
	}
	const item = JSON.parse(itemStr)
	const now = new Date()
	// compare the expiry time of the item with the current time
	if (now.getTime() > item.expiry) {
		// If the item is expired, delete the item from storage
		// and return null
		localStorage.removeItem(key)
		return null
	}
	return item.value
}


try {
	//Sesssion expire
	$(document).idle({
		onIdle: function () {
			if (GLOBAL_MAIN_VARS["SESSION_EXPIRE"] != 0) {
				//window.location = "login/logout";
				location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "login/logout";
			}
		},
		idle: GLOBAL_MAIN_VARS["SESSION_EXPIRE"] * 1000 * 60
	});

} catch (error) {

}

// setInterval(() => { debugger; }, 1000); 