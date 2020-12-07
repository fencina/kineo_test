                                 .-..-.
   _____                         | || |
  /____/-.---_  .---.  .---.  .-.| || | .---.
  | |  _   _  |/  _  \/  _  \/  _  || |/  __ \
  * | | | | | || |_| || |_| || |_| || || |___/
    |_| |_| |_|\_____/\_____/\_____||_|\_____)

Moodle - the world's open source learning platform

Moodle <https://moodle.org> is a learning platform designed to provide
educators, administrators and learners with a single robust, secure and
integrated system to create personalised learning environments.

You can download Moodle <https://download.moodle.org> and run it on your own
web server, ask one of our Moodle Partners <https://moodle.com/partners/> to
assist you, or have a MoodleCloud site <https://moodle.com/cloud/> set up for
you.

Moodle is widely used around the world by universities, schools, companies and
all manner of organisations and individuals.

Moodle is provided freely as open source software, under the GNU General Public
License <https://docs.moodle.org/dev/License>.

Moodle is written in PHP and JavaScript and uses an SQL database for storing
the data.

See <https://docs.moodle.org> for details of Moodle's many features.


# Installation

### Requirements
- docker >= v19.03.x
- docker-compose >= v1.13.x

### Steps:

1. Clone this repository

2. Update DB settings at `config-dist.php` file:
```
$CFG->dbname    = 'DB_NAME';     // database name, eg moodle
$CFG->dbuser    = 'DB_USER';   // your database username
$CFG->dbpass    = 'DB_PASSWORD';   // your database password
```

3. Copy contents of `config-dist.php` file into a new file `config.php`

4. Run `docker-compose up -d`. _(Make sure ports 80 and 3306 are not in use)_

5. Go to http://localhost

6. Proceed with the Moodle's wizard installation steps.

7. Enjoy!

_You can access your DB at localhost:3306._

# Poll plugin usage

This plugin allows a user to create a block to take a site-wide vote on a specific topic. Follow the next steps to create your first block:

1. Make sure your role has the capability *block/poll:addinstance* allowed.

2. Go to the home page and add block Poll

3. You can configure your title and text to appear on the block content.

4. Go to the _edit_ link and add a title and a question/topic for your poll.

5. You should also add at least two options so the rest of the users can start to answer it. Once the poll has at least one answer you would not be able to edit it anymore.

6. Once your poll has options a user can answer it in the _answer_ link. Each user can answer only once.

7. If you are the poll's creator or you have answered the question, you will see a _Poll results_ link.


# ToDos

- Enable Moodle's cron
- Add foreign keys to tables
- Allow dynamic options adding
- When deleting a block delete poll related tables
- allow user to see his answer


Developed using [code style](https://docs.moodle.org/dev/Coding_style)