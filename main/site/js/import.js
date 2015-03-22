
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

  var debt_view = Backbone.View.extend({
    el: $('ul.menu'),

    events: {
      'click a#import_debt': 'import_debt'
    },

    initialize: function () {
      this.import_debt_form = $('#import_debt_form').html();
    },

    import_debt: function(){
      $('.import').html(this.import_debt_form);
    }
  });

  var numbers_view = Backbone.View.extend({
    el: $('ul.menu'),

    events: {
      'click a#import_numbers': 'import_numbers'
    },

    initialize: function () {
      this.import_numbers_form = $('#import_numbers_form').html();
    },

    import_numbers: function(){
      $('.import').html(this.import_numbers_form);
    }
  });

  var view = new view();
  var debt = new debt_view();
  var numbers = new numbers_view();
});