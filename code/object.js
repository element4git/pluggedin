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

var sortList = function(){
	return {
		checkResults : function(gigSet /*array*/){
			var gigs = sxswObject.searchAutoComplete();
			var results = []
			
			for(var i=0; i < gigSet.length; i++){
				
				if($.inArray(gigSet[i], gigs) != -1){
					results.push('.'+gigSet[i].replace(/&|\'| /g,''));
				}
			}
			
			this.showGigs(results);
			
		},
		showGigs:function(gigSet){
			$('#sxswMusic div.gig').removeAttr('style');
			
			$(gigSet.toString()).css('display','block');
		}
	}
}();

var user = function(){
	var initialized = false,
		FBLikes = [],
		optionsSelected = {},
		selectedObject,
		checkSpotify = false;
	return {
		initFB : function(paging){
			
			//Start Loading Grapic
			
			var initialized = true;
			var url = (paging) ? paging : '/me/music';
			if(checkSpotify && !paging){
				url = '/me/music.listens';
			}
			FB.api(url,function(r){
				if(r.data.length > 0 && !checkSpotify){
					for(var i=0; i < r.data.length; i++){
						FBLikes.push(r.data[i].name);
					}
				}
				else if(checkSpotify && r.data.length > 0){
					for(var i=0; i < r.data.length; i++){
						//FBLikes.push(r.data[i].name);
						for(var a in r.data[i].data){
							switch(a){
								case 'playlist':
									FBLikes.push(r.data[i].data[a].title.split(' - ')[0]);
									break;
								case 'musician':
									FBLikes.push(r.data[i].data[a].title);
									break;
								
							}
						}
					}					
					var searchUnique = [];
			
					$.each(FBLikes, function(i, el){
						if($.inArray(el, searchUnique) === -1) searchUnique.push(el);
					});
					FBLikes = searchUnique;
				}			
				if(r.hasOwnProperty('paging') && typeof r.paging.next == 'string')
					user.initFB(r.paging.next);
				else if(!checkSpotify){
					debug.log(FBLikes)
					checkSpotify = true;
					user.initFB();
					return false;
				}
				else{
					selectedObject.unbind('click').bind('click',function(){user.toggle('FBLikes')});
					user.toggle('FBLikes');
					sortList.checkResults(FBLikes);
					debug.log(FBLikes);
					
					//End Loading Graphic Here
				}
			});
		},
		setObject : function(obj){
			selectedObject = obj;
		},
		toggle : function(obj){
			if(typeof optionsSelected[obj] == 'boolean')
				delete optionsSelected[obj]
			else
				optionsSelected[obj] = true;
				
			debug.log(optionsSelected);
		}
	}
}();



//Enable Objects
$(function(){
	/*$( "#bandSearch" ).autocomplete({
	  source: sxswObject.searchAutoComplete(),
	  response: function( event, ui ) { console.log(ui)}
	});*/
	$('#facebook').bind('click',function(){
		var $this = $(this);
		FB.login(function(response) {
		   if (response.authResponse) {
			 debug.log('Welcome!  Fetching your information.... ');
			 user.setObject($this);
			 user.initFB();
		   } else {
			 debug.log('User cancelled login or did not fully authorize.');
		   }
		},{scope:'user_likes,user_activities'});
	})
});