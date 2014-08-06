{% set params = response.params %}
<!-- begin filters -->
<div class="filters col-xs-12">
	<!-- begin filter status -->
	<div class="filter">
	    <label>по статусу заявки</label>
        <select class="filter-content-select-status form-control">
            <option value="all">Все заявки</option>
            {% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые', 'working' : 'В работе'} %}
            {% for key, status in statuses %}
                <option value="{{ key }}"
                {% if key == params['status'] %}
                 selected
                {% endif %}
                >{{status}}</option>
            {% endfor %}
        </select>
	</div>
    <!-- end filter status, begin filter department -->
    <div class="filter">
        <label>по участку</label>
        <select class="filter-content-select-department form-control">
            <option value="all">Все участки</option>
            {% if response.departments != false %}
                {% for department in response.departments %}
                    {% if params['department'] is empty %}
                        <option value="{{ department.get_id() }}"
                        {% if department.get_id() == params['department'] %}
                        selected
                        {% endif %}
                        >{{ department.get_name() }}</option>
                    {% else %}
                        {% if department.get_id() in params['department'] %}
                            <option value="{{ department.get_id() }}"
                            {% if department.get_id() == params['department'] %}
                            selected
                            {% endif %}
                            >{{ department.get_name() }}</option>
                        {% endif %}
                    {% endif %}
                {% endfor %}
            {% endif %}
        </select>
    </div>
	<!-- end filter department, begin filter street -->
	<div class="filter">
	    <label>по улице и дому</label>
        <select class="filter-content-select-street form-control">
            <option value="all">Все улицы</option>
            {% if response.streets != false %}
                {% for street in response.streets %}
                    <option value="{{ street.get_id() }}"
                    {% if street.get_id() == params['street'] %}
                        selected
                    {% endif %}
                    >{{ street.get_name() }}</option>
                {% endfor %}
            {% endif %}
        </select>
        <select class="filter-content-select-house form-control"
            {% if response.houses|length < 1 %}
                disabled="disabled"
            {% endif %}
            >
            {% if response.houses|length >0 %}
                <option value="all">Все дома</option>
            {% else %}
                <option value="all">Ожидание...</option>
            {% endif %}
            {% for house in response.houses %}
                    <option value="{{ house.get_id() }}"
                    {% if house.get_id() == params['house'] %}
                        selected
                    {% endif %}
                    >дом №{{ house.get_number() }}</option>
            {% endfor %}
        </select>
	</div>
	<!-- end filter street, begin filter worktype -->
	<div class="filter">
	    <label>по типу работ</label>
        <select class="filter-content-select-work_type form-control">
            <option value="all">Все типы</option>
            {% if response.query_work_types != false %}
                {% for query_work_type in response.query_work_types %}
                    <option value="{{ query_work_type.get_id() }}"
                    {% if query_work_type.get_id() == params['work_type'] %}
                    selected
                    {% endif %}
                    >{{ query_work_type.get_name() }}</option>
                {% endfor %}
            {% endif %}
        </select>
	</div>
     {#
	<!-- end filter worktype, begin filter users -->
	<div class="filter">
	    <label>по пользователю</label>
        <select class="filter-content-select-user form-control">
            <option value="all">Все пользователи</option>
            {% if response.users != false %}
                {% for user in response.users %}
                    <option value="{{user.id}}">{{user.lastname}} {{user.firstname}}</option>
                {% endfor %}
            {% endif %}
        </select>
	</div>
	<!-- end filter users -->
    #}
</div>
<!-- end filters -->