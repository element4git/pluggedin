var sxswObject = function(){
	var gigs,
		searchAutoComplete = [];
	return{
		init:function(){
			for(var n in sxswMusic)
				gigs = sxswMusic[n].response.gigs;
			
			for(var i=0; i < gigs.length; i++){
				searchAutoComplete.push(gigs[i].band_name);
				searchAutoComplete.push(gigs[i].venue_name);
			
			}
			searchAutoComplete = searchAutoComplete.sort();
			var searchUnique = [];
			
			$.each(searchAutoComplete, function(i, el){
				if($.inArray(el, searchUnique) === -1) searchUnique.push(el);
			});
			searchAutoComplete = searchUnique;
		},
		searchAutoComplete : function(){
			return searchAutoComplete;
		}
		
	}
}();

var user = function(){
	FBLikes = [];
	return {
		initFB : function(paging){
			var url = (paging) ? paging : '/me/music';
			FB.api(url,function(r){
				debug.log(r);
				if(r.data.length > 0){
					for(var i=0; i < r.data.length; i++){
							FBLikes.push(r.data[i].name);
					}
				}				
				if(typeof r.paging.next == 'string')
					user.initFB(r.paging.next);
			});
			debug.log(FBLikes);
		}
	}
}();



//Enable Objects
$(function(){
	$( "#bandSearch" ).autocomplete({
	  source: sxswObject.searchAutoComplete(),
	  response: function( event, ui ) { console.log(ui)}
	});
	$('#facebook').bind('click',function(){
		FB.login(function(response) {
		   if (response.authResponse) {
			 debug.log('Welcome!  Fetching your information.... ');
			 user.initFB();
		   } else {
			 debug.log('User cancelled login or did not fully authorize.');
		   }
		});
	})
});