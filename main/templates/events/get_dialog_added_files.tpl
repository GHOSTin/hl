{% extends "dialog.tpl" %}
{% block title %}Добавление файлов{% endblock title %}
{% block dialog %}
  <form id="my-awesome-dropzone" class="dropzone" action="#">
    <div class="dropzone-previews"></div>
  </form>
{% endblock dialog %}
{% block buttons %}
  <div class="btn btn-default update_cellphone">Сохранить</div>
{% endblock buttons %}