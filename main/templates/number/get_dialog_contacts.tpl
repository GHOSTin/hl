{% extends "dialog.tpl" %}

{% block title %}Диалог редактирования контактных данных{% endblock %}

{% block dialog %}
<div class="form-group">
  <label>ФИО</label>
  <input type="text" class="dialog-input-fio form-control" value="{{ number.get_fio() }}">
</div>
<div class="form-group">
  <label>Стационарный телефон</label>
  <input type="text" class="dialog-input-telephone form-control" value="{{ number.get_telephone() }}">
</div>
<div class="form-group">
  <label>Сотовый телефон</label>
  <input type="text" class="dialog-input-cellphone form-control" value="{{ number.get_cellphone() }}">
</div>
<div class="form-group">
  <label>Email</label>
  <input type="email" class="dialog-input-email form-control" value="{{ number.get_email() }}">
</div>
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary update_contacts">Сохранить</div>
{% endblock %}

{% block script %}
	$('.update_contacts').click(function(){
		$.post('/numbers/{{ number.get_id() }}/contacts/',{
			fio: $('.dialog-input-fio').val(),
      telephone: $('.dialog-input-telephone').val(),
      cellphone: $('.dialog-input-cellphone').val(),
      email: $('.dialog-input-email').val()
		},function(r){
			$('.dialog').modal('hide');
			$('.workspace').html(r);
      $('.cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
		});
	});
  $('.dialog-input-cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
{% endblock %}