<!-- begin left block -->
<div style="display:inline-block; vertical-align:top;">
    <div class="get_search cm">Поиск</div>
    <div>
        <span class="view-toggle-filters">Фильтры</span>
        <span class="cm clear_filters absolute_hide">сбросить</span>
    </div> 
    <!-- begin filters -->
    <div class="filters">  
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
        <!-- end filter status, begin filter street -->
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
        <!-- end filter street, begin filter department -->
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
        <!-- end filter department, begin filter worktype -->
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
        <!-- end filter worktype, begin filter users -->
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
    <!-- end filters -->
</div>
<!-- end left block, begin right block -->
<div style="display:inline-block; vertical-align:top;">
    <!-- begin timeline -->
    {% set day = timeline %}
    {% set month = timeline|date('n') %}
    {% set months = {1 : 'Январь', 2 : 'Февраль', 3 : 'Март', 4: 'Апрель', 
        5 : 'Май', 6 : 'Июнь', 7 : 'Июль', 8 : 'Август', 9 : 'Сентябрь',
        10 : 'Октябрь', 11 : 'Ноябрь', 12 : 'Декабрь'} %}
    <nav class="timeline">
        <div class="timeline-month">
            {% if month in months|keys %}
                {{months[month]}}
            {% endif %}
            {{timeline|date('Y')}}</div>
        {% for i in range(1, timeline|date('t')) %}
            <div class="timeline-day" time="{{day}}">{{i}}</div>
            {% set day = day + 86400 %}
        {% endfor %}
    </nav>
    <!-- end timeline, begin queries -->
    <div class="queries">
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
        {% else %}
            Нет заявок
        {% endif %}
    </div>
    <!-- end queries -->
</div>
<!-- end right block -->