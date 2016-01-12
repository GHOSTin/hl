{% extends "dialog.tpl" %}

{% block title %}Новая задача{% endblock title %}

{% block dialog %}
<form id="createTask">
  <div class="form-group">
    <label for="task-title">Тема</label>
    <input type="text" name="title" id="task-title" tabindex="1" autofocus
              class="form-control" placeholder="Что нужно сделать">
  </div>
  <div class="form-group">
    <label for="task-description">Задача</label>
    <textarea name="description" id="task-description" tabindex="2"
              class="form-control" placeholder="Что нужно сделать" rows="5"></textarea>
  </div>
  <div class="form-group">
    <label for="task-performers">Исполнители</label>
    <select data-placeholder="Выберите исполнителей" class="form-control chosen-select" aria-required="true"
            multiple tabindex="3" id="task-performers" name="performers">
      {% for user in users %}
        <option value="{{ user.get_id() }}">{{ user.get_lastname() }} {{ user.get_firstname() }}</option>
      {% endfor %}
    </select>
  </div>
  <div class="form-group">
    <label for="task-time_target">Крайний срок</label>
    <input type="text" name="time_target" class="form-control" id="task-time_target" readonly="readonly" tabindex="4">
  </div>
</form>
{% endblock dialog %}

{% block buttons %}
  <button form="createTask" type="submit" class="btn btn-primary create_task">Поставить задачу</button>
{% endblock buttons %}

{% block script %}
$(document).ready(function() {
    $('#createTask')
      .formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
          valid: null,
          invalid: null,
          validating: null
        },
        fields: {
          title: {
            validators: {
              notEmpty: {
                message: 'Введите тему'
              }
            }
          },
          description: {
            validators: {
              notEmpty: {
                message: 'Введите описание задачи'
              }
            }
          },
          performers: {
            validators: {
              notEmpty: {
                message: 'Выберите исполнителей'
              }
            }
          },
          time_target: {
            validators: {
              notEmpty: {
                message: 'Выберите конечный срок выполнения задачи'
              }
            }
          }
        }
      })
      .on('err.form.fv', function(e) {
        $('.add_task').addClass('disabled').prop('disabled', true);
      })
      .on('success.field.fv', function(e, data) {
        if (data.fv.getInvalidFields().length > 0) {    // There is invalid field
          $('.create_task').addClass('disabled').prop('disabled', true);
        } else {
          $('.create_task').removeClass('disabled').prop('disabled', false);
        }
      })
      .on('success.form.fv', function(e) {
        $('.create_task').removeClass('disabled').prop('disabled', false);
        // Prevent form submission
        e.preventDefault();

        var $form      = $(e.target),
        fv         = $(e.target).data('formValidation');
        $.get('add_task',{
          title: $('#task-title').val(),
          description: $('#task-description').val(),
          performers: $('#task-performers').val(),
          time_target: $('#task-time_target').data("DateTimePicker").date().format('X')
          },function(res){
            init_content(res);
            $('.dialog').modal('hide');
        });
    });
    $(".chosen-select").chosen({width: '100%'});
    $('#task-time_target').datetimepicker({
      format: "DD.MM.YYYY",
      locale: 'ru',
      defaultDate: moment(),
      minDate: moment().subtract(1, 'seconds'),
      ignoreReadonly: true
    });
  });
{% endblock script %}