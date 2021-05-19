<?php
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpSameParameterValueInspection */
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = 3380;
const DB_NAME = 'ck_electrotrance_depot';
const DB_USER = 'root';
const DB_PASSWORD = 'root';

const DEFAULT_POSITIONS = ['Depot Chief', 'Accountant Manager', 'Driver', 'Mechanic', 'Dispatcher', 'System Administrator', 'Office Manager', 'Route Operator', 'Controller', 'Depot Guardian', 'Operator Of Call-center'];
const DEFAULT_FIRST_NAMES = ['Norbert', 'Damon', 'Laverna', 'Annice', 'Brandie', 'Emogene', 'Cinthia', 'Magaret', 'Daria', 'Ellyn', 'Rhoda', 'Debbra', 'Reid', 'Desire', 'Sueann', 'Shemeka', 'Julian', 'Winona', 'Billie', 'Michaela', 'Loren', 'Zoraida', 'Jacalyn', 'Lovella', 'Bernice', 'Kassie', 'Natalya', 'Whitley', 'Katelin', 'Danica', 'Willow', 'Noah', 'Tamera', 'Veronique', 'Cathrine', 'Jolynn', 'Meridith', 'Moira', 'Vince', 'Fransisca', 'Irvin', 'Catina', 'Jackelyn', 'Laurine', 'Freida', 'Torri', 'Terese', 'Dorothea', 'Landon', 'Emelia'];
const DEFAULT_ROUTES_LIST = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '14', '15', '18', '1A', '7A', '8P'];
const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';
const DEFAULT_RANGE_PERIOD = 31556952;
const DEFAULT_EMPLOYEES_QTY = 50;
const DEFAULT_SALARIES_QTY = 100000;
const DEFAULT_TICKETS_QTY = 1000000;
const DEFAULT_FIRST_TICKET_ID = 10000;

class Fixtures
{
    private ?PDO $connection = null;
    private DateTime $currentDT;

    /**
     * Fixtures constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->currentDT = new DateTime('now');

        if (null === $this->connection) {
            try {
                $this->connection = $this->dbConnect();
            } catch (PDOException $e) {
                if ($e->getCode() === 1049) {
                    $this->connection = $this->createDatabase(DB_NAME);
                } else {
                    $stacktrace = [
                        'msg' => $e->getMessage(),
                        'info' => $e->errorInfo,
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ];
                    throw new Exception(print_r($stacktrace), $e->getCode());
                }
            }

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

//---- HELPERS ---------------------------------------------------------------------------------------------------------

//    private function queryExec(string $query, int $recordsNum, array $params): void {}

    private function printExecTime(float $begin, string $msg = null, $beforeCh = "\u{2705}"): void
    {
        $time = (microtime(true) - $begin) . "\n";
        echo (null === $msg) ? "$beforeCh Total execution time: $time" : "$beforeCh $msg: $time";
    }

    private function randomFirstName(): string
    {
        return DEFAULT_FIRST_NAMES[array_rand(DEFAULT_FIRST_NAMES)];
    }

    private function randomLastName(): string
    {
        $randomLastNames = ['Mischke', 'Serna', 'Pingree', 'Mcnaught', 'Pepper', 'Schildgen', 'Mongold', 'Wrona', 'Geddes', 'Lanz', 'Fetzer', 'Schroeder', 'Block', 'Mayoral', 'Fleishman', 'Roberie', 'Latson', 'Lupo', 'Motsinger', 'Drews', 'Coby', 'Redner', 'Culton', 'Howe', 'Stoval', 'Michaud', 'Mote', 'Menjivar', 'Wiers', 'Paris', 'Grisby', 'Noren', 'Damron', 'Kazmierczak', 'Haslett', 'Guillemette', 'Buresh', 'Center', 'Kucera', 'Catt', 'Badon', 'Grumbles', 'Antes', 'Byron', 'Volkman', 'Klemp', 'Pekar', 'Pecora', 'Schewe', 'Ramage'];
        return $randomLastNames[array_rand($randomLastNames)];
    }

    /**
     * Get formatted random date by defined range values
     * and specified period (by default period equal one year)
     *
     * @throws Exception
     */
    private function randomDateByRange(int $minRangeValue, int $maxRangeValue, string $format = null, int $period = null): string
    {
        $period = (!$period) ? DEFAULT_RANGE_PERIOD : $period;
        if (isset($minRangeValue, $maxRangeValue)) {
            $minRangeTimestamp = $this->currentDT->getTimestamp() - ($period * $minRangeValue); // 45
            $maxRangeTimestamp = $this->currentDT->getTimestamp() - ($period * $maxRangeValue); // 16
        } else {
            throw new Exception('Undefined range time values.', 200);
        }
        $randomRangedTimestamp = random_int($minRangeTimestamp, $maxRangeTimestamp);

        return (isset($format))
            ? date($format, $randomRangedTimestamp)
            : date(DEFAULT_DATE_FORMAT, $randomRangedTimestamp);
    }

    private function fakeSerialNumber(int $len): string
    {
        $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($x, (int)round($len / strlen($x), 0))), 1, $len);
    }

//---- DDL -------------------------------------------------------------------------------------------------------------

    private function dbConnect(): PDO
    {
        return new PDO("mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, []);
    }

    private function createDatabase(string $name): PDO
    {
        $q = <<<SQL
            CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
        SQL;

        try {
            $this->connection = new PDO("mysql:host=" . DB_HOST . ":" . DB_PORT, DB_USER, DB_PASSWORD);
            $this->connection->exec($q) or die(print_r($this->connection->errorInfo(), true));
        } catch (PDOException $e) {
            die("\u{1F6A8} DB ERROR: " . $e->getMessage());
        }

        return $this->dbConnect();
    }

    public function createTables(bool $indexes = false, bool $fk = false): bool
    {
        $firstId = DEFAULT_FIRST_TICKET_ID;
        $currentDT = $this->currentDT->format(DEFAULT_DATE_FORMAT);
        $tables = [
            'positions' => <<<SQL
                CREATE TABLE IF NOT EXISTS positions
                (
                    `position_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `title`       varchar(60)      NOT NULL UNIQUE,
                    `salary`      DECIMAL(8, 2)    NOT NULL DEFAULT 9000.00,
                    `created_at`  DATETIME         NOT NULL DEFAULT '$currentDT',
                    `updated_at`  DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`position_id`)
                );
                SQL,
            'employees' => <<<SQL
                CREATE TABLE IF NOT EXISTS employees
                (
                    `employee_id`   INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `first_name`    varchar(25)      NOT NULL,
                    `last_name`     varchar(25)      NOT NULL,
                    `position_id`   INT(11) unsigned NOT NULL,
                    `date_of_birth` DATETIME         NOT NULL DEFAULT '1986-05-01 09:45:00',
                    `hired_at`      DATETIME         NOT NULL DEFAULT '2021-03-25 10:00:00',
                    `updated_at`    DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`employee_id`)
                );
                SQL,
            'transports' => <<<SQL
                CREATE TABLE IF NOT EXISTS transports
                (
                    `transport_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `serial`       VARCHAR(36)      NOT NULL,
                    `created_at`   DATETIME         NOT NULL,
                    `updated_at`   DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`transport_id`)
                );
                SQL,
            'routes' => <<<SQL
                CREATE TABLE IF NOT EXISTS routes
                (
                    `route_id`   INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `code`       varchar(3)       NOT NULL UNIQUE,
                    `created_at` DATETIME         NOT NULL,
                    `updated_at` DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`route_id`)
                );
                SQL,
            'tickets' => <<<SQL
                CREATE TABLE IF NOT EXISTS tickets
                (
                    `ticket_id`  BIGINT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `code`       VARCHAR(2)          NOT NULL DEFAULT 'AC',
                    `price`      DECIMAL(2, 1)       NOT NULL DEFAULT 5.00,
                    `created_at` DATETIME            NOT NULL DEFAULT '$currentDT',
                    PRIMARY KEY (`ticket_id`)
                );
                SQL,
            'transport_tickets' => <<<SQL
                CREATE TABLE IF NOT EXISTS transport_tickets
                (
                    `transport_id` INT(11) unsigned    NOT NULL,
                    `ticket_id`    BIGINT(11) unsigned NOT NULL,
                    `sold_at`      DATETIME            NULL DEFAULT NULL
                );
                SQL,
            'timelogs' => <<<SQL
                CREATE TABLE IF NOT EXISTS timelogs
                (
                    `timelog_id`   INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `time_spent`   DECIMAL(2, 1)    NOT NULL DEFAULT 7.00,
                    `employee_id`  INT(11) unsigned NOT NULL,
                    `transport_id` INT(11) unsigned NOT NULL,
                    `route_id`     INT(11) unsigned NOT NULL,
                    `created_at`   DATETIME         NOT NULL DEFAULT '1994-03-03 09:00:00',
                    `updated_at`   DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`timelog_id`)
                );
                SQL,
            'salaries' => <<<SQL
                CREATE TABLE IF NOT EXISTS salaries
                (
                    `salary_id`    INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `employee_id`  INT(11) unsigned NOT NULL,
                    `position_id`  INT(11) unsigned NOT NULL DEFAULT 3,
                    `created_at`   DATE             NOT NULL DEFAULT '1994-03-03',
                    PRIMARY KEY (`salary_id`)
                );
                SQL,
        ];
        $this->connection->beginTransaction();
        foreach ($tables as $table => $sql) {
            $begin = microtime(true);
            $this->connection->prepare($sql)->execute(['firstId' => $firstId]);
            $this->printExecTime($begin, "Table `$table` created");
            unset($tables[$table]);
        }
        $this->connection->prepare(<<<SQL
            ALTER TABLE tickets AUTO_INCREMENT = $firstId;
            SQL
        )->execute();

//------ Create indexes for database tables ----------------------------------------------------------------------------
        if (true === $indexes) {
            $this->connection->prepare(<<<SQL
            CREATE UNIQUE INDEX transport_serial_index ON `transports` (serial DESC);
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                CREATE INDEX ticket_code_index ON `tickets` (code DESC);
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                CREATE UNIQUE INDEX position_title_index ON `positions` (title DESC);
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                CREATE INDEX employees_firstname_index ON `employees` (first_name DESC);
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                CREATE INDEX employees_lastname_index ON `employees` (last_name DESC);
            SQL
            )->execute();
        }

//------ Create FK for database tables ---------------------------------------------------------------------------------
        if (true === $fk) {
            $this->connection->prepare(<<<SQL
            ALTER TABLE transport_tickets ADD CONSTRAINT `transport_tickets_fk0` FOREIGN KEY (transport_id)
                REFERENCES transports (transport_id) ON DELETE NO ACTION;
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                ALTER TABLE transport_tickets  ADD CONSTRAINT `transport_tickets_fk1` FOREIGN KEY (ticket_id)
                    REFERENCES tickets (ticket_id) ON DELETE NO ACTION;
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                ALTER TABLE employees ADD CONSTRAINT `employees_fk0` FOREIGN KEY (position_id) 
                    REFERENCES positions (position_id) ON DELETE CASCADE;
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                ALTER TABLE timelogs ADD CONSTRAINT `timelogs_fk0` FOREIGN KEY (employee_id) 
                    REFERENCES employees (employee_id) ON DELETE CASCADE;
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                ALTER TABLE timelogs ADD CONSTRAINT `timelogs_fk1` FOREIGN KEY (transport_id) 
                    REFERENCES transports (transport_id) ON DELETE CASCADE;
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                ALTER TABLE timelogs ADD CONSTRAINT `timelogs_fk2` FOREIGN KEY (route_id) 
                    REFERENCES routes (route_id) ON DELETE CASCADE;
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                ALTER TABLE `salaries`
                    ADD CONSTRAINT `salaries_fk0` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;
            SQL
            )->execute();
            $this->connection->prepare(<<<SQL
                ALTER TABLE `salaries`
                    ADD CONSTRAINT `salaries_fk1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`) ON DELETE CASCADE;
            SQL
            )->execute();
        }

        $this->connection->commit();

        return empty($tables);
    }

//---- DML -------------------------------------------------------------------------------------------------------------

    private function routesGenerator(array $routes = []): void
    {
        $begin = microtime(true);
        $q = $this->connection->prepare(<<<SQL
            INSERT INTO `routes` (code, created_at) VALUES (:routeCode, :createdAt);
            SQL
        );
        $this->connection->beginTransaction();
        foreach ($routes as $route) {
            $q->execute([
                'routeCode' => $route,
                'createdAt' => $this->currentDT->format(DEFAULT_DATE_FORMAT),
            ]);
        }
        $this->connection->commit();
        $this->printExecTime($begin, '`routes` generation time');
    }

    /**
     * @throws Exception
     */
    private function transportGenerator(int $qty, string $serialNum = null): void
    {
        $begin = microtime(true);
        $q = $this->connection->prepare(<<<SQL
                INSERT INTO transports (serial, created_at)
                VALUES (:serialNum, :createdAt)
            SQL
        );
        $q->bindParam(':createdAt', $createdAt);
        $q->bindParam(':serialNum', $serialNum);
        $this->connection->beginTransaction();
        for ($i = 0; $i < $qty; $i++) {
            $serialNum = $this->fakeSerialNumber(36);
            $createdAt = $this->randomDateByRange(34, 2);
            $q->execute();
        }
        $this->connection->commit();
        $this->printExecTime($begin, '`transports` generation time');
    }

    /**
     * @throws Exception
     */
    private function ticketsGenerator(int $firstId, int $qty): void
    {
        $begin = microtime(true);
        $createdAt = $this->currentDT->format(DEFAULT_DATE_FORMAT);
        $q = $this->connection->prepare(<<<SQL
                INSERT INTO tickets (created_at) VALUES (:createdAt);
            SQL
        );
        $q->bindParam(':createdAt', $createdAt);
        $this->connection->beginTransaction();
        for ($i = 0, $totalQty = ($firstId + $qty); $i < $totalQty; $i++)
            $q->execute();
        $this->connection->commit();
        $this->printExecTime($begin, '`tickets` generation time');
    }

    /**
     * @noinspection DisconnectedForeachInstructionInspection
     */
    private function generatePositions(array $positions): void
    {
        $begin = microtime(true);

        $currentDate = $this->currentDT->format(DEFAULT_DATE_FORMAT);
        $q = $this->connection->prepare(<<<SQL
                INSERT INTO positions (title, created_at)
                VALUES (:positionTitle, :currentDate);
            SQL
        );
        $q->bindParam(':positionTitle', $positionTitle);
        $q->bindParam(':currentDate', $currentDate);

        $this->connection->beginTransaction();
        foreach ($positions as $title) {
            $positionTitle = $title;
            $q->execute();
        }
        $this->connection->commit();

        $this->printExecTime($begin, '`positions` generation time');
    }

    /**
     * @throws Exception
     * @noinspection PhpSameParameterValueInspection
     */
    private function generateEmployees(int $qty, int $positionId = 3): void
    {
        $begin = microtime(true);
        $firstName = $lastName = $dateOfBirth = $hiredAt = null;

        $q = $this->connection->prepare(<<<SQL
            INSERT INTO employees (first_name, last_name, position_id, date_of_birth, hired_at)
            VALUES (:firstName, :lastName, $positionId, :dateOfBirth, :hiredAt);
        SQL
        );

        $q->bindParam(':firstName', $firstName);
        $q->bindParam(':lastName', $lastName);
        $q->bindParam(':dateOfBirth', $dateOfBirth);
        $q->bindParam(':hiredAt', $hiredAt);

        $this->connection->beginTransaction();
        for ($id = 0; $id < $qty; $id++) {
            $firstName = $this->randomFirstName();
            $lastName = $this->randomLastName();
            $dateOfBirth = $this->randomDateByRange(45, 16);
            $hiredAt = $this->randomDateByRange(10, 1);

            $q->execute();
        }
        $this->connection->commit();
        $this->printExecTime($begin, '`employees` generation time');
    }

    /**
     * @param int $firstId
     * @param int $qty
     * @param array $options = [
     *    'rangeIds' => [
     *      'begin' => 1,  // Begin transports range value.
     *      'end'   => 10, // End transports range value.
     *    ],
     * ]
     * @throws Exception
     */
    private function transportTicketsGenerator(int $firstId, int $qty, array $options): void
    {
        $begin = microtime(true);
        if (isset($firstId, $qty, $options['rangeIds']['begin'], $options['rangeIds']['end'])) {
            $q = $this->connection->prepare(<<<SQL
                INSERT INTO `transport_tickets` (transport_id, ticket_id, sold_at)
                VALUES (:transportId, :ticketId, :soldAt);
                SQL
            );
            $this->connection->beginTransaction();
            for ($id = 0; $id < $qty; $id++) {
                $randomValueFromRange = random_int($options['rangeIds']['begin'], $options['rangeIds']['end']);
                $q->execute([
                    'transportId' => $randomValueFromRange,
                    'ticketId' => $firstId + $id,
                    'soldAt' => $this->randomDateByRange(5, 4),
                ]);
            }
            $this->connection->commit();
        }
        $this->printExecTime($begin, '`transport_tickets` generation time');
    }

    /**
     * @param int $qty
     * @param array $options = [
     *     'employees_range' => [
     *       'fromId' => 1,
     *       'toId'   => 10,
     *     ],
     *     'transport_range' => [
     *       'fromId' => 1,
     *       'toId'   => 5,
     *     ],
     *     'date_range' => [
     *       'min' => 2,
     *       'max' => 1,
     *     ],
     * ]
     * @throws Exception
     */
    private function timelogsGenerator(int $qty, array $options): void
    {
        $begin = microtime(true);
        if (isset($qty, $options)) {
            $timelogsQuery = $this->connection->prepare(<<<SQL
                INSERT INTO timelogs (employee_id, transport_id, route_id, created_at)
                VALUES (:employeeId, :transportId, :routeId, :createdAt);
                SQL
            );

            $timelogsQuery->bindParam(':employeeId', $rndEmployee);
            $timelogsQuery->bindParam(':transportId', $rndTransport);
            $timelogsQuery->bindParam(':routeId', $rndRoute);
            $timelogsQuery->bindParam(':createdAt', $rndDate);

            $this->connection->beginTransaction();
            for ($i = 0; $i < $qty; $i++) {
                $rndEmployee = random_int($options['employees_range']['fromId'], $options['employees_range']['toId']);
                $rndTransport = random_int($options['transport_range']['fromId'], $options['transport_range']['toId']);
                $rndRoute = random_int(1, count(DEFAULT_ROUTES_LIST));
                $rndDate = $this->randomDateByRange($options['date_range']['min'], $options['date_range']['max']);

                $timelogsQuery->execute();
            }
            $this->connection->commit();
        }
        $this->printExecTime($begin, '`timelogs` generation time');
    }

    /**
     * @param int $qty
     * @param array $options = [
     *     'position_id' => 3,
     *     'employees_range' => [
     *       'fromId' => 1,
     *       'toId'   => 10,
     *     ],
     *     'date_range' => [
     *       'min' => 2,
     *       'max' => 1,
     *     ],
     * ]
     * @throws Exception
     */
    private function salariesGenerator(int $qty, array $options): void
    {
        $begin = microtime(true);
        if (isset($qty, $options)) {
            $q = $this->connection->prepare(<<<SQL
                    INSERT INTO `salaries` (employee_id, position_id, created_at)
                    VALUES (:employeeId, :positionId, :createdAt);
                SQL
            );
            $q->bindParam(':employeeId', $rndEmployeeId);
            $q->bindParam(':positionId', $positionId);
            $q->bindParam(':createdAt', $rndDate);
            $this->connection->beginTransaction();
            for ($i = 0; $i < $qty; $i++) {
                $rndEmployeeId = random_int($options['employees_range']['fromId'], $options['employees_range']['toId']);
                $rndDate = $this->randomDateByRange($options['date_range']['min'], $options['date_range']['max']);
                $positionId = $options['position_id'];
                $q->execute();
            }
            $this->connection->commit();
        }
        $this->printExecTime($begin, '`salaries` generation time');
    }

//----------------------------------------------------------------------------------------------------------------------
    public function generate(): void
    {
        $transportTicketsOpt = [
            'rangeIds' => [
                'begin' => 1,
                'end' => 10,
            ],
        ];
        $timelogsOpt = [
            'employees_range' => [
                'fromId' => 1,
                'toId' => 10,
            ],
            'transport_range' => [
                'fromId' => 1,
                'toId' => 5,
            ],
            'date_range' => [
                'min' => 2,
                'max' => 1,
            ],
        ];
        $salariesOpt = [
            'position_id' => 3,
            'employees_range' => [
                'fromId' => 1,
                'toId' => 10,
            ],
            'date_range' => [
                'min' => 2,
                'max' => 1,
            ],

        ];
        try {
            //------ DML: SEEDING DATA TO DATABASE TABLES --------------------------------------------------------------
            $this->routesGenerator(DEFAULT_ROUTES_LIST);
            $this->transportGenerator(45);
            $this->ticketsGenerator(DEFAULT_FIRST_TICKET_ID, DEFAULT_TICKETS_QTY);
            $this->transportTicketsGenerator(DEFAULT_FIRST_TICKET_ID, DEFAULT_TICKETS_QTY, $transportTicketsOpt);
            $this->generatePositions(DEFAULT_POSITIONS);
            $this->generateEmployees(DEFAULT_EMPLOYEES_QTY);
            $this->timelogsGenerator(10000, $timelogsOpt);
            $this->salariesGenerator(DEFAULT_SALARIES_QTY, $salariesOpt);
        } catch (Exception $e) {
            $this->connection->rollBack();
            echo $e->getMessage();
        }
    }
}

$fixturesGenerator = new Fixtures();
$fixturesGenerator->createTables(true, true);
$fixturesGenerator->generate();
