{%set query = component.queries[0] %}
{% if query.initiator == 'number' %}
	{% if component.numbers.numbers != false %}
		{% set number = component.numbers.numbers[component.numbers.structure[query.id].true[0]] %}
	{% endif %}
{% endif %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Печатная форма заявки №{{query.number}} от {{query.time_open|date('H:i d.m.Y')}}</title>
			<style>
				body {font-size:10pt; margin: 0mm 0mm 0mm 0mm; padding: 0mm 0mm 0mm 0mm;}
				.main-block table {border-collapse: collapse; border-spacing: 0mm;}
				.ttle {font-size:14pt; font-weight:900;}
				.main {border:1px solid grey; width:200mm; padding: 0mm 0mm 5mm 0mm;}
				.main-block {width:194mm; padding:1mm 2mm 2mm 2mm; }
				@media print{
					.navbar{
						display:none;
						visibility: hidden;
					}
				}
			</style>
		</head>
		<body>
        <header>
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="navbar-inner">
                    <a class="btn" href="#" onclick="window.print(); return false;">
                        <i class="icon-print"></i>
                         Печать
                    </a>
                    <a class="btn" onclick="window.close();">
                        <i class="icon-remove"></i>
                         Отмена
                    </a>
                </div>
            </nav>
        </header>
		<button id="b" >Печать</button>
		<div class="main">
			<!-- begin 1 block -->
			<div class="main-block">
				<div class="ttle">Заявка №{{query.number}} от {{query.time_open|date('H:i d.m.Y')}}</div>
				<div class="ttle">
						{{query.street_name}}, дом №{{query.house_number}}
						{% if query.initiator == 'number' %}
							{% if component.numbers.numbers != false %}
								, кв. {{number.flat_number}} (л/с №{{number.number}}, {{number.fio}})
							{% endif %}
						{% endif %}
				</div>
				<div class="ttle">{{query.description}}</div>
				<table style="padding:5mm 0mm 0mm 0mm;">
				{% for i in 1..6 %}
					<tr>
								<td>{{i}}</td>
								<td style="border-bottom: 1px solid black; width:70%; height:8mm;"></td>
								<td style="height:8mm;">с</td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>		
								<td style="height:8mm;">до</td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>		
								<td style="height:8mm;">-</td>
								<td style="border-bottom: 1px solid black; width:15%; height:8mm;"></td>		
								<td style="height:8mm;">г.</td>													
							</tr>
				{% endfor %}
				</table>
				<div style="font-size:6pt; margin: 5mm 0mm 0mm 0mm;">Все необходимые работы выполнены в срок, качественно и в полном объеме. Оплата за выполненые работы с Заявителя не взималась. Заявитель не имеет претензий, связанных с исполнением заявки. С правилами и условиями эффективного и безопасного использования результатов оказанных услуг ( выполненных работ ) заявитель ознакомлен.</div>
				<table style="margin: 2mm 0mm 0mm 0mm;">
					<tr>
						<td>Работы </td>
						<td> принял</td>
						<td style="border-bottom: 1px solid black; width:60%;"></td>
						<td>Подпись</td>
						<td style="border-bottom: 1px solid black; width:20%;"></td>
						<td>Телефон</td>
						<td style="border-bottom: 1px solid black; width:20%;"></td>
					</tr>
				</table>		
		</div>
		<!-- end 1 block, begin 2 block -->
		<div class="main-block">
		{% if query.initiator == 'number' %}
			<div>Владелец {{number.fio}}</div>
		{% endif %}
		{% if query.contact_fio != false or query.contact_telephone != false or query.contact_cellphone != false%}
			<div>Заявку подал:
				{% if query.contact_fio != false %}
					{{query.contact_fio}}
				{% endif %}
				{% if query.contact_telephone != false %}
					тел. {{query.contact_telephone}}
				{% endif %}
				{% if query.contact_cellphone != false %}
					сот. {{query.contact_cellphone}}
				{% endif %}
			</div>
		{% endif %}
			<table style="margin:2mm 0mm 0mm 0mm; width:194mm">
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;">Сотрудники: диспетчер
					{% if component.users != false %}
						{% set creator = component.users.users[component.users.structure[query.id].creator[0]] %}
						{{creator.lastname}} {{creator.firstname}} {{creator.middlename}}
					{% endif %}
					</td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;">Отметки:</td>
				</tr>
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;"></td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;"></td>
				</tr>
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;"></td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;"></td>
				</tr>			
			</table>
		</div>
	<!-- end 2 block -->
	</div></body></html>
{% endif %}