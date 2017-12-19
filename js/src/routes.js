var DEFAULT_HASH = "";
var routes = {
	"home":         crossroads.addRoute(""),
	"about": 		crossroads.addRoute("/our-story"),
	"register": 	crossroads.addRoute("/register/:id:")
};


$.each(routes, function (index, value) {
	if (index !== "default") {
		value.matched.add(function(id) {
			id = (typeof id !== "undefined" && typeof id === "string") ? id : null;
			setView(index, id);
		});
	}
	
});

// //only required if you want to set a default value
// if(! hasher.getHash()){
//     hasher.setHash(DEFAULT_HASH);
// }

function parseHash(newHash, oldHash){
    // second parameter of crossroads.parse() is the "defaultArguments" and should be an array
    // so we ignore the "oldHash" argument to avoid issues.
    crossroads.parse(newHash);
}
hasher.initialized.add(parseHash); //parse initial hash
hasher.changed.add(parseHash); //parse hash changes

hasher.init(); //start listening for hash changes