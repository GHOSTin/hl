{% extends "dialog.tpl" %}
{% block title %}Новая задача{% endblock title %}
{% block dialog %}
<form role="form">
  <div class="form-group">
    <label for="task-description">Задача</label>
    <textarea type="text" name="description" id="task-description" tabindex="1" autofocus
              class="form-control" placeholder="Что нужно сделать" rows="5" required>
    </textarea>
  </div>
  <div class="form-group">
    <label for="task-performers">Исполнители</label>
    <select data-placeholder="Выберите исполнителей" class="form-control chosen-select" aria-required="true"
            multiple tabindex="2" id="task-performers" name="performers" required>
      {% for user in component.users %}
        <option value="{{ user.get_id() }}">{{ user.get_lastname() }} {{ user.get_firstname() }}</option>
      {% endfor %}
    </select>
  </div>
  <div class="form-group">
    <label for="task-time_close">Крайний срок</label>
    <div class="input-group date">
      <input type="text" class="form-control" id="task-time_target" readonly="true" tabindex="3" required>
      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
    </div>
  </div>
</form>
{% endblock dialog %}
{% block buttons %}
  <div class="btn btn-default add_task">Поставить задачу</div>
{% endblock buttons %}
{% block script %}
  $(".chosen-select").chosen({width: '100%'});
  $('.input-group.date').datepicker({
    format: "dd.mm.yyyy",
    startDate: "today",
    weekStart: 1,
    todayBtn: "linked",
    language: "ru",
    autoclose: true,
    todayHighlight: true
  });
  $('form').jBootValidator({validateOnSubmit: true});
  $('.add_task').click(function(){
    if ($('form').find('.form-group.has-error').length == 0)
      $.get('add_task',{
        description: $('#task-description').val(),
        performers: $('#task-performers').val(),
        time_target: $('.input-group.date').datepicker('getDate').getTime()/1000
        },function(r){
          init_content(r);
          $('.dialog').modal('hide');
      });
  });
{% endblock script %}