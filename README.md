How to use fixture generation script?
=====================================
If you use `JetBrains PhpStorm` you can use the next hotkeys for run script in CLI `Ctrl+Shift+F10` or run this script 
from your terminal using next command in project root directory:

```bash
php Fixtures.php
```

## Database connection
For configure connection to your database server, you can override the next constants (according to your server settings):

```php 
const DB_HOST = '127.0.0.1';
const DB_PORT = 3306;
const DB_NAME = 'database_name';
const DB_USER = 'username';
const DB_PASSWORD = 'user_password';
```

This script can create the `database`, `tables`, `foreign keys` and `indexes` for you if it needed.
Just call a method `createTables` with `true` for params:
 - Indexes
 - Foreign keys

Example of create database tables `if tables doesn't exists` with `indexes` and `foreign keys`:
```php
$fixturesGenerator->createTables(true, true);
```

## Configure fixtures generation script

You can set any data for fixtures generation script. Just override next presets of php-constants on the top place of the script.

```php
const DEFAULT_FIRST_NAMES = ['Norbert', 'Damon', 'Laverna', 'Annice', 'Brandie', 'Emogene', 'Cinthia', 'Magaret', 'Daria', 'Ellyn', 'Rhoda', 'Debbra', 'Reid', 'Desire', 'Sueann', 'Shemeka', 'Julian', 'Winona', 'Billie', 'Michaela', 'Loren', 'Zoraida', 'Jacalyn', 'Lovella', 'Bernice', 'Kassie', 'Natalya', 'Whitley', 'Katelin', 'Danica', 'Willow', 'Noah', 'Tamera', 'Veronique', 'Cathrine', 'Jolynn', 'Meridith', 'Moira', 'Vince', 'Fransisca', 'Irvin', 'Catina', 'Jackelyn', 'Laurine', 'Freida', 'Torri', 'Terese', 'Dorothea', 'Landon', 'Emelia'];
const DEFAULT_LAST_NAMES = ['Mischke', 'Serna', 'Pingree', 'Mcnaught', 'Pepper', 'Schildgen', 'Mongold', 'Wrona', 'Geddes', 'Lanz', 'Fetzer', 'Schroeder', 'Block', 'Mayoral', 'Fleishman', 'Roberie', 'Latson', 'Lupo', 'Motsinger', 'Drews', 'Coby', 'Redner', 'Culton', 'Howe', 'Stoval', 'Michaud', 'Mote', 'Menjivar', 'Wiers', 'Paris', 'Grisby', 'Noren', 'Damron', 'Kazmierczak', 'Haslett', 'Guillemette', 'Buresh', 'Center', 'Kucera', 'Catt', 'Badon', 'Grumbles', 'Antes', 'Byron', 'Volkman', 'Klemp', 'Pekar', 'Pecora', 'Schewe', 'Ramage'];
const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';
/** Seconds of one calendar year */
const DEFAULT_RANGE_PERIOD = 31556952;
const DEFAULT_EMPLOYEES_QTY = 50;
const DEFAULT_SALARIES_QTY = 100000;
const DEFAULT_TICKETS_QTY = 1000000;
const DEFAULT_FIRST_TICKET_ID = 10000;
```


Lesson 14: SQL-queries
======================

## Make queries

In case of this lesson need make next SQL-queries:

- Get a list of all employees, ordered by last name;
- Get average salary by employee;
- Get total profit for any given vehicle in depot (use `avg()` on this case) AND total exploitation days, sorted by profit descending.
- Get total working days and total profit from this employee on his working days.
- Get employees list born in the MAY month.
- Count total years of working experience for any employee in the company.

Lesson 15: SQL indexes and queries optimization
===============================================

## Execution time without indexes fields

✅ `routes` generation time: 0.0091791152954102

✅ `transports` generation time: 0.013545989990234

✅ `tickets` generation time: 177.89625096321

✅ `transport_tickets` generation time: 210.80657982826

✅ `positions` generation time: 0.016829967498779

✅ `employees` generation time: 0.019996881484985

✅ `timelogs` generation time: 2.3890280723572

✅ `salaries` generation time: 20.843318939209

## Execution time with indexes fields

✅ `routes` generation time: 0.0094509124755859

✅ `transports` generation time: 0.020349979400635

✅ `tickets` generation time: 199.21988797188

✅ `transport_tickets` generation time: 241.34361004829

✅ `positions` generation time: 0.010597944259644

✅ `employees` generation time: 0.022233963012695

✅ `timelogs` and `transport_timelogs` generation time: 5.4249279499054

✅ `salaries` generation time: 24.915431022644
