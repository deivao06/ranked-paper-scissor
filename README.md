
# Ranked Paper Scissor

A open-source ranked rock paper scissor game using Laravel, VueJS and Websocket

## Screenshots

![App Screenshot](https://imgur.com/a/9VUI7wo)


## Running on Localhost

Install Composer dependencies:
```bash
  composer install
```

Install NPM dependencies:
```bash
  npm install
```

Run NPM in dev mode:
```bash
  npm run dev
```

Run laravel migrations:
```bash
  php artisan migrate
```

Run laravel artisan local server:
```bash
  php artisan serve
```
Tip: If you want to run a server on your local network to test the websocket functionality, run it using the following command:
```bash
  php artisan serve --host 0.0.0.0 --port {any port that you want (without the brackets)}
```
With this command any browser using the same network as you will be able to access the project using http://youripv4addres:port

You will have to change websocket url in front-end too.

Finally run the websocket server:
```bash
  php artisan run:websocket
```
This command will run the websocket server in port 5050.
## TODO

- [X]  ~User login~
- [X]  ~User registration~
- [X]  ~Online normal game matchmaking~
- [X]  ~Base game mechanics~
- [ ]  User game history
- [ ]  Online ranked game matchmaking
- [ ]  User ranked points (used to balance ranked matchmaking)
- [ ]  Leaderboard
- [ ]  Custom game creation
- [ ]  User games info (total games, total wins, total loses)
- [ ]  User friend system (maybe?)

## Programming language versions

**PHP:** 7.4.29

**Laravel Framework:** 8.83.26


## License

[MIT](https://choosealicense.com/licenses/mit/)