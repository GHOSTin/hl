{% extends "ajax.tpl" %}

{% block js %}
$('.user[user = {{ user.get_id() }}] .user-information').html(get_hidden_content());
{% endblock %}

{% block html %}
<div class="row">
  <div class="col-md-4">
    <h4>Участки</h4>
    <ul type="departments">
    {% for department in departments %}
      <li class="restriction" item="{{ department.get_id() }}"><input type="checkbox"{% if department.get_id() in user.get_restriction('departments') %}checked=""{% endif %}> {{ department.get_name() }}</li>
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-4">
    <h4>Категория</h4>
    <ul type="categories">
    {% for category in categories %}
      <li class="restriction" item="{{ category.get_id() }}"><input type="checkbox"{% if category.get_id() in user.get_restriction('categories') %}checked=""{% endif %}> {{ category.get_name() }}</li>
    {% endfor %}
    </ul>
  </div>
</div>
{% endblock %}