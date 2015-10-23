{% extends "dialog.tpl" %}

{% block title %}Диалог создания отключения{% endblock title %}

{% block dialog %}
<form id="createOutage">
  <div class="form-group">
    <div class="row">
      <div class="col-md-6">
        <label class="control-label">Начальная дата</label>
        <input type="text" class="form-control dialog-begin" value="{{ "now"|date("d.m.Y") }}">
      </div>
      <div class="col-md-6">
        <label class="control-label">Планируемая дата</label>
        <input type="text" class="form-control dialog-target" value="{{ "now"|date("d.m.Y") }}">
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <div class="add_house_dialog col-md-6">
        <a class="add_house"><i class="fa fa-plus"></i> Добавить дом</a>
        <a class="add_house_cancel" style="display:none"><i class="fa fa-close"></i> Отменить</a>
        <div class="add_house_content" style="display:none">
          <div class="form-group">
            <label>Улицы</label>
            <select class="form-control dialog-select-street">
              <option value="0">Выберите улицу</option>
            {% for street in streets %}
              <option value="{{ street.get_id() }}">{{ street.get_name() }}</option>
            {% endfor %}
            </select>
          </div>
          <div class="form-group">
            <label>Дома</label>
            <select class="form-control dialog-select-house" disabled>
              <option value="0">Ожидание...</option>
          </select>
          </div>
        </div>
        <ul class="houses">
        </ul>
      </div>
      <div class="add_performer_dialog col-md-6">
        <a class="add_performer"><i class="fa fa-plus"></i> Добавить исполнителя</a>
        <a class="add_performer_cancel" style="display:none"><i class="fa fa-close"></i> Отменить</a>
        <div class="add_performer_content" style="display:none">
          <div class="form-group">
            <label>Группы</label>
            <select class="form-control dialog-select-group">
              <option value="0">Выберите группу</option>
            {% for group in groups %}
              <option value="{{ group.get_id() }}">{{ group.get_name() }}</option>
            {% endfor %}
            </select>
          </div>
          <div class="form-group">
            <label>Пользователи</label>
            <select class="form-control dialog-select-user" disabled>
              <option value="0">Ожидание...</option>
          </select>
          </div>
        </div>
        <ul class="performers">
        </ul>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label">Тип услуги</label>
    <select class="form-control dialog-type" name="workgroup" title="Выберите тип услуги">
      <option value="">Выберите тип услуги</option>
    {% for workgroup in workgroups %}
      <option value="{{ workgroup.get_id() }}">{{ workgroup.get_name() }}</option>
    {% endfor %}
    </select>
  </div>
  <div class="form-group">
    <label class="control-label">Описание</label>
    <textarea class="form-control dialog-description" rows="3" autofocus name="description"></textarea>
  </div>
</form>
{% endblock dialog %}

{% block buttons %}
	<button type="submit" name="submitButton" class="btn btn-primary create_outage" form="createOutage">Создать</button>
{% endblock buttons %}

{% block script %}
$(document).ready(function() {
  $('.dialog')
    .on('shown.bs.modal', function() {
      $('#createOutage')
      .formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
          valid: 'fa fa-check',
          invalid: 'fa fa-times',
          validating: 'fa fa-refresh'
        },
        fields: {
          workgroup: {
            validators: {
              notEmpty: {
                message: 'Выберите тип услуги'
              }
            }
          },
          description: {
            validators: {
              notEmpty: {
                message: 'Введите описание отключения'
              }
            }
          }
        }
      })
      .on('success.form.fv', function(e) {
      // Prevent form submission
        e.preventDefault();

        var $form      = $(e.target),
            fv         = $(e.target).data('formValidation'),
            houses     = [],
            performers = [],
            begin      = $form.find('.dialog-begin').val(),
            target     = $form.find('.dialog-target').val();
        $('.houses > li').each(function(){
          houses.push($(this).attr('value'));
        });
        $('.performers > li').each(function(){
          performers.push($(this).attr('value'));
        });
        $.post('/numbers/outages/',{
            description: $form.find('.dialog-description').val(),
            type: $form.find('.dialog-type').val(),
            houses: houses,
            performers: performers,
            begin: begin,
            target: target
          },function(response){
            $('.dialog').modal('hide');
            $('.workspace').html(response['workspace']);
          },
        "json");
      });
    })
  $('.add_house').click(function(){
    $('.add_house_content').show();
    $('.add_house_cancel').show();
    $('.add_house').hide();
  });
  $('.add_house_cancel').click(function(){
    $('.add_house_content').hide();
    $('.add_house').show();
    $('.add_house_cancel').hide();
  });
  $('.add_performer').click(function(){
    $('.add_performer_content').show();
    $('.add_performer_cancel').show();
    $('.add_performer').hide();
  });
  $('.add_performer_cancel').click(function(){
    $('.add_performer_content').hide();
    $('.add_performer').show();
    $('.add_performer_cancel').hide();
  });
  $('.dialog-begin').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
    $('.dialog-begin').datepicker('hide');
  });
  $('.dialog-target').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
    $('.dialog-target').datepicker('hide');
  })
  $('.dialog-select-street').change(function(){
    var id = $('.dialog-select-street :selected').val();
    if(id > 0){
      $.get('/numbers/outages/streets/' + id + '/houses/',
      function(r){
        $('.dialog-select-house').html(r).attr('disabled', false);
      });
    }
  });
  $('.dialog-select-house').change(function(){
    var id = $('.dialog-select-house :selected').val();
    var number = $('.dialog-select-house :selected').text();
    var street = $('.dialog-select-street :selected').text();
    if(id > 0){
      $('.houses').append('<li value="' + id + '">' + street + ', ' + number + ' <i class="fa fa-close remove_element"></i></li>');
      $('.add_house_content').hide();
      $('.add_house').show();
      $('.add_house_cancel').hide();
    }
  });
  $('.dialog-select-group').change(function(){
    var id = $('.dialog-select-group :selected').val();
    if(id > 0){
      $.get('/numbers/outages/groups/' + id + '/users/',
      function(r){
        $('.dialog-select-user').html(r).attr('disabled', false);
      });
    }
  });
  $('.dialog-select-user').change(function(){
    var id = $('.dialog-select-user :selected').val();
    var user = $('.dialog-select-user :selected').text();
    if(id > 0){
      $('.performers').append('<li value="' + id + '">' + user + ' <i class="fa fa-close remove_element"></i></li>');
      $('.add_performer_content').hide();
      $('.add_performer').show();
      $('.add_performer_cancel').hide();
    }
  });
});
{% endblock script %}