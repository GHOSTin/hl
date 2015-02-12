
$(function(){

  var view = Backbone.View.extend({
    el: $('ul.menu'),

    events: {
      'click a#import_accruals': 'import_accruals'
    },

    initialize: function () {
      this.import_accruals_form = $('#import_accruals_form').html();
    },

    import_accruals: function(){
      $('.import').html(this.import_accruals_form);
      $('.date').datepicker({format: 'mm.yyyy', language: 'ru'}).on('changeDate', function(){
        $('.date').datepicker('hide');
      });
    }
  });

  var view = new view();
});