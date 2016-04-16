# URL Notes

This is tiny web program share url with co-workers.

## Features

- Shere url note with description and tags.
- Comment to note.
- Notify with email(Note and comment).
- Scriptlet to share url.


## Install

This use Laravel framework so you should indication of Laravel.

You should use composer, npm of nodejs and gulp of nodejs program.

Each program, I don't descript. You can see individual tutorial on site of each lib.

If you have all dependencies, run below commands.

<pre>
composer install
npm install
gulp
</pre>

And, you set .env file.

<pre>
cp .env.example .env
</pre>

You should fill database and email server infomation. Email server's `MAIL_DRIVER` will be filled with `log`, `smtp`, etc... Full list is on Laravel Document Mail section.

After fill database information, run below.

<pre>
php artisan migrate
</pre>

You can run the program, type this command.

<pre>
php artisan serve
</pre>


## Migrate from Wunderlist

Our team use wunderlist to share url. So first of our task is migrate the data on Wunderlist. Wunderlist support exporting. You can see it Account section.

After export, you can import the items to URL Notes by artisan. By the way, you should fill `$user_match` array on `app\Console\Commands\ImportFromWunderlist.php` line 15. the format is there by comments. If you complete it, run a command.

<pre>
php artisan import:wunderlist
</pre>


## Test

Lalavel provide default test set. We use it. So, if you installed phpunit, you can test simply by type `phpunit`.

<pre>
$ phpunit

PHPUnit 5.2.12 by Sebastian Bergmann and contributors.

...                                                                3 / 3 (100%)

Time: 492 ms, Memory: 22.25Mb

OK (3 tests, 27 assertions)
</pre>

