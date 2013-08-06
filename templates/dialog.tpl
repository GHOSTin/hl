<script>function _hidden_content(){show_dialog(get_hidden_content()); {% block script %}{% endblock script %} }</script>
{% spaceless %}
<div class="_hidden_content_html">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{% block title %}{% endblock title %}</h3>
        </div>  
        <div class="modal-body">{% block dialog %}{% endblock dialog %}</div>
        <div class="modal-footer">
            {% block buttons %}{% endblock buttons %}<div class="btn close_dialog">Отмена</div>
        </div>    
    </div>
</div>
{% endspaceless %}