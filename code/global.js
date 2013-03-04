var debug = function(){
	var logOn = true;
	return {
		log : function(msg){
			if(window.console && logOn)
				console.log(msg)
		}
	}
}();
