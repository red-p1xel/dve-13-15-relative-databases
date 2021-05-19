<?php
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpSameParameterValueInspection */

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = 3380;
const DB_NAME = 'ck_electrotrans_depot';
const DB_USER = 'root';
const DB_PASSWORD = 'root';
const DEFAULT_FIRST_NAMES = ['Norbert', 'Damon', 'Laverna', 'Annice', 'Brandie', 'Emogene', 'Cinthia', 'Magaret', 'Daria', 'Ellyn', 'Rhoda', 'Debbra', 'Reid', 'Desire', 'Sueann', 'Shemeka', 'Julian', 'Winona', 'Billie', 'Michaela', 'Loren', 'Zoraida', 'Jacalyn', 'Lovella', 'Bernice', 'Kassie', 'Natalya', 'Whitley', 'Katelin', 'Danica', 'Willow', 'Noah', 'Tamera', 'Veronique', 'Cathrine', 'Jolynn', 'Meridith', 'Moira', 'Vince', 'Fransisca', 'Irvin', 'Catina', 'Jackelyn', 'Laurine', 'Freida', 'Torri', 'Terese', 'Dorothea', 'Landon', 'Emelia'];
const DEFAULT_LAST_NAMES = ['Mischke', 'Serna', 'Pingree', 'Mcnaught', 'Pepper', 'Schildgen', 'Mongold', 'Wrona', 'Geddes', 'Lanz', 'Fetzer', 'Schroeder', 'Block', 'Mayoral', 'Fleishman', 'Roberie', 'Latson', 'Lupo', 'Motsinger', 'Drews', 'Coby', 'Redner', 'Culton', 'Howe', 'Stoval', 'Michaud', 'Mote', 'Menjivar', 'Wiers', 'Paris', 'Grisby', 'Noren', 'Damron', 'Kazmierczak', 'Haslett', 'Guillemette', 'Buresh', 'Center', 'Kucera', 'Catt', 'Badon', 'Grumbles', 'Antes', 'Byron', 'Volkman', 'Klemp', 'Pekar', 'Pecora', 'Schewe', 'Ramage'];
const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';
const DEFAULT_RANGE_PERIOD = 31556952;
const DEFAULT_EMPLOYEES_QTY = 50;
const DEFAULT_SALARIES_QTY = 100000;
const DEFAULT_TICKETS_QTY = 1000000;
const DEFAULT_FIRST_TICKET_ID = 10000;

/**
 * Class Fixtures
 */
class Fixtures
{
    /**
     * @var PDO|null Database connection.
     */
    private ?PDO $connection = null;

    /**
     * @var DateTime Current datetime.
     */
    private DateTime $currentDT;

    /**
     * @var array Driver timelogs.
     */
    private array $driverTimelogs = [];

    /**
     * @var array|string[] Default depot work positions.
     */
    private array $defaultPositions = [
        'Depot Chief',
        'Accountant Manager',
        'Driver',
        'Mechanic',
        'Dispatcher',
        'System Administrator',
        'Office Manager',
        'Route Operator',
        'Controller',
        'Depot Guardian',
        'Operator Of Call-center'
    ];

    /**
     * @var array|string[] Default depot routes.
     */
    private array $defaultRoutes = [
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
        '14',
        '15',
        '18',
        '1A',
        '7A',
        '8P'
    ];

    /**
     * Fixtures constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->currentDT = new DateTime('now');

        // Handle if connection not found
        if (null === $this->connection) {
            try {
                $this->connection = $this->dbConnect();
            } catch (PDOException $e) {
                // Handle if database is not exists
                if ($e->getCode() === 1049) {
                    $this->connection = $this->createDatabase(DB_NAME);
                } else {
                    $stacktrace = [
                        'msg' => $e->getMessage(),
                        'info' => $e->errorInfo,
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ];
                    throw new Exception($stacktrace, $e->getCode());
                }
            }

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * Add driver timelog.
     *
     * @param int $employeeId
     * @param string $dateTime
     *
     * @return array
     */
    protected function addDriverTimelog(int $employeeId, string $dateTime): array
    {

        $this->driverTimelogs[$employeeId][] = $dateTime;

        return $this->driverTimelogs;
    }

    /**
     * Print execution time.
     *
     * @param float $begin
     * @param string|null $msg
     * @param string $beforeCh
     */
    private function printExecTime(float $begin, string $msg = null, string $beforeCh = "\u{2705}"): void
    {
        $time = (microtime(true) - $begin) . "\n";
        echo (null === $msg) ? "$beforeCh Total execution time: $time" : "$beforeCh $msg: $time";
    }

    /**
     * Return one random firstname from array of firstnames.
     *
     * @return string
     */
    private function randomFirstName(): string
    {
        return DEFAULT_FIRST_NAMES[array_rand(DEFAULT_FIRST_NAMES)];
    }

    /**
     * Return one random lastname from array of lastnames.
     *
     * @return string
     */
    private function randomLastName(): string
    {
        return DEFAULT_LAST_NAMES[array_rand(DEFAULT_LAST_NAMES)];
    }

    /**
     * Get formatted random date by defined range of values and specified period (by default period equal of one year).
     *
     * @param int $minRangeValue
     * @param int $maxRangeValue
     * @param string|null $format
     * @param int|null $period
     *
     * @return string
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

    /**
     * Make a fake serial number.
     *
     * @param int $len
     *
     * @return string
     */
    private function fakeSerialNumber(int $len): string
    {
        $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($x, (int) round($len / strlen($x)))), 1, $len);
    }

    /**
     * Make a random float number.
     *
     * @param int $from
     * @param int $to
     * @param int $accuracy
     *
     * @return float
     *
     * @throws Exception
     */
    private function randomFloat(int $from, int $to, int $accuracy = 2): float
    {
        return round(random_int($from, $to - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX), $accuracy);
    }

//---- DDL -------------------------------------------------------------------------------------------------------------

    /**
     * Create a new database connection.
     *
     * @return PDO
     */
    private function dbConnect(): PDO
    {
        return new PDO("mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, []);
    }

    /**
     * Create a new database.
     *
     * @param string $name Database name.
     *
     * @return PDO
     */
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

    /**
     * Create tables with (without) indexes and foreign keys fields into database.
     *
     * @param bool $indexes Flag for create indexes fields.
     * @param bool $fk Flag for create foreign keys fields.
     *
     * @return bool
     */
    public function createTables(bool $indexes = false, bool $fk = false): bool
    {
        $firstId = DEFAULT_FIRST_TICKET_ID;
        $currentDT = $this->currentDT->format(DEFAULT_DATE_FORMAT);

        // Make array of databese tables.
        $tables = [
            'positions' => <<<SQL
                CREATE TABLE positions
                (
                    `position_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `title`       varchar(60)      NOT NULL UNIQUE,
                    `created_at`  DATETIME         NOT NULL DEFAULT '$currentDT',
                    `updated_at`  DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`position_id`)
                );
                SQL,
            'employees' => <<<SQL
                CREATE TABLE employees
                (
                    `employee_id`   INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `first_name`    varchar(25)      NOT NULL,
                    `last_name`     varchar(25)      NOT NULL,
                    `position_id`   INT(11) unsigned NOT NULL,
                    `income`        DECIMAL(8, 2)    NOT NULL DEFAULT 0.00,
                    `date_of_birth` DATETIME         NOT NULL DEFAULT '1986-05-01 09:45:00',
                    `hired_at`      DATETIME         NOT NULL DEFAULT '2021-03-25 10:00:00',
                    `updated_at`    DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`employee_id`)
                );
                SQL,
            'transports' => <<<SQL
                CREATE TABLE transports
                (
                    `transport_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `serial`       VARCHAR(36)      NOT NULL,
                    `created_at`   DATETIME         NOT NULL,
                    `updated_at`   DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`transport_id`)
                );
                SQL,
            'routes' => <<<SQL
                CREATE TABLE routes
                (
                    `route_id`   INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `code`       varchar(3)       NOT NULL UNIQUE,
                    `created_at` DATETIME         NOT NULL,
                    `updated_at` DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`route_id`)
                );
                SQL,
            'tickets' => <<<SQL
                CREATE TABLE tickets
                (
                    `ticket_id`  BIGINT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `code`       VARCHAR(2)          NOT NULL DEFAULT 'AC',
                    `price`      DECIMAL(2, 1)       NOT NULL DEFAULT 5.00,
                    `created_at` DATETIME            NOT NULL DEFAULT '$currentDT',
                    PRIMARY KEY (`ticket_id`)
                );
                SQL,
            'transport_tickets' => <<<SQL
                CREATE TABLE transport_tickets
                (
                    `transport_id` INT(11) unsigned    NOT NULL,
                    `ticket_id`    BIGINT(11) unsigned NOT NULL,
                    `sold_at`      DATETIME            NULL DEFAULT NULL
                );
                SQL,
            'timelogs' => <<<SQL
                CREATE TABLE timelogs
                (
                    `timelog_id`   INT(11) unsigned NOT NULL AUTO_INCREMENT,
                    `daily_income` DECIMAL(8, 2)    NOT NULL DEFAULT 0.00,
                    `employee_id`  INT(11) unsigned NOT NULL,
                    `transport_id` INT(11) unsigned NOT NULL,
                    `route_id`     INT(11) unsigned NOT NULL,
                    `created_at`   DATETIME         NOT NULL DEFAULT '1994-03-03 09:00:00',
                    `updated_at`   DATETIME         NULL DEFAULT NULL,
                    PRIMARY KEY (`timelog_id`)
                );
                SQL,
            'salaries' => <<<SQL
                CREATE TABLE salaries
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
            $resultSet = $this->tableExists($table);
            if (false === $resultSet) {
                $begin = microtime(true);
                $this->connection->prepare($sql)->execute(['firstId' => $firstId]);
                $this->printExecTime($begin, "Table `$table` created");
                unset($tables[$table]);
            } else {
                if ('positions' === $table || 'routes' === $table) {
                    $rowsCount = $resultSet->rowCount();
                    for ($rowId = 1; $rowId <= $rowsCount; $rowId++) {
                        $row = $resultSet->fetchColumn(1);
                        if ('positions' === $table && in_array($row, $this->defaultPositions, true)) {
                            $this->unsetEntrie($row, $this->defaultPositions);
                        }
                        if ('routes' === $table && in_array($row, $this->defaultRoutes, true)) {
                            $this->unsetEntrie($row, $this->defaultRoutes);
                        }
                    }
                }
            }
        }
        // Create indexes fields for database tables
        if (true === $indexes) {
            // Set custom value for first record identifier.
            $this->connection->prepare(<<<SQL
                ALTER TABLE tickets AUTO_INCREMENT = $firstId;
            SQL
            )->execute();

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
        // Create FK's for database tables
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

    /**
     * Check if a table exists into database.
     *
     * @param string $tableName
     *
     * @return false|PDOStatement
     *
     */
    private function tableExists(string $tableName)
    {
        try {
            $qr = $this->connection->query("SELECT * FROM $tableName");
        } catch (Exception $e) {
            return false;
        }

        return $qr;
    }

    /**
     * Method find and unset entrie from array of rows instertion.
     *
     * @param string $row
     * @param array $rows
     */
    private function unsetEntrie(string $row, array &$rows): void
    {
        $index = array_search($row, $rows, true);
        unset($rows[$index]);
    }


//---- DML -------------------------------------------------------------------------------------------------------------

    /**
     * Generate routes.
     *
     * @param array $routes
     */
    private function routesGenerator(array $routes = []): void
    {
        // If table is exists and not empty, unset all duplicated entries from array of default routes.


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
     * Generate transport vehicles.
     *
     * @param int $qty
     * @param string|null $serialNum
     *
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
     * Generate tickets.
     *
     * @param int $firstId
     * @param int $qty
     *
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
        for ($i = 0, $totalQty = ($firstId + $qty); $i < $totalQty; $i++) {
            $q->execute();
        }
        $this->connection->commit();
        $this->printExecTime($begin, '`tickets` generation time');
    }

    /**
     * Generate positions.
     *
     * @param array $positions
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
     * Generate employees.
     *
     * @param int $qty
     * @param int $positionId
     *
     * @throws Exception
     *
     * @noinspection PhpSameParameterValueInspection
     */
    private function generateEmployees(int $qty, int $positionId = 3): void
    {
        $begin = microtime(true);
        $firstName = $lastName = $dateOfBirth = $hiredAt = null;
        $q = $this->connection->prepare(<<<SQL
            INSERT INTO employees (first_name, last_name, position_id, income, date_of_birth, hired_at)
            VALUES (:firstName, :lastName, $positionId, :income, :dateOfBirth, :hiredAt);
        SQL
        );
        $q->bindParam(':firstName', $firstName);
        $q->bindParam(':lastName', $lastName);
        $q->bindParam(':income', $income);
        $q->bindParam(':dateOfBirth', $dateOfBirth);
        $q->bindParam(':hiredAt', $hiredAt);
        $this->connection->beginTransaction();
        for ($id = 0; $id < $qty; $id++) {
            $firstName = $this->randomFirstName();
            $lastName = $this->randomLastName();
            $income = $this->randomFloat(10000, 70000);
            $dateOfBirth = $this->randomDateByRange(45, 16);
            $hiredAt = $this->randomDateByRange(10, 1);

            $q->execute();
        }
        $this->connection->commit();
        $this->printExecTime($begin, '`employees` generation time');
    }

    /**
     * Generate sold transports tickets.
     *
     * @param int $firstId
     * @param int $qty
     * @param array $options = [
     *    'rangeIds' => [
     *      'begin' => 1,  // Begin transports range value.
     *      'end'   => 10, // End transports range value.
     *    ],
     * ]
     *
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
     * Generate drivers timelogs.
     *
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
     *
     * @throws Exception
     */
    private function timelogsGenerator(int $qty, array $options): void
    {
        $begin = microtime(true);
        if (isset($qty, $options)) {
            $timelogsQuery = $this->connection->prepare(<<<SQL
                INSERT INTO timelogs (daily_income, employee_id, transport_id, route_id, created_at)
                VALUES (:dailyIncome, :employeeId, :transportId, :routeId, :createdAt);
                SQL
            );
            $timelogsQuery->bindParam(':dailyIncome', $dailyIncome);
            $timelogsQuery->bindParam(':employeeId', $rndEmployee);
            $timelogsQuery->bindParam(':transportId', $rndTransport);
            $timelogsQuery->bindParam(':routeId', $rndRoute);
            $timelogsQuery->bindParam(':createdAt', $rndDate);
            $this->connection->beginTransaction();
            for ($i = 1; $i <= $qty; $i++) {
                $dailyIncome = $this->randomFloat(10000, 70000);
                $rndEmployee = random_int($options['employees_range']['fromId'], $options['employees_range']['toId']);
                $rndTransport = random_int($options['transport_range']['fromId'], $options['transport_range']['toId']);
                $rndRoute = random_int(1, count($this->defaultRoutes));
                $rndDate = $this->randomDateByRange($options['date_range']['min'], $options['date_range']['max']);
                if (isset($this->driverTimelogs[$rndEmployee])) {
                    if (!in_array($rndDate, $this->driverTimelogs[$rndEmployee], true)) {
                        $this->addDriverTimelog($rndEmployee, $rndDate);
                        $timelogsQuery->execute();
                    } else {
                        --$i;
                    }
                } else {
                    $this->addDriverTimelog($rndEmployee, $rndDate);
                    $timelogsQuery->execute();
                }
            }
            $this->connection->commit();
        }
        $this->printExecTime($begin, '`timelogs` generation time');
    }

    /**
     * Generate employees salaries.
     *
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
     *
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
        // The transport tickets range options definition.
        $transportTicketsOpt = [
            'rangeIds' => [
                'begin' => 1,
                'end' => 10,
            ],
        ];
        // The timelogs options definition.
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
        // The employees salaries options definition.
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
            $this->routesGenerator($this->defaultRoutes);
            $this->transportGenerator(45);
            $this->ticketsGenerator(DEFAULT_FIRST_TICKET_ID, DEFAULT_TICKETS_QTY);
            $this->transportTicketsGenerator(DEFAULT_FIRST_TICKET_ID, DEFAULT_TICKETS_QTY, $transportTicketsOpt);
            $this->generatePositions($this->defaultPositions);
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
$fixturesGenerator->createTables();
$fixturesGenerator->generate();
