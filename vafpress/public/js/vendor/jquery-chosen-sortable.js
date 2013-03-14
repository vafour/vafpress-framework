/*
 * Author: Yves Van Broekhoven & Simon Menke
 * Created at: 2012-07-05
 *
 * Requirements:
 * - jQuery
 * - jQuery UI
 * - Chosen
 *
 * Version: 1.0.0
 */
(function($) {

  $.fn.chosenOrder = function() {
    var $this   = this.filter('.chzn-sortable[multiple]').first(),
        $chosen = $this.siblings('.chzn-container');

    return $($chosen.find('.chzn-choices li[class!="search-field"]').map( function() {
      if (!this) {
        return undefined;
      }
      return $this.find('option:contains(' + $(this).text() + ')')[0];
    }));
  };


  /*
   * Extend jQuery
   */
  $.fn.chosenSortable = function(){
    var $this = this.filter('.chzn-sortable[multiple]');

    $this.each(function(){
      var $select = $(this);
      var $chosen = $select.siblings('.chzn-container');

      // On mousedown of choice element,
      // we don't want to display the dropdown list
      $chosen.find('.chzn-choices').bind('mousedown', function(event){
        if ($(event.target).is('span')) {
          event.stopPropagation();
        }
      });

      // Initialize jQuery UI Sortable
      $chosen.find('.chzn-choices').sortable({
        'placeholder' : 'ui-state-highlight',
        'items'       : 'li:not(.search-field)',
        //'update'      : _update,
        'tolerance'   : 'pointer'
      });

      // Intercept form submit & order the chosens
      $select.closest('form').on('submit', function(){
        var $options = $select.chosenOrder();
        $select.children().remove();
        $select.append($options);
      });

    });

  };

}(jQuery));