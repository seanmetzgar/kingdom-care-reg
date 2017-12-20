var docpop = (function() {
	if (!$(".docpop-overlay, .docpop-container, .docpop-content").length) {
		var body = $("body");
		var overlay = $("<div></div>").appendTo(body).addClass("docpop-overlay");
		var container = $("<div></div>").appendTo(body).addClass("docpop-container");
		var content = $("<div></div>").appendTo(container).addClass("docpop-content");
		var closer = $("<button></button>").appendTo(container).addClass("docpop-closer").attr("title", "close");

		$("<i></i>").appendTo(closer).addClass("fa fa-times").attr("aria-hidden", "true");

		$(".docpop").on("click", function(e) {
			var $this = $(this);
			var doc = $this.data("doc");

			if (typeof doc === "string" && doc) {
				e.preventDefault();
				content.empty().load(doc);
				overlay.show();
				container.show();
			}
		});
		closer.add(overlay).on("click", function(e) {
			e.preventDefault();
			container.hide();
			overlay.hide();
			content.empty();
		});
	}
});