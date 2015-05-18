{% extends "ajax.tpl" %}

{% block js %}
$('.report-content').html(get_hidden_content());

// датапикер
$('.event_time_begin').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
  $('.event_time_begin').datepicker('hide');
  $.get('event/set_time_begin',{
    time: $('.event_time_begin').val()
  },function(r){
    init_content(r);
  });
});

$('.event_time_end').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
  $('.event_time_end').datepicker('hide');
  $.get('event/set_time_end',{
    time: $('.event_time_end').val()
  }, function(r){
    init_content(r);
  });
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
            <input type="text" class="form-control event_time_begin" value="{{ filters.time_begin|date('d.m.Y') }}">
          </div>
        </div>
        <div class="row form-group">
          <label class="control-label col-xs-1">по</label>
          <div class="col-xs-10">
            <input type="text" class="form-control event_time_end" value="{{ filters.time_end|date('d.m.Y') }}">
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