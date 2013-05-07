/**
 * jQuery Select2 Sortable
 * - enable select2 to be sortable via normal select element
 * 
 * author      : Vafpress
 * inspired by : jQuery Chosen Sortable (https://github.com/mrhenry/jquery-chosen-sortable)
 * License     : GPL
 */

(function($){
	$.fn.extend({
		select2Order: function(){
			var $this      = this.filter('[multiple]').first(),
			    $select2   = $this.siblings('.select2-container'),
			    unselected = [],
			    sorted;

			$this.find('option').each(function(){
				!this.selected && unselected.push(this);
			});

			sorted = $($select2.find('.select2-choices li[class!="select2-search-field"]').map( function() {
				if (!this) {
					return undefined;
				}
				var text = $.trim($(this).text());
				return $this.find('option').filter(function () { return $(this).html() == text; })[0];
			}));

			sorted.push.apply(sorted, unselected);
			return sorted;
		},
		select2Sortable: function(){
			var $this = this.filter('[multiple]');

			$this.each(function(){
				var $select  = $(this);
				var $select2 = $select.siblings('.select2-container');

				// Init jQuery UI Sortable
				$select2.find('.select2-choices').sortable({
					'placeholder' : 'ui-state-highlight',
					'items'       : 'li:not(.select2-search-field)',
					'tolerance'   : 'pointer'
				});

				// Apply options ordering in form submit
				$select.closest('form').unbind('submit.select2sortable').on('submit.select2sortable', function(){
					var $options = $select.select2Order();
					$select.children().remove();
					$select.append($options);
					$select.select2("destroy");
					$select.select2().select2Sortable();
				});
			});
		}
	});
}(jQuery));