<script>function _hidden_content(){show_dialog(get_hidden_content()); {% block script %}{% endblock script %} }</script>
<div class="_hidden_content_html">
  <div class="modal-dialog {% block modal_size %}{% endblock modal_size %}">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{% block title %}{% endblock title %}</h3>
        </div>
        <div class="modal-body">{% block dialog %}{% endblock dialog %}</div>
        <div class="modal-footer">
            {% block buttons %}{% endblock buttons %}<div class="btn btn-default close_dialog">Отмена</div>
        </div>
    </div>
  </div>
</div>