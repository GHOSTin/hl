<!-- begin filters -->
<div class="filters span12">
	<!-- begin filter status -->
	<div class="filter">
	    <label>по статусу заявки</label>
        <select class="filter-content-select-status span12">
            <option value="all">Все заявки</option>
            {% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые', 'working' : 'В работе'} %}
            {% for key, status in statuses %}
                <option value="{{ key }}"
                {% if key == component.filters.status %}
                 selected
                {% endif %}
                >{{status}}</option>
            {% endfor %}
        </select>
	</div>
    <!-- end filter status, begin filter department -->
    <div class="filter">
        <label>по участку</label>
        <select class="filter-content-select-department span12">
            <option value="all">Все участки</option>
            {% if component.departments != false %}
                {% for department in component.departments %}
                    <option value="{{ department.id }}"
                    {% if department.id == component.filters.department_id %}
                    selected
                    {% endif %}
                    >{{ department.name }}</option>
                {% endfor %}
            {% endif %}
        </select>
    </div>
	<!-- end filter department, begin filter street -->
	<div class="filter">
	    <label>по улице и дому</label>
        <select class="filter-content-select-street span12">
            <option value="all">Все улицы</option>
            {% if component.streets != false %}
                {% for street in component.streets %}
                    <option value="{{street.id}}"
                    {% if street.id == component.filters.street_id %}
                        selected
                    {% endif %}
                    >{{street.name}}</option>
                {% endfor %}
            {% endif %}
        </select>
        <select class="filter-content-select-house span12" 
            {% if component.houses|length < 1 %}
                disabled="disabled"
            {% endif %}
            >
            {% if component.houses|length >0 %}
                <option value="all">Все дома</option>
            {% else %}
                <option value="all">Ожидание...</option>
            {% endif %}
            {% for house in component.houses %}
                    <option value="{{house.id}}"
                    {% if house.id == component.filters.house_id %}
                        selected
                    {% endif %}
                    >дом №{{house.number}}</option>
            {% endfor %}
        </select>
	</div>
	<!-- end filter street, begin filter worktype -->
	<div class="filter">
	    <label>по типу работ</label>
        <select class="filter-content-select-work_type span12">
            <option value="all">Все типы</option>
            {% if component.query_work_types != false %}
                {% for query_work_type in component.query_work_types %}
                    <option value="{{query_work_type.id}}"
                    {% if query_work_type.id == component.filters.worktype_id %}
                    selected
                    {% endif %}
                    >{{query_work_type.name}}</option>
                {% endfor %}
            {% endif %}
        </select>
	</div>
     {#
	<!-- end filter worktype, begin filter users -->
	<div class="filter">
	    <label>по пользователю</label>
        <select class="filter-content-select-user span12">
            <option value="all">Все пользователи</option>
            {% if component.users != false %}
                {% for user in component.users %}
                    <option value="{{user.id}}">{{user.lastname}} {{user.firstname}}</option>
                {% endfor %}
            {% endif %}
        </select>
	</div>
	<!-- end filter users -->
    #}
</div>
<!-- end filters -->