# Installation

### Requirements
- docker >= v19.03.x
- docker-compose >= v1.13.x

### Steps:

1. Clone this repository

2. Copy contents of `config-dist.php` file into a new file `config.php`

3. Update DB settings at `config.php` file:
```
$CFG->dbname    = 'DB_NAME';     // database name, eg moodle
$CFG->dbuser    = 'DB_USER';   // your database username
$CFG->dbpass    = 'DB_PASSWORD';   // your database password
```

4. Run `docker-compose up -d`. _(Make sure ports 80 and 3306 are not in use)_

5. Create the database with the name previously configured at `config.php`. _You can access your DB at localhost:3306._

6. Go to http://localhost

7. Proceed with the Moodle's wizard installation steps.

8. Enjoy!

# Poll plugin usage

This plugin allows a user to create a block to take a site-wide vote on a specific topic. Follow the next steps to create your first block:

1. Make sure your role has the capability *block/poll:addinstance* allowed.

2. Go to the home page and add a Poll block.

3. You can configure the block content's title and text.

4. Go to the _Edit_ link and add a title and a question/topic for your poll.

5. You should also add at least two options so the rest of the users can start to answer it. Once the poll has at least one answer you would not be able to edit it anymore.

6. Once your poll has options a user can answer it going to the _answer_ link. Each user can answer only once.

7. If you are the poll's creator or you have answered the question, you will see a _Poll results_ link.

# ToDos

- Enable Moodle's cron
- Add foreign keys to tables
- Allow dynamic options adding
- When deleting a block delete poll related tables
- Allow user to see his answer


Developed using [code style](https://docs.moodle.org/dev/Coding_style)