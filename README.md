# Ticket (Detik)


## How to Use

1. Install Web Server XAMPP / WAMP or etc 
2. Clone repository https://github.com/ickokid/detik.git
3. Create database "detik" then Import Database (detik.sql) in your phpmyadmin / database tools
4. Configuration database in config/config.php
5. Run Script CLI then API will be Used

## Usage CLI example
CLI

```
<PATH_PHP> <PATH_CODE>detik/cli/generate-ticket.php 2 500 (LINUX) 
<PATH_PHP> -f "<PATH_CODE>detik\cli\generate-ticket.php" 2 500 (WINDOWS)

Sample : C:\wamp64\bin\php\php7.2.33\php.exe -f "C:\wamp64\www\detik\cli\generate-ticket.php" 2 500
```

## Usage API & Documentation
1. Import Detik.postman_collection.json to Postman
2. Run / Test API Function Check Ticket & Update Ticket

API CHECK TICKET

```
URL : <BASE_URL>detik\api\check-ticket.php
METHOD : POST
AUTORIZATION : Basic Auth
HEADER : X-Api-Key
PARAMS :
event_id
ticket_code
RESPONSE :
ticket_code
status
```

API UPDATE TICKET

```
URL : <BASE_URL>detik\api\update-ticket.php
METHOD : POST
AUTORIZATION : Basic Auth
HEADER : X-Api-Key
PARAMS :
event_id
ticket_code
status
RESPONSE :
ticket_code
status
updated_at
```