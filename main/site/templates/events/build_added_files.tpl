{% extends "../dialog.tpl" %}
{% block title %}Добавление файлов{% endblock title %}
{% block dialog %}
  <form id="events-files" class="dropzone" action="#">
    <div class="dropzone-previews"></div>
  </form>
{% endblock dialog %}
{% block buttons %}
  <div class="btn btn-primary added_files">Загрузить</div>
{% endblock buttons %}