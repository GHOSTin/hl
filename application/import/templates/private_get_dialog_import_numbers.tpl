{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
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
<h3>Импорт лицевых счетов</h3>
<p class="alert">Для создания улиц в файле должны быть указаны название улицы, название города, номер дома, номера квартир.</p>
<div>
    <input id="fileupload" type="file" name="file" data-url="/import/import_numbers" multiple>
</div>
{% endblock html %}