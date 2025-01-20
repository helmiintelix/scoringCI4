var menu_arr = new Array();
var JSON_MENU = new Array();
var loadMenu = function (title, link, menu_id) {
	GLOBAL_MAIN_VARS["CURRENT_MENU"] = title;

	let placeholder = ' <p class="placeholder-glow">' +
		'<span class="placeholder col-7"></span>' +
		'<span class="placeholder col-4"></span>' +
		'<span class="placeholder col-4"></span>' +
		'<span class="placeholder col-6"></span>' +
		'<span class="placeholder col-8"></span>'
	'</p>';
	$("#admin-wrapper").html(generatePlaceholders(10));
	$("#page-title").fadeOut(function () {
		$("#current-page").html(title);
		$("#page-title").html('');
		$(this).append('<li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door-fill"></i></a></li>');
		let menu = JSON_MENU[menu_id];
		if (menu.menu_1 != null) {
			$(this).append('<li class="breadcrumb-item"><a href="#">' + menu.menu_1 + '</a></li>');
		}
		if (menu.menu_2 != null) {
			$(this).append('<li class="breadcrumb-item"><a href="#">' + menu.menu_2 + '</a></li>');
		}
		if (menu.menu_3 != null) {
			$(this).append('<li class="breadcrumb-item"><a href="#">' + menu.menu_3 + '</a></li>');
		}
		$("#page-title").fadeIn();
	})



	try {

		$("#admin-wrapper").html(GLOBAL_MAIN_VARS["SPINNER"]).load(GLOBAL_MAIN_VARS["BASE_URL"] + '/' + link, function (responseTxt, statusTxt, xhr) {

			if (statusTxt == "success") {
				changeTheme(GLOBAL_THEME_MODE);
				$("#admin-wrapper").show();
			}
			else if (statusTxt == "error") {
				$("#admin-wrapper").html('<i>something wrong</i>');
			}

		})
	} catch (error) {
		console.log('error', error);
	}



	if (title != 'Agent Monitoring') {
		clearInterval(GLOBAL_INTERVAL);
	}
}




var loadHome = function (title, link) {
	$("#page-title").fadeOut(function () {
		$("#page-title").text(title).fadeIn();
	})

	$("#main-wrapper").slideUp("fast", function () {
		$(".nva-list > li").removeClass("active")
		$("#main-wrapper").html(GLOBAL_MAIN_VARS["SPINNER"]).load('/' + link, function () {
			$("#main-wrapper").slideDown("slow");
		})
	})
}

function getMenu() {
	$.ajax({
		url: 'Main/setMenu',  // URL yang akan diakses
		type: 'GET',  // Metode request
		cache: true,
		dataType: 'json',  // Data yang diharapkan dari server adalah JSON
		success: function (response) {
			// Fungsi callback yang dipanggil jika request berhasil
			if (response) {
				// Lakukan sesuatu dengan data yang diterima, misal menampilkan menu
				menu_arr = response.MENU_ARR;
				JSON_MENU = response.JSON_MENU;
				generate_menu(response.MENU_ARR);
				changeTheme(GLOBAL_THEME_MODE);
			} else {
				console.error('Data menu tidak tersedia');
			}
		},
		error: function (xhr, status, error) {
			// Fungsi callback yang dipanggil jika terjadi error
			console.error('Error:', error);
		}
	});
}

function generate_menu(menu_arrx) {
	var html = '';
	$.each(menu_arrx, (i, val) => {
		let onclick = '';
		let href = val['menu_desc'].replaceAll(' ', '').replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, '');
		if (val['url'] != '#') {
			onclick = 'onclick="loadMenu(\'' + val["menu_desc"] + ' \' ,\'' + val['url'] + '\',\'' + val['menu_id'] + '\' )"';
		}

		html += '<li class="nav-heading ">';
		html += '<span> ' + val['menu_desc'] + ' </span>';
		html += '</li>';
		// html += '<a data-bs-theme="dark" class="nav-link collapsed" data-bs-target="#' + href + '" data-bs-toggle="collapse" href="#" ' + onclick + ' >';
		// html += '</a>';
		if (val['children']) {
			let onclick = '';
			html += ' <li id="' + href + '" class="nav-content collapse">';
			$.each(val['children'], (ii, vall) => {
				let onclick = '';
				// console.log('emnu vall menu',vall);
				var href_sub = vall['menu_desc'].replaceAll(' ', '').replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, '');
				if (vall['url'] != '#') {
					onclick = 'onclick="loadMenu(\'' + vall["menu_desc"] + ' \' ,\'' + vall['url'] + '\',\'' + vall['menu_id'] + '\' )"';
				}

				html += '<li class="nav-item">';
				html += '<a class="nav-link collapsed" data-bs-target="#' + href_sub + '" data-bs-toggle="collapse" ' + onclick + ' >';
				// if (vall["icon"] == '') {
				// 	html += '<i class="bi bi-list-ul"></i><span>' + vall["menu_desc"] + '</span>';
				// } else {
				// 	html += '<i class="bi bi-list-ul"></i><span>' + vall["menu_desc"] + '</span>';
				// }
				html += '<i class="bi bi-list-ul"></i><span>' + vall["menu_desc"] + '</span>';

				if (vall['children']) {
					html += '<i class="bi bi-chevron-down ms-auto" style="font-size: 8px;"></i>';
				}
				html += ' </a>';
				if (vall['children']) {
					html += ' <ul id="' + href_sub + '" class="nav-content collapse" style="margin-left: 0px;">';
					$.each(vall['children'], (iii, valll) => {
						let onclick = '';
						if (valll['url'] != '#') {
							onclick = 'onclick="loadMenu(\'' + valll["menu_desc"] + ' \' ,\'' + valll['url'] + '\',\'' + valll['menu_id'] + '\' )"';
						}
						html += '<li>';
						html += '<a class="nav-link"  href="#" ' + onclick + ' >';
						html += '<i class="bi bi-list-ul"></i><span>' + valll["menu_desc"] + '</span>';
						html += '</a>';
						html += '</li>';
					})
					html += ' </ul>';
				}


			})

			html += '</li>';
		}




	})

	$("#sidebar-nav").html(html);
	changeTheme(GLOBAL_THEME_MODE);
}

function cari_menu(param) {
	findMenu(param.value);
}

function findMenu(param) {

	let tmp = menu_arr;
	const options = {
		threshold: 0.1,
		ignoreLocation: true,
		location: 1,
		keys: ['menu_desc', 'children.menu_desc', ['children.menu_desc']]

	}

	// Create the Fuse index
	const myIndex = Fuse.createIndex(options.keys, tmp)
	// initialize Fuse with the index
	var fuse = new Fuse(tmp, options, myIndex)

	// const fuse = new Fuse(menu_arr, options)

	var result = fuse.search(param);


	var arr = new Array();
	$.each(result, function (i, val) {
		arr.push(val.item);
		if (val.item.children) {
			fuse = new Fuse(val.item.children, options, myIndex)
			var result2 = fuse.search(param);

			// if(result2.length>0){
			//   $.each(result2,(ii,vall)=>{
			//     if(vall.item){
			//       if(arr[i]['children']){
			//         arr[i]['children'][ii] = vall.item;
			//        } 
			//       console.log('vall',vall.item);
			//     }
			//   })
			// }

		}
	});
	console.log('arr', arr);
	if (param == '') {
		generate_menu(menu_arr);
	} else {

		generate_menu(arr);
	}
}

jQuery(function ($) {
	getMenu();
	$('#menuMain .submenu > li').click(function () {
		var list = $("#menuMain").find('li');
		$(list.get()).each(function () {
			$(this).removeClass('active');
		});

		$(this).addClass("active");
	});

	//Main Screen Init
	if (GLOBAL_SESSION_VARS["LEVEL_GROUP"] == "ADMIN" || GLOBAL_SESSION_VARS["LEVEL_GROUP"] == "ROOT") {
		//	loadMenu('User Management','user_and_group/user_management');
	} else if (GLOBAL_SESSION_VARS["LEVEL_GROUP"] == "USER") {
		//loadMenu('OCR Verification','ocr_verification/raw_data_list');
	}
});

try {
	//Sesssion expire
	$(document).idle({
		onIdle: function () {
			if (GLOBAL_MAIN_VARS["SESSION_EXPIRE"] != 0) {
				window.location = "login/logout";
				//alert(GLOBAL_MAIN_VARS["SESSION_EXPIRE"]);
			}
		},
		idle: GLOBAL_MAIN_VARS["SESSION_EXPIRE"] * 1000 * 60
	});
} catch (error) {
	(function () {
		var minutes = true; // change to false if you'd rather use seconds
		var interval = minutes ? 60000 : 1000;
		var IDLE_TIMEOUT = GLOBAL_MAIN_VARS["SESSION_EXPIRE"]; // 15 minutes in this example
		var idleCounter = 0;

		document.onmousemove = document.onkeypress = function () {
			idleCounter = 0;
		};

		window.setInterval(function () {
			if (++idleCounter >= IDLE_TIMEOUT) {
				location.href = GLOBAL_MAIN_VARS["SITE_URL"] + "login/logout";
			}
		}, interval);
	}());
}

function generateRandomPlaceholder() {
	const placeholders = [
		'<div class="placeholder-glow">' +
		'<span class="placeholder col-7"></span>' +
		'<span class="placeholder col-4"></span>' +
		'<span class="placeholder col-1"></span>' +
		'<span class="placeholder col-6"></span>' +
		'<span class="placeholder col-6"></span>' +
		'</div>',
		'<div class="placeholder-glow">' +
		'<h1 class="placeholder col-6"></h1>' +
		'<p class="placeholder col-6"></p>' +
		'<p class="placeholder col-8"></p>' +
		'<p class="placeholder col-4"></p>' +
		'</div>'
	];

	// Pilih placeholder secara acak
	const randomIndex = Math.floor(Math.random() * placeholders.length);
	return placeholders[randomIndex];
}

// Fungsi untuk membuat banyak placeholder
function generatePlaceholders(count) {
	let output = '';
	for (let i = 0; i < count; i++) {
		output += generateRandomPlaceholder();
	}
	return output;
}



$("body").click(function () {
	console.log('admin_main.js');
	if (GLOBAL_SESSION_VARS["SWAL_LOGOUT"] == 0) {
		check_session();
	}
});
