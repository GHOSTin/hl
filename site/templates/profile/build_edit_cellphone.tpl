{% extends "../default/dialog.tpl" %}
{% block title %}Смена номера сотового телефона{% endblock title %}
{% block dialog %}
		<input type="text" value="{{ user.cellphone }}" class="dialog-cellphone form-control">
{% endblock dialog %}
{% block buttons %}
		<div class="btn btn-default update_cellphone">Сохранить</div>
{% endblock buttons %}
