{% extends "ajax.tpl" %}
{% set query = response.queries[0] %}
{% block js %}
    show_dialog(get_hidden_content());
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Ошибки
        </h3>
    </div>
    <div class="modal-body">
       {{ error.getMessage() }}
    </div>
    <div class="modal-footer">
        <div class="btn close_dialog">Отмена</div>
    </div>
</div>
{% endblock html %}