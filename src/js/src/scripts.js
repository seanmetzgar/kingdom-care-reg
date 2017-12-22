var leapYear = function(year) {
  return ((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0);
};

var range = function (min, max, increment) {
	var array = [];
	increment = typeof increment === "number" && Number.isInteger(increment) ? increment : 1;
	for (min = min; min <= max; min = min + increment) {
		array.push(min.toString());
	}
	return array;
};

var log = function (msg) {
	var host = window.location.hostname;

	if (typeof msg !== "undefined" && (host.includes("dev.") || host.includes("localhost"))) {
	    console.log(msg);
	} // Only log on local & dev
};

var initForm = function() {
	var $birthMonth = $("#form-birth-month").empty();
	var $birthDay = $("#form-birth-day").empty();
	var $birthYear = $("#form-birth-year").empty();

	$("<option default>Month</option>").appendTo($birthMonth).prop("default", true).prop("disabled", true);
	$("<option default>Day</option>").appendTo($birthDay).prop("default", true).prop("disabled", true);
	$("<option default>Year</option>").appendTo($birthYear).prop("default", true).prop("disabled", true);

	$.each(selectData.month, function(key, value) {
		$("<option></option>").appendTo($birthMonth).html(value).attr("value", key);
	});

	$.each(selectData.day, function(key, value) {
		$("<option></option>").appendTo($birthDay).html(value).attr("value", value);
	});

	$.each(selectData.year, function(key, value) {
		$("<option></option>").appendTo($birthYear).html(value).attr("value", value);
	});

	$("#form-payment-structure").on("change blur", function (e) {
		var $this = $(this);
		var structure = $this.val();
		$(".payment-rate-form").hide(0).find("input").prop("disabled", true);
		$("#payment-" + structure).show(0).find("input").prop("disabled", false);
	}).trigger("change");

	$("#form-birth-month, #form-birth-year").on("change blur", function (e) {
		month = $("#form-birth-month").val();
		year = $("#form-birth-year").val();
		updateBirthDay(month, year);
	});

	$("#form-years-experience").slider({
		"tooltip": "hide",
		"ticks": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
		"ticks_labels": ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10+"],
		"value": 0
	});

	$("#form-travel-distance").slider({
		"tooltip": "hide",
		"ticks": [0, 5, 10, 15, 20],
		"ticks_labels": ["0", "5", "10", "15", "20+"],
		"value": 10
	});

	$("#form-transport-capacity").slider({
		"tooltip": "hide",
		"ticks": [0, 1, 2, 3, 4, 5, 6],
		"ticks_labels": ["0", "1", "2", "3", "4", "5", "6+"],
		"value": 0
	});

	$("#form-number-children").slider({
		"tooltip": "hide",
		"ticks": [1, 2, 3, 4, 5, 6],
		"ticks_labels": ["1", "2", "3", "4", "5", "6+"],
		"value": 1
	});

	$(".registration-form-view").find(".btn-prev").on("click", function (e) {
		var $this = $(this);
		var	$appView = $this.parents(".registration-form-view");
		var appView = $appView.data("form-step");

		if (appView > 1) {
			currentFormStep = appView - 1;
			$appView.hide(0);
			$(".registration-form-view[data-form-step=" + currentFormStep + "]").show(0);
			$("input[data-slider-id]").slider("relayout");
		}
	});

	$(".registration-form-view").find(".btn-next").on("click", function (e) {
		var $this = $(this);
		var	$appView = $this.parents(".registration-form-view");
		var appView = $appView.data("form-step");

		if (appView < maxFormStep) {
			currentFormStep = appView + 1;
			$appView.hide(0);
			$appView = $(".registration-form-view[data-form-step=" + currentFormStep + "]").show(0);
			$("input[data-slider-id]").slider("relayout");
		}
	});

	$("#registration-form").on("submit", function(e) {
		if (this.checkValidity() === false) {
			e.preventDefault();
			e.stopPropagation();
		}
		$(this).addClass("was-validated");
	});

	$(".image-preview").on("change", function() { 
		readURL(this);
	});
};

var updateBirthDay = function(month, year) {
	var days = [];
	var shortMonths = [4, 6, 9, 11];
	var $birthDay = $("#form-birth-day");
	var currentBirthDay = $birthDay.val();
	var tempOption = null;

	if (typeof month === "string" && !isNaN(Number(month))) {
		month = Number(month);
	} else { month = new Date().getMonth() + 1; }

	if (typeof year === "string" && !isNaN(Number(year))) {
		year = Number(year);
	} else { year = new Date().getFullYear(); }

	currentBirthDay = (!isNaN(Number(currentBirthDay))) ? Number(currentBirthDay) : 0;

	days = (month === 2) ? 
		((leapYear(year)) ? range(1, 29) : range(1, 28)) :
		((shortMonths.indexOf(month) !== -1) ? range(1, 30) : range(1, 31));

	currentBirthDay = (days.indexOf(currentBirthDay.toString()) === -1) ? false : currentBirthDay.toString();
	
	$birthDay.empty();
	tempOption = $("<option default>Day</option>").appendTo($birthDay).prop("default", true).prop("disabled", true);
	$.each(days, function(key, value) {
		tempOption = $("<option></option>").appendTo($birthDay).html(value).attr("value", value);
		if (currentBirthDay === value) {
			tempOption.prop("selected", true);
		}

	});
};

var setView = function(view, id) {
	id = (typeof id === "string") ? id : null;
	var defaultViewTitle = "Kingdom Care";
	var viewTitle = null;
	var viewPath = hasher.getHash();
	viewPath = viewPath.length > 0 ? "/#/" + viewPath : viewPath;

	if (typeof view === "string" && id !== null) {
		view = view + ":" + id;
	}

	if (typeof view !== "undefined" && typeof views[view] !== "undefined") {
		$(".app-view").fadeOut();
		if (typeof viewsContent[view] !== "undefined") {
			views[view].empty().load(viewsContent[view], function() {
				if ($(this).hasClass("form-view")) {
					currentFormStep = 1;
					maxFormStep = parseInt($(".registration-form-view").last().data("form-step"));
					initForm();
					$(".registration-form-view[data-form-step=" + currentFormStep + "]").show(0);
				}
				$(this).fadeIn().find("input[data-slider-id]").slider("relayout");
			});
		} else { views[view].fadeIn(); }

		viewTitle = (typeof viewsTitles[view] !== "undefined") ? viewsTitles[view] + " | " + defaultViewTitle : defaultViewTitle;
		$("title").text(viewTitle);

		gtag('config', 'UA-111431080-1', {'page_path': viewPath});
	}
};

var readURL = function(input) {
	var $input = $(input);
	var allowedMimeTypes = ["image/jpeg", "image/png", "image/gif"];
	var inputName = $input.attr("name");
	var $preview = $("[data-for=" + inputName + "]");
	var reader = null;
 	if (input.files && 
  		input.files[0] && 
  		input.files[0].type &&
  		allowedMimeTypes.indexOf(input.files[0].type) !== -1
  	) {

  		$input.siblings(".custom-file-control").text(input.files[0].name);
	    reader = new FileReader();	    
	    
	    if ($preview.length) {
	    	reader.onload = function(e) {
				$preview.empty();
				$("<img>").appendTo($preview).attr('src', e.target.result);
	    	}

	    	reader.readAsDataURL(input.files[0]);
	    }
  	} else {
  		$input.val("").siblings(".custom-file-control").empty();
  	}
};

var views = {
	"register:sitter" 	: $(".app-view.sitter-registration"),
	"register:parent" 	: $(".app-view.parent-registration"),
	"register:thanks" 	: $(".app-view.thanks-registration"),
	"register:error"  	: $(".app-view.error-registration"),
	"document:terms"  	: $(".app-view.document"),
	"document:privacy"	: $(".app-view.document"),
	"about"			  	: $(".app-view.about"),
	"home" 				: $(".app-view.home-view")
},
viewsContent= {
	"register:sitter": "views/register-sitter.html",
	"register:parent": "views/register-parent.html",
	"document:terms": "views/document-terms.html",
	"document:privacy": "views/document-privacy.html"
},
viewsTitles= {
	"register:sitter" 	: "Sitter Registration",
	"register:parent" 	: "Parent Registration",
	"register:thanks" 	: "Thank You",
	"register:error"  	: "Error Registering",
	"document:terms"  	: "Terms of Service",
	"document:privacy"	: "Privacy Policy",
	"about"			  	: "Our Story"
}

var selectData = {
	"month" : {
		"1": "January",
		"2" : "February",
		"3" : "March",
		"4" : "April",
		"5" : "May",
		"6" : "June",
		"7" : "July",
		"8" : "August",
		"9" : "September",
		"10" : "October",
		"11" : "November",
		"12" : "December"
	},
	"day": range(1, 31),
	"year": range((new Date()).getFullYear() - 115, (new Date()).getFullYear() - 15)
};

var currentFormStep = 1;
var maxFormStep = null;
var registration_form = null;

$(document).ready(function () {
	$(".oembed-field").on("change blur", function (e) {
		var $oembedField = $(this);
		var $oembedData = $oembedField.next(".oembed-data");
		tagdata = [];
		eventdata = [];
		var scriptruns = [];
		var text = $oembedField.val();
		text = $("<span>" + text + "</span>").text(); //strip html
		text = text.replace(/(\s|>|^)(https?:[^\s<]*)/igm, "$1<div><a href=\"$2\" class=\"oembed\">$2</a></div>");
		text = text.replace(/(\s|>|^)(mailto:[^\s<]*)/igm, "$1<div><a href=\"$2\" class=\"oembed\">$2</a></div>");
		
		$oembedData.empty().html(text).find(".oembed").oembed(null, {
			includeHandle: false,
		});		
	});

	docpop();
});