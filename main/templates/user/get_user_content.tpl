{% extends "ajax.tpl" %}

{% block js %}
$('.user[user = {{ user.get_id() }}]').append(get_hidden_content())
{% endblock %}

{% block html %}
<ul class="user-content list-unstyled">
  <li>
    <ul class="nav nav-pills">
      <li>
        <a class="get_user_information">Информация</a>
      </li>
      <li>
        <a class="get_user_profiles">Профили</a>
      </li>
      <li>
        <a class="get_restrictions">Ограничения</a>
      </li>
    </ul>
  </li>
  <li class="user-information">{% include 'user/build_user_content.tpl' %}</li>
</ul>
{% endblock %}