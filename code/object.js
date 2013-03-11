var sxswObject = function(){
	var gigs,
		searchAutoComplete = [],
		gigHTML = $('<div id="gightml" />');
	return{
		init:function(){
			for(var n in sxswMusic)
				gigs = sxswMusic[n].response.gigs;
			
			for(var i=0; i < gigs.length; i++){
				searchAutoComplete.push(gigs[i].band_name);
				searchAutoComplete.push(gigs[i].venue_name);
				this.generateGigHTML(gigs[i]);
			
			}
			searchAutoComplete = searchAutoComplete.sort();
			var searchUnique = [];
			
			$.each(searchAutoComplete, function(i, el){
				if($.inArray(el, searchUnique) === -1) searchUnique.push(el);
			});
			searchAutoComplete = searchUnique;
			debug.log(gigHTML);
		},
		generateGigHTML : function(gig){
			var pattern = /[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]|@| /g;
			var cleanBandName = gig.band_name.replace(pattern,'');
			var cleanVenueName = gig.venue_name.replace(pattern,'');
			var html = '<div class="full-width gig '+cleanBandName+' '+cleanVenueName+'"><div class="grid-3">'+gig.start_time+'</div><div class="grid-7">'+gig.band_name+'</div><div class="grid-2"><span class="ico-calendar"></span></div></div>'
			//var html = '<div class="gig '+cleanBandName+' '+cleanVenueName+'"><div class="time">'+gig.start_time+'</div><div class="gigInfo"><span class="band">'+gig.band_name+'</span><span class="venue">'+gig.venue_name+'</span></div><div class="calendar"></div></div>';
			gigHTML.append(html);
		},
		searchAutoComplete : function(){
			return searchAutoComplete;
		},
		gigHTML : function(){
			return gigHTML;
		},
		searchValue : function(searchSet){
			var searchUnique = [];
			debug.log(searchSet);
			$.each(searchAutoComplete, function(i, el){
				var string = searchAutoComplete[i].toUpperCase();
				if(string.search(searchSet.toUpperCase()) != -1){
					searchUnique.push(searchAutoComplete[i])
				}
			});
			
			sortList.checkResults(searchUnique);
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
					
					results.push('.'+gigSet[i].replace(/[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]|@| /g,''));
				}
			}
			
			
			debug.log(results);
			this.showGigs(results);
			
		},
		showGigs:function(gigSet){
			var gigHTML = sxswObject.gigHTML();
			
			$('#masterList div.gig').remove();
			
			var currentHTML = gigHTML.find(gigSet.toString());
			
			$('#masterList .grid-container').append(gigHTML.find(gigSet.toString()).clone());
		}
	}
}();

var user = function(){
	var initialized = false,
		FBLikes = [],
		SDsongs = [],
		optionsSelected = {},
		selectedObject,
		checkSpotify = false;
	return {
		initSC : function(paging){
			var url = (paging) ? paging : '/me/followings';
			SC.get(url, function(r) { 
				if(r.length > 0){
					for(var i=0; i < r.length; i++){
						SDsongs.push(r[i].username)
					}
				}
				if(r.hasOwnProperty('next_href')){
					user.initSC(r.next_href);
				}
				sortList.checkResults(SDsongs);
			});
			
		},
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
					checkSpotify = true;
					user.initFB();
					return false;
				}
				else{
					selectedObject.unbind('click').bind('click',function(){user.toggle('FBLikes')});
					user.toggle('FBLikes');
					debug.log(FBLikes);
					sortList.checkResults(FBLikes);
					
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
	$('.btn.facebook').bind('click',function(){
		var $this = $(this);
		FB.login(function(response) {
		   if (response.authResponse) {
			 debug.log('Welcome!  Fetching your information.... ');
			 user.setObject($this);
			 user.initFB();
		   } else {
			 debug.log('User cancelled login or did not fully authorize.');
		   }
		},{scope:'user_likes,user_actions.music'});
	});
	$('.btn.soundcloud').bind('click',function(){
		SC.connect(function() {
		  user.initSC();
		});
	});
	$('#search-form').bind('keyup',function(ev){
		if(this.value.length > 2)
			sxswObject.searchValue(this.value);
	});
});