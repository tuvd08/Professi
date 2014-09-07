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
		}
	};
	
	$('#input-search-field').on('keydown', Base.enterSearch);
	Base.widthMenu();
})(jQuery);
