{% extends "ajax.tpl" %}
{% set query = response.queries[0] %}
{% block js %}
    $('.import-form').html(get_hidden_content());
    $('#fileupload').fileupload({
        dataType: 'html',
        done: function(e, data){
         init_content(data.result);
        }
    });
{% endblock js %}
{% block html %}
<h3>Импорт дома</h3>
<p class="alert alert-warning">Для создания дома в файле должны быть указаны название улицы и название города.</p>
<div>
    <input id="fileupload" type="file" name="file" data-url="/import/analize_house" multiple>
</div>
{% endblock html %}