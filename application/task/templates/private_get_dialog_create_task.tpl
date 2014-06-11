{% extends "dialog.tpl" %}
{% block title %}Новая задача{% endblock title %}
{% block dialog %}
  <div class="form-group">
    <label for="task-description">Задача</label>
    <textarea type="text" name="description" id="task-description"
              class="form-control" placeholder="Что нужно сделать" rows="5">
    </textarea>
  </div>
  <div class="form-group">
    <label for="task-performers">Исполнители</label>
    <select data-placeholder="Выберите исполнителей" class="form-control chosen-select" multiple tabindex="-1" id="task-performers" name="performers">
      {% for user in component.users %}
        <option value="{{ user.get_id() }}">{{ user.get_lastname() }} {{ user.get_firstname() }}</option>
      {% endfor %}
    </select>
  </div>
  <div class="form-group">
    <label for="task-time_close">Крайний срок</label>
    <div class="input-group date">
      <input type="text" class="form-control" id="task-time_close" disabled><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
    </div>
  </div>
{% endblock dialog %}
{% block buttons %}
  <div class="btn btn-default add_task">Поставить задачу</div>
{% endblock buttons %}
{% block script %}
  $(".chosen-select").chosen({width: '100%'});
  $('.input-group.date').datepicker({
    format: "dd.mm.yyyy",
    weekStart: 1,
    todayBtn: "linked",
    language: "ru",
    autoclose: true
  });
  $('.add_task').click(function(){
    $.get('add_task',{
      description: $('#task-description').val(),
      performers: $('#task-performers').val(),
      time_close: $('.input-group.date').datepicker('getDate').getTime()/1000
      },function(r){
        init_content(r);
        $('.dialog').modal('hide');
    });
  });
{% endblock script %}