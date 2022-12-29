# Ticket (Detik)


## Instalation
1. Install Web Server XAMPP / WAMP or etc 
2. Clone repository https://github.com/ickokid/detik.git
3. Create database "detik" then Import Database (detik.sql) in your phpmyadmin / database tools
4. Configuration database in config/config.php


## Enviroment
CLI

```
php <PATH>detik\cli\\generate-ticket.php 2 500 
```

API CHECK TICKET

```
URL : <BASE_URL>detik\api\check-ticket.php
METHOD : POST
PARAMS :
event_id
ticket_code
RESPONSE :
ticket_code
status
```