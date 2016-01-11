{% extends "dialog.tpl" %}

{% block title %}Задача "{{ task.get_description()[:15] }}..." от {{ task.get_time_open()|date('d.m.Y') }}{% endblock title %}

{% block dialog %}
<form role="form">
  <div class="form-group">
    <label for="task-reason">Причина закрытия</label>
    <textarea type="text" name="reason" id="task-reason" tabindex="1" autofocus required
              class="form-control" placeholder="Причина закрытия задачи. Например 'Выполнена в срок.'" rows="5">
    </textarea>
  </div>
  <div class="form-group">
    <label for="task-rating" style="display: block;">Оценка выполнения</label>
    <!--[if lte IE 7]><style>#reviewStars-input{display:none}</style><![endif]-->

    <div id="reviewStars-input" tabindex="2">
      <input id="star-4" type="radio" name="task-rating">
      <label title="отлично" for="star-4"></label>

      <input id="star-3" type="radio" name="task-rating">
      <label title="хорошо" for="star-3"></label>

      <input id="star-2" type="radio" name="task-rating">
      <label title="удовлетворительно" for="star-2"></label>

      <input id="star-1" type="radio" name="task-rating">
      <label title="неудовлетворительно" for="star-1"></label>

      <input id="star-0" type="radio" name="task-rating" checked>
      <label title="плохо" for="star-0"></label>
    </div>
  </div>
  <div class="form-group">
    <label for="task-time_target">Дата закрытия</label>
    <div class="input-group date">
      <input type="text" class="form-control" id="task-time_close" readonly="readonly" tabindex="3" required>
      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
    </div>
  </div>
</form>
{% endblock dialog %}

{% block buttons %}
  <div class="btn btn-default close_task">Закрыть задачу</div>
{% endblock buttons %}

{% block script %}
  $('.input-group.date').datetimepicker({
    format: "DD.MM.YYYY",
    minDate: moment("{{ task.get_time_open() }}", "X"),
    useCurrent: true,
    locale: "ru",
    ignoreReadonly: true
  });
  $('.close_task').click(function(){
    if ($('form').find('.form-group.has-error').length == 0)
      $.get('close_task',{
        id: {{ task.get_id() }},
        reason: $('#task-reason').val(),
        rating: $('[name="task-rating"]:checked').attr('id'),
        time_close: $('.input-group.date').data("DateTimePicker").date().format('X')
        },function(r){
          init_content(r);
          $('.dialog').modal('hide');
      });
  });
{% endblock script %}