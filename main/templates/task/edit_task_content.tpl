{% extends "ajax.tpl" %}

{% set creator = task.get_creator() %}
{% set open_date = task.get_time_open()|date('d.m.Y') %}
{% set target_date = task.get_time_target()|date('d.m.Y') %}
{% set close_date = task.get_time_close()|date('d.m.Y') %}
{% set performers = task.get_performers() %}

{% block html %}
  <div class="row" id="task" data-id="{{ task.get_id() }}">
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header col-sm-12 col-lg-7">
          <p class="navbar-text">
            <span class="visible-xs visible-sm text-center">{{ open_date }} -</span>
            <span class="hidden-xs hidden-sm">
              Выполняется с {{ open_date }} по
            </span>
          </p>
          <form class="navbar-form pull-left">
            <input type="text" class="form-control task_time_target" readonly="readonly">
          </form>
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
      <p>
        <input type="text" name="title" id="task-title" class="form-control" value="{{ task.get_title() }}">
      </p>
      <textarea type="text" name="description" id="task-description"
                class="form-control" placeholder="Что нужно сделать" rows="10">{{ task.get_description() }}</textarea>
      {% if task.get_status() == 'close' %}
        <p>
          <strong>Дата закрытия:</strong>
          <input type="text" class="form-control task_time_close" value="{{ close_date }}" readonly="true">
        </p>
        <p>
          <strong>Причина закрытия:</strong>
          <textarea type="text" name="reason" id="task-reason"
                    class="form-control" placeholder="Причина закрытия" rows="10">{{ task.get_reason() }}</textarea>
        </p>
        <p class="task_rating">
          <strong>Оценка:</strong>
          <!--[if lte IE 7]><style>#reviewStars-input{display:none}</style><![endif]-->

          <div id="reviewStars-input" tabindex="2">
          {% for i in 0..4 %}
            <input id="star-{{ loop.revindex0 }}" type="radio" name="task-rating"
              {% if loop.revindex0 == (task.get_rating()-1) %}checked {% endif %}>
            <label for="star-{{ loop.revindex0 }}"></label>
          {% endfor %}
          </div>
        </p>
      {% endif %}
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
                {% for user in users %}
                  <option value="{{ user.get_id() }}"
                          {% if user in performers %}selected="selected" {% endif %}>{{ user.get_lastname() }} {{ user.get_firstname() }}
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

  $('.task_time_target, .task_time_close').datetimepicker({
    format: "DD.MM.YYYY",
    defaultDate: moment("{{ target_date }}", "DD.MM.YYYY").add(1, 'seconds'),
    minDate: moment("{{ open_date }}", "DD.MM.YYYY"),
    locale: "ru",
    ignoreReadonly: true
  });
  $(document).on('click', '#task_save', function(){
    var link = location.hash.replace('#', '');
    if(link)
      $.get('save_task_content', {
          id: $('div#task').attr('data-id'),
          title: $('#task-title').val(),
          description: $('#task-description').val(),
          performers: $('#task-performers').val(),
          time_target: $('.task_time_target').data("DateTimePicker").date().format('X'),
          status: "{{ task.get_status() }}",
          {% if task.get_status() == 'close' %}
            reason: $('#task-reason').val(),
            rating: $('[name="task-rating"]:checked').attr('id'),
            time_close: $('.task_time_close').data("DateTimePicker").date().format('X'),
          {% endif %}
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