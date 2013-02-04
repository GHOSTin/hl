<nav class="timeline">Линия календаря</nav>
<div class="get_search cm">Поиск</div>
<div class="cm">
	<span class="view-toggle-filters cm">Фильтры</span>
	<span class="clear_filters absolute_hide">сбросить</span>
</div>
<div class="filters" style="display:inline-block; vertical-align:top;">
    <!-- end filter date -->
    <!-- begin filter status -->
    <div class="filter">
        <div class="filter-name">по статусу заявки</div>
        <div class="filter-content">
            <label class="view-select">
            <select class="filter-content-select-status">
                <option value="all">Все заявки</option>
                <option value="open">Открытые заявки</option>
                <option value="close">Закрытые заявки</option>
                <option value="reopen">Переоткрытые заявки</option>
                <option value="working">В работе</option>
            </select></label>
        </div>
    </div>
    <!-- end filter status -->
    <!-- begin filter street -->
    <div class="filter">
        <div class="filter-name">по улице и дому</div>
        <div class="filter-content">
            	<label class="view-select">
            		<select class="filter-content-select-street">
            			<option value="">Все улицы</option>
               		</select>
               	</label>
           		<label class="view-select">
           			<select class="filter-content-select-house" style="margin:5px 0px 0px 0px;">
                		<option value="all">Все дома</option>
           			</select>
           		</label>
            </div>
    </div>
    <!-- end filter street -->

    <!-- begin filter department -->
    <div class="filter">
        <div class="filter-name">по участку</div>
        <div class="filter-content">
            <label class="view-select">
            	<select class="filter-content-select-department">
            		<option value="">Все участки</option>
            	</select>
            </label>
        </div>
    </div>
    <!-- end filter department -->
    <!-- begin filter worktype -->
    <div class="filter">
        <div class="filter-name">по типу работ</div>
        <div class="filter-content">
        	<label class="view-select">
        		<select class="filter-content-select-workType">
        			<option value="">Все типы</option>
        		</select>
        	</label>
        </div>
    </div>
    <!-- end filter worktype -->
    <!-- begin filter users -->
    <div class="filter">
        <div class="filter-name">по пользователю</div>
        <div class="filter-content">
        	<label class="view-select">
        		<select class="filter-content-select-user">
                	<option value="all">Все пользователи</option>
            	</select>
            </label>
        </div>
    </div>
    <!-- end filter users -->
</div>
<div class="queries" style="display:inline-block; vertical-align:top;">
{% if queries != false %}
	{% for query in queries %}
		<div class="query get_query_content
		{% if query.status in ['working','open', 'close', 'reopen']%}
			query_status_{{query.status}}
		{% else %}
			query_status_default
		{% endif %}
		" query_id="{{query.id}}">
			<div>
				{% if query.initiator == 'number' %}
					<img src="templates/default/images/icons/xfn-friend.png" />
				{% else %}
					<img src="templates/default/images/icons/home-medium.png" />
				{% endif %}
				<b>№{{query.number}}</b> {{query.time_open|date("H:i d.m.y")}}
				{{query.street_name}}, дом №{{query.house_number}}
			</div>
			{{query.description}}
		</div>
	{% endfor %}
{% endif %}
</div>