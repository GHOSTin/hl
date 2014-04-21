<div class="modal-content">
    <div class="modal-header">
        <h3>{% block title %}{% endblock title %}</h3>
    </div>
    <div class="modal-body">{% block dialog %}{% endblock dialog %}</div>
    <div class="modal-footer">
        {% block buttons %}{% endblock buttons %}<div class="btn btn-default close_dialog">Отмена</div>
    </div>
</div>