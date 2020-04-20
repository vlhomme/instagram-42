# My very first big project
![](insta1.gif)
## How long did it take ?
2 months

## challenge accepted ?
- [x] no framework and no external libraries : just vanilla php javascript and css
- [x] own local webserver using docker compatible with any os
- [x] no security breaks (SQL injection,  good Access Control with different user roles, XSS, no CORS error, file upload ...)

## What did I learn ?
  * object-oriented programming in php
  * javascript
  * ajax
  * responsive design and advanced CSS tricks
  * authentication process in backend and frontend
  * docker LAMP
  * a feel of what should be a REST api and implementation of CRUD
  * how to send email with php
  * how to handle webcam, upload images and edit them with php and javascript
  * DOM Manipulation
  * SQL Debugging
  * Cross Site Request Forgery
  * Cross Origin Resource Sharing


![](insta2.gif)

## What is this project ?
This is an instagram like application that has 3 main features :
### 1. User Features
A user can register. A mail of confirmation is send to validate his registration. A user can login. A user can modify his information and password once login. A user can reset his password if he forgets it with his mail address.
### 2. Gallery Features
A public gallery is available without connection needed (with infinite pagination of all the pictures in the website). Each user has its own gallery. Any user, once login can comment and like any pictures. He can also like comments. A user can only delete his comments/likes/posts.
![](insta3.gif)
### 3. Editing Features
A user can take a picture with his webcam or upload an image from his computer. He can add a sticker (live preview with the webcam) or/and a filter to his picture before publishing it.

For more information about the project please see the pdf subject available in french and english at ![subject](https://github.com/nepriel/instagram-42/tree/master/subject "subject").

### How do I run it on my laptop ?
You will need docker running on your machine.
- If you are on mac or linux just clone the repo cd into the repo and run deploiement.sh.
- If you are on windows you will need to create a new folder in yout desktop called 'new' then copy the 'www' ![www](https://github.com/nepriel/instagram-42/tree/master/www) folder in it. You will need to change this file a bit according to your windows username ![docker-compose](https://github.com/nepriel/instagram-42/blob/master/www/camagru/DOCKER/docker-compose.yml) like this :
```
        volumes:
            - C:\Users\myusername\Desktop\New\www:/var/www/html/
            # - ~/Desktop/New/www:/var/www/html/
```
```
        volumes:
            # - ~/Desktop/New/dump:/docker-entrypoint-initdb.d
            # - ~/Desktop/New/conf:/etc/mysql/conf.d
            - C:\Users\myusername\Desktop\New\dump:/docker-entrypoint-initdb.d
            - C:\Users\myusername\Desktop\New\conf:/etc/mysql/conf.d
            - persistent:/var/lib/mysql
```
![alt text](https://github.com/nepriel/instagram-42/blob/master/hello.PNG "result of evaluation of project")
