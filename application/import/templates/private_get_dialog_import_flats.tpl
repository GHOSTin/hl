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
<h3>Импорт квартир</h3>
<p class="alert">Для создания улиц в файле должны быть указаны название улицы, название города, номер дома.</p>
<div>
    <input id="fileupload" type="file" name="file" data-url="/import/analize_flats" multiple>
</div>
{% endblock html %}