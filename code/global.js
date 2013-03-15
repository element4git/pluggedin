var debug = function(){
	var logOn = false;
	return {
		log : function(msg){
			if(window.console && logOn)
				console.log(msg)
		}
	}
}();
