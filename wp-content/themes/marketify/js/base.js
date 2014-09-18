(function($){
	var Base = {
		 getKeynum : function(event) {
      var keynum = -1;
      if (window.event) { /* IE */
        event = window.event;
        keynum = event.keyCode;
      } else if (event.which) { /* Netscape/Firefox/Opera */
        keynum = event.which;
      }
      if (keynum == 0) {
        keynum = event.keyCode;
      }
      return keynum;
    },
    enterSearch : function(evt) {
			var key = Base.getKeynum(evt);
			if(key === 13) {
				$(this).parents('form.search-form-active:first').find('button.search-submit:first').trigger( "click" );
			}
		},
		widthMenu : function() {
			var menuItem = $('ul.edd-taxonomy-widget > li');
			var w = 0;
			menuItem.each(function(i) {
				var wI = $(this).find('a:first').width();
				if(wI > w) {
						w = wI;
				}
			});
			menuItem.each(function(i) {
				$(this).find('a:first').width(w);
			});
		},
		menuSearchAction : function() {
			
		},
		processSearch : function() {
			var parent = $('#edd_categories_tags_widget-4');
			var fLis = parent.find('ul:first').find('>li');
			fLis.find('>a').removeAttr('href');
			fLis.find('ul').find('a').on('click', function(evt) {
				evt.preventDefault();
				var href = $(this).attr('href');
				var paths = href.split('/')
				var cat = paths[paths.length -2];
				var fromSearch = $('#quick-search-form');
				var input = fromSearch.find('#absc_search_cat:first');
				var cr = input.val();
				if(cr.length == 0) {
					input.val(cat);
				} else {
					input.val(cr  + ',' + cat);
				}
				//
				fromSearch.find('button.search-submit:first').trigger( "click" );
			}).attr('title', 'Click on this category to select search.');
			//
			if(window.lastSearchCats) {
				$('#quick-search-form').find('#absc_search_cat:first').val( window.lastSearchCats );
			}
			var select_parent = $('#selected-categories');
			select_parent.find('span.icon-cat').on('click', function() {
				var rVal = $(this).data('slug');
				//
				var fromSearch = $('#quick-search-form');
				var input = fromSearch.find('#absc_search_cat:first');
				var crs = input.val().split(',');
				crs = Base.removeArr(crs, rVal);
				input.val(crs.join());
				//
				fromSearch.find('button.search-submit:first').trigger( "click" );
			});
		},
		removeArr : function(arr, item) {
      for(var i = arr.length; i--;) {
				if(arr[i] === item) {
						arr.splice(i, 1);
				}
      }
      return arr;
		}
		
	};
	
	$('#input-search-field').on('keydown', Base.enterSearch);
	Base.widthMenu();
	//
	if(window.searchResult && window.searchResult == true) {
		Base.processSearch();
	}
	//
	window.Base = Base;
})(jQuery);
