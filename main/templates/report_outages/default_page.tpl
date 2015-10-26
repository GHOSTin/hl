{% extends "ajax.tpl" %}

{% block js %}
$('.report-content').html(get_hidden_content());

$('.begin_start').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
  $('.begin_start').datepicker('hide');
  $.post('/reports/outages/filters/begin/start/',{
    time: '00:00 ' + $('.begin_start').val()
  });
});

$('.begin_end').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
  $('.begin_end').datepicker('hide');
  $.post('/reports/outages/filters/begin/end/',{
    time: '00:00 ' + $('.begin_end').val()
  });
});
{% endblock %}

{% block html %}
<h4>Отчеты по отключениям</h4>
<div class="row">
  <div class="col-sm-3 col-lg-3">
    <div class="col-xs-12">
      <h4>Фильтры</h4>
    </div>
    <ul class="list-unstyled filters">
      <li>
        <label>по дате</label>
        <div class="row form-group">
          <label class="control-label col-xs-1">с</label>
          <div class="col-xs-10">
            <input type="text" class="form-control begin_start" value="{{ filters.start|date('d.m.Y') }}">
          </div>
        </div>
        <div class="row form-group">
          <label class="control-label col-xs-1">по</label>
          <div class="col-xs-10">
            <input type="text" class="form-control begin_end" value="{{ filters.end|date('d.m.Y') }}">
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="col-xs-12 col-sm-9 col-lg-9">
    <a class="btn btn-link" href="/reports/outages/html/" target="_blank">Просмотреть</a>
  </div>
</div>
{% endblock %}