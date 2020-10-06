# SkyLive-webportal


## Setup

First, you need to [install GIT](https://git-scm.com/downloads) and learn the basics (clone, commit, pull, push commands). If you're a Windows user, I recommend to use [Tortoisegit](https://tortoisegit.org/) or any other git gui.

You also need some king of home-webserver, so you can open SkyLive on localhost. For Windows users I recommend [WAMP.NET](https://wamp.net/), for Linux users apache2 and MySQL would be enough.

Create an empty project folder. Make sure you're able to open PHP files from this folder in the browser. Clone (with git) this repo into the folder.

There is a `skylive.sql` file in the repo. You need to create a new database, a new db-user with a password, and import `skylive.sql` it to mysql (with PHPMyAdmin or command line).

Copy `config_default.php` to `config.php` and follow the comments. Edit the file and save. (Never upload config.php to github, it is a secret. Don't worry, tihs file is already ignored by git)

Have fun!