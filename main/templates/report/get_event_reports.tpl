{% extends "ajax.tpl" %}

{% block js %}
$('.report-content').html(get_hidden_content());

// датапикер
$('.event_time_begin').datetimepicker({
  format: 'DD.MM.YYYY',
  locale: 'ru',
  defaultDate: moment.unix({{ filters.time_begin }})
}).on('dp.change', function(e){
    $.get('event/set_time_begin',{
      time: e.date.format('DD.MM.YYYY')
    });
});

$('.event_time_end').datetimepicker({
  format: 'DD.MM.YYYY',
  locale: 'ru',
  defaultDate: moment.unix({{ filters.time_end }})
}).on('dp.change', function(e){
    $.get('event/set_time_end',{
      time: e.date.format('DD.MM.YYYY')
    }) ;
});

// сбрасывает фильтры
$('.clear_filter_event').click(function(){
  $.get('event/clear', {
  }, function(r){
    init_content(r);
  });
});
{% endblock %}

{% block html %}
<h4>Отчеты по событиям</h4>
<div class="row">
  <div class="col-sm-3 col-lg-3">
    <div class="col-xs-12">
      <h4>Фильтры <small><a class="pull-right clear_filter_event">сбросить</a></small></h4>
    </div>
    <ul class="list-unstyled filters">
      <li>
        <label>по дате</label>
        <div class="row form-group">
          <label class="control-label col-xs-1">с</label>
          <div class="col-xs-10">
            <input type="text" class="form-control event_time_begin">
          </div>
        </div>
        <div class="row form-group">
          <label class="control-label col-xs-1">по</label>
          <div class="col-xs-10">
            <input type="text" class="form-control event_time_end">
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="col-xs-12 col-sm-9 col-lg-9">
    <a class="btn btn-link" href="/reports/event/html/" target="_blank">Просмотреть</a>
  </div>
</div>
{% endblock %}