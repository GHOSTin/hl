{% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые', 'working' : 'В работе'} %}
<!-- begin filters -->
<div class="filters col-xs-12">
  <!-- begin filter status -->
  <div class="filter">
    <label>по статусу заявки</label>
    <select class="filter-content-select-status form-control">
      <option value="all">Все заявки</option>
    {% for key, status in statuses %}
      <option value="{{ key }}"{% if key == params['status'] %} selected{% endif %}>{{status}}</option>
    {% endfor %}
    </select>
	</div>
  <!-- end filter status, begin filter query_types -->
  <div class="filter">
    <label>по типу заявки</label>
      <select class="filter-content-select-query_type form-control">
        <option value="all">Все типы</option>
      {% for query_type in query_types %}
        <option value="{{ query_type.get_id() }}"{% if query_type.get_id() == params['query_types'] %} selected{% endif %}>{{ query_type.get_name() }}</option>
      {% endfor %}
      </select>
  </div>
  <!-- end filter query_types, begin filter department -->
  <div class="filter">
    <label>по участку</label>
    <select class="filter-content-select-department form-control">
      <option value="all">Все участки</option>
    {% for department in departments %}
      <option value="{{ department.get_id() }}"{% if department.get_id() == params['department'] %} selected{% endif %}>{{ department.get_name() }}</option>
    {% endfor %}
    </select>
  </div>
	<!-- end filter department, begin filter street -->
	<div class="filter">
    <label>по улице и дому</label>
    <select class="filter-content-select-street form-control">
      <option value="all">Все улицы</option>
    {% for street in streets %}
      <option value="{{ street.get_id() }}"{% if street.get_id() == params['streets'] %} selected{% endif %}>{{ street.get_name() }}</option>
    {% endfor %}
    </select>
    <select class="filter-content-select-house form-control"{% if houses|length < 1 %} disabled="disabled"{% endif %}>
    {% if houses|length >0 %}
      <option value="all">Все дома</option>
    {% else %}
      <option value="all">Ожидание...</option>
    {% endif %}
    {% for house in houses %}
      <option value="{{ house.get_id() }}"{% if house.get_id() == params['house'] %} selected{% endif %}>дом №{{ house.get_number() }}</option>
    {% endfor %}
    </select>
	</div>
	<!-- end filter street, begin filter worktype -->
	<div class="filter">
    <label>по типу работ</label>
    <select class="filter-content-select-work_type form-control">
      <option value="all">Все типы</option>
    {% for query_work_type in query_work_types %}
      <option value="{{ query_work_type.get_id() }}"{% if query_work_type.get_id() == params['work_type'] %} selected{% endif %}>{{ query_work_type.get_name() }}</option>
    {% endfor %}
    </select>
	</div>
</div>
<!-- end filters -->