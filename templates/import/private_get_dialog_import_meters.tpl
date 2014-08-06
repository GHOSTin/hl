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
<h3>Импорт счетчиков</h3>
<div>
    <input id="fileupload" type="file" name="file" data-url="/import/import_meters" multiple>
</div>
{% endblock html %}