{% extends "../default/dialog.tpl" %}
{% block title %}Смена номера телефона{% endblock title %}
{% block dialog %}
	<input type="text" value="{{ user.telephone }}" class="dialog-telephone form-control">
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default update_telephone">Сохранить</div>
{% endblock buttons %}	