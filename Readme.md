# Shrizzer - easy link sharing

The free, registration less, anonymously link timeline you ever waited for. This software gives you the possibility to create anonymous link timelines like you know it from doodle. Just paste a link and you start a new timeline under a secret link. Everyone who knows this link can participate on
this timeline. 

## Features

- Create new timelines with embeded content like you know from facebook etc
- Everyone who knows the secret link can participate on the timeline
- Add anonymouse comments and likes to media on a timeline
- Get updates via Email for timeline changes
- Responsive template

## Screenshots

### Homepage

![Homepage](/screenshots/homepage.png)

This picture shows the page after you enter the tool. You can just paste a link and new timeline will be generated instantly.

### Timeline 

![Timeline](/screenshots/timeline.png)

Here you see the timeline view. You can add new media to the timeline by adding some url and "Post it", you can register yourself to updates fot this timeline or change the settings. Everyone who knows the link can add new links or change the settings.

## Installation

Shrizzer is build upon laravel with a docker container so you need to do the following steps to get this software up and running:

- Install docker and docker-compose
- Boot the container with: 
```
docker-compose up
```
- After that you need to run composer and migrations inside the PHP box
```
docker-compose exec php bash
composer install
mv .env.example .env
```
- Create a new database called shrizzer inside mysql. See section sequel pro to connect to the mysql box. **make a backup of your data if you docker mysql is not empty**
- Run migrations inside the PHP Box
```
docker-compose exec php bash
./artisan migrate
```
- Now change the /etc/hosts file with the following record
```
...
127.0.0.1       shrizzer.dev
...
```

If you now type shrizzer.dev inside you browser you should be able to see the shrizzer homepage.

If you want to change something to the frontend checkout laravel asset management: https://laravel.com/docs/5.4/mix

### SequelPro

If you want to use sequel pro to look inside the database just use this params:

```
Name: shrizzer.dev
Host: 127.0.0.1
User: root
PW: root
``` 

### Send Mails

IF you want to send real mails to subscribers you need to change the laravel mail settings in your environment file.
If you want to send the notifications automatically just enable the laravel cron https://laravel.com/docs/5.4/scheduling

## API Routes

### Sessions

* Get Session by key: `GET /api/v1/sessions/{key}`
* Create session: `POST /api/v1/sessions/`
* Update session: `PUT /api/v1/sessions/{key}`
* Delete session: `DELETE /api/v1/sessions/{key}`

### Session Urls

* Get all urls: `GET /api/v1/sessions/{key}/urls`
* Create urls: `POST /api/v1/sessions/{key}/urls`
* Update urls: `PUT /api/v1/sessions/{key}/urls/{urlId}`
* Delete urls: `DELETE /api/v1/sessions/{key}/urls/{urlId}`

### Comments

* Get comments by key: `GET /api/v1/sessions/{sessionKey}/urls/{urlKey}/comments`
* Create comments: `POST /api/v1/sessions/{sessionKey}/urls/{urlKey}/comments`
* Delete session: `DELETE /api/v1/sessions/{sessionKey}/urls/{urlKey}/comments/{id}`

### Notifications

* Get all notifications by session: `GET /api/v1/sessions/{sessionKey}/notifications`

### Session Subscribers

* Get subscribers by session: `GET /api/v1/sessions/{sessionKey}/users`
* Create subscribers: `POST /api/v1/sessions/{sessionKey}/users`
* Delete subscriber: `DELETE /api/v1/sessions/{sessionKey}/users/{id}`

## License

Shrizzer is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
