{% extends "ajax.tpl" %}
{% set task = component.task %}
{% set creator = task.get_creator() %}
{% set open_date = task.get_time_open()|date('d.m.Y') %}
{% set close_date = task.get_time_target()|date('d.m.Y') %}
{% set performers = [] %}
{% for performer in task.get_performers() %}
  {% set performers = performers|merge([performer.get_id()]) %}
{% endfor %}
{% block html %}
  <div class="row" id="task" data-id="{{ task.get_id() }}">
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <form class="navbar-form pull-right">
            <input type="text" class="form-control task_time_target" value="{{ close_date }}" readonly="true">
          </form>
          <p class="navbar-text">
            <span class="visible-xs visible-sm text-center">{{ open_date }} -</span>
            <span class="hidden-xs hidden-sm">
              Выполняется с {{ open_date }} по
            </span>
          </p>
        </div>
        <form class="navbar-form text-center">
          <button type="button" class="btn btn-default" id="task_save">
            <i class="glyphicon glyphicon-save"></i>Сохранить
          </button>
          <button type="button" class="btn btn-default" id="task_cancel_save">
            <i class="glyphicon glyphicon-remove"></i>Отменить
          </button>
        </form>
      </div>
    </nav>
    <div class="col-xs-12">
      <textarea type="text" name="description" id="task-description"
                class="form-control" placeholder="Что нужно сделать" rows="10">{{ task.get_description() }}</textarea>
      <p>
        <ul class="list-group">
          <li class="list-group-item list-group-item-info">
            Постановщик:
            <strong>
              {{ creator.get_lastname()}} {{ creator.get_firstname()|first|upper }}.{{ creator.get_middlename()|first|upper }}.
            </strong>
          </li>
          <li class="list-group-item list-group-item-info">
            Исполнители:
            <strong>
              <select data-placeholder="Выберите исполнителей" class="form-control chosen-select" multiple tabindex="-1" id="task-performers" name="performers">
                {% for user in component.users %}
                  <option value="{{ user.get_id() }}"
                          {% if user.get_id() in performers %}selected="selected" {% endif %}>{{ user.get_lastname() }} {{ user.get_firstname() }}
                  </option>
                {% endfor %}
              </select>
            </strong>
          </li>
        </ul>
      </p>
    </div>
  </div>
{% endblock %}
{% block js %}
  $('#task_content').find('section').html(get_hidden_content());

  $(".chosen-select").chosen({width: '100%'});

  $('.task_time_target').datepicker({
  format: "dd.mm.yyyy",
  startDate: "{{ open_date }}",
  weekStart: 1,
  todayBtn: "linked",
  language: "ru",
  autoclose: true,
  todayHighlight: true
  });
  $(document).on('click', '#task_save', function(){
    var link = location.hash.replace('#', '');
    if(link)
      $.get('save_task_content', {
          id: $('div#task').attr('data-id'),
          description: $('#task-description').val(),
          performers: $('#task-performers').val(),
          time_target: $('.task_time_target').datepicker('getDate').getTime()/1000
        },function(r) {
          init_content(r);
        });
  });
  $(document).on('click', '#task_cancel_save', function(){
    var link = location.hash.replace('#', '');
    if(link)
      $.get('get_task_content', {
        id: $('div#task').attr('data-id')
      }, function(r) {
        init_content(r);
      });
  });
{% endblock %}