{% extends "ajax.tpl" %}
{% block js %}
    show_dialog(get_hidden_content());
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Данный счетчика за месяц</h3>
    </div>  
    <div class="modal-body">
        1<input type="text" style="width:50px"> 2<input type="text" style="width:50px">
    </div>
    <div class="modal-footer">
        <div class="btn update_meter_data">Сохранить</div>
        <div class="btn close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}