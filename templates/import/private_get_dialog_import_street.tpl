{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
    $('.import-form').html(get_hidden_content());
    $('#fileupload').fileupload({
        url: '/import/analize_street',
        dataType: 'html',
        done: function(e, data){
         init_content(data.result);
        }
    });
{% endblock js %}
{% block html %}
<h3>Импорт улицы</h3>
<p class="alert alert-warning">Для создания улицы в файле должно быть указано название города.</p>
<div>
    <input id="fileupload" type="file" name="file" multiple>
</div>
{% endblock html %}