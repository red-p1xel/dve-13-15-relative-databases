# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transports`;
CREATE TABLE `transports`
(
    `id`            INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `serial_number` varchar(64)      NOT NULL UNIQUE,
    `created_at`    DATETIME         NOT NULL,
    `updated_at`    DATETIME         NOT NULL,
    `deleted_at`    DATETIME         NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO transports (serial_number, created_at, updated_at, deleted_at)
VALUES ('F2MPS9L34NSQX9B94YWYKSK3VFXPMA8BAASWVGL3DS', '2000-04-27 09:35:00', '2018-04-27 09:35:00', NULL),
       ('V2QQGYAZJHX6YFEPHC2ZR95FU37V73D6K5R9NZPLDX', '2000-04-27 09:35:00', '2018-04-27 09:35:00', NULL),
       ('47WHGWDPMCBKU46QYJAE84GXAL5VMGG4B3HXAJ62D', '2000-04-27 09:35:00', '2018-04-27 09:35:00', NULL),
       ('RKKTSFVUZMHB7UQANYJRYTQVQ84EXWZMHQGFMSXK', '2000-04-27 09:35:00', '2018-04-27 09:35:00', NULL),
       ('MSE76GUBK2J9QGMLFFDHCC32GGMULJNHBMKX5N2Q9E', '2000-04-27 09:35:00', '2018-04-27 09:35:00', NULL);


# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets`
(
    `id`         INT(11) unsigned    NOT NULL AUTO_INCREMENT,
    `code`       VARCHAR(2)          NOT NULL DEFAULT 'AC',
    `number`     BIGINT(11) unsigned NOT NULL UNIQUE,
    `price`      DECIMAL(2, 1)                DEFAULT '4.00',
    `created_at` DATETIME            NOT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO tickets (number, created_at)
VALUES (4243873767, '2000-01-01 03:00:00'),
       (4248177115, '2000-01-01 03:00:00'),
       (3814471989, '2000-01-01 03:00:00'),
       (2132108904, '2000-01-01 03:00:00'),
       (3964104948, '2000-01-01 03:00:00'),
       (3729914733, '2000-01-01 03:00:00'),
       (3430914679, '2000-01-01 03:00:00'),
       (2297517190, '2000-01-01 03:00:00'),
       (1859666574, '2000-01-01 03:00:00'),
       (2084592049, '2000-01-01 03:00:00'),
       (2120425510, '2000-01-01 03:00:00'),
       (953232684, '2000-01-01 03:00:00'),
       (1930058249, '2000-01-01 03:00:00'),
       (2019514445, '2000-01-01 03:00:00'),
       (2620853017, '2000-01-01 03:00:00'),
       (645733842, '2000-01-01 03:00:00'),
       (3797696936, '2000-01-01 03:00:00'),
       (2460156913, '2000-01-01 03:00:00'),
       (3249003782, '2000-01-01 03:00:00'),
       (809657073, '2000-01-01 03:00:00');

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transport_tickets`;
CREATE TABLE `transport_tickets`
(
    `transport_id` INT(11) unsigned NOT NULL,
    `ticket_id`    INT(11) unsigned NOT NULL,
    `sold_at`      DATETIME NULL
);


ALTER TABLE `transport_tickets`
    ADD CONSTRAINT `transport_tickets_fk0` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`) ON DELETE CASCADE;
ALTER TABLE `transport_tickets`
    ADD CONSTRAINT `transport_tickets_fk1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

INSERT INTO `transport_tickets` (transport_id, ticket_id)
VALUES (3, 1),
       (4, 2),
       (3, 3),
       (4, 4),
       (3, 5),
       (4, 6),
       (3, 7),
       (4, 8),
       (3, 9),
       (4, 10),
       (3, 11),
       (4, 12),
       (3, 13),
       (4, 14),
       (3, 15),
       (4, 16),
       (3, 17),
       (4, 18),
       (3, 19);


# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes`
(
    `id`         INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `code`       varchar(10)      NOT NULL UNIQUE,
    `created_at` DATETIME         NOT NULL,
    `updated_at` DATETIME         NOT NULL,
    `deleted_at` DATETIME         NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO routes (code, created_at, updated_at, deleted_at)
VALUES ('1', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('2', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('3', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('4', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('5', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('7', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('7A', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('8', '2000-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('10', '2010-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('12', '2012-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('14', '2018-04-27 11:35:09', '2021-04-27 11:35:09', NULL),
       ('15', '2019-04-27 11:35:09', '2021-04-27 11:35:09', NULL);


# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions`
(
    `id`         INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `title`      varchar(60)      NOT NULL UNIQUE,
    `created_at` DATETIME         NOT NULL,
    `updated_at` DATETIME         NOT NULL,
    `deleted_at` DATETIME         NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO positions (title, created_at, updated_at, deleted_at)
VALUES ('Depot Chief', '2021-04-27 11:28:38', '2021-04-27 11:28:38', null),
       ('Accountant Manager', '2021-04-27 11:28:38', '2021-04-27 11:28:38', null),
       ('Driver', '2021-04-27 11:28:38', '2021-04-27 11:28:38', null),
       ('Mechanic', '2021-04-27 11:28:38', '2021-04-27 11:28:38', null),
       ('Dispatcher', '2021-04-27 11:28:38', '2021-04-27 11:28:38', null);

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees`
(
    `id`          INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `first_name`  varchar(25)      NOT NULL,
    `last_name`   varchar(25)      NOT NULL,
    `rate`        DECIMAL(4, 2)    NOT NULL,
    `position_id` INT(11) unsigned NOT NULL,
    `birth_at`    DATETIME         NOT NULL,
    `hired_at`    DATETIME         NOT NULL,
    `updated_at`  DATETIME         NOT NULL,
    `deleted_at`  DATETIME         NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `employees`
    ADD CONSTRAINT `employees_fk0` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE;

INSERT INTO employees (first_name, last_name, rate, position_id, birth_at, hired_at, updated_at, deleted_at)
VALUES ('Avdei', 'Volodin', 50.00, 1, '1984-04-25 11:34:44', '2018-04-27 11:34:54', '2021-04-27 11:35:09', null),
       ('Nancy', 'Carter', 20.00, 2, '1986-02-17 11:34:44', '2018-04-27 11:34:54', '2021-04-27 11:35:09', null),
       ('Ruslana', 'Alexeeva', 15.00, 3, '1994-03-11 11:34:44', '2018-04-27 11:34:54', '2021-04-27 11:35:09', null),
       ('Nick', 'Gavrilov', 15.00, 3, '1980-01-01 11:34:44', '2018-04-27 11:34:54', '2021-04-27 11:35:09', null),
       ('Borislav', 'Zakharov', 25.00, 4, '1976-11-15 11:34:44', '2018-04-27 11:34:54', '2021-04-27 11:35:09', null),
       ('Dementi', 'Yermakov', 25.00, 4, '1984-03-22 11:34:44', '2018-04-27 11:34:54', '2021-04-27 11:35:09', null),
       ('Rodion', 'Bulgakov', 5.00, 5, '1993-09-01 11:34:44', '2018-04-27 11:34:54', '2021-04-27 11:35:09', null);

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `timelogs`;
CREATE TABLE `timelogs`
(
    `id`           INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `time_spent`   DECIMAL(2, 1)    NOT NULL DEFAULT '0',
    `employee_id`  INT(11) unsigned NOT NULL,
    `transport_id` INT(11) unsigned NOT NULL,
    `route_id`     INT(11) unsigned NOT NULL,
    `created_at`   DATETIME         NOT NULL,
    `updated_at`   DATETIME         NOT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `timelogs`
    ADD CONSTRAINT `timelogs_fk0` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

ALTER TABLE `timelogs`
    ADD CONSTRAINT `timelogs_fk1` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`) ON DELETE CASCADE;

ALTER TABLE `timelogs`
    ADD CONSTRAINT `timelogs_fk2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;

INSERT INTO timelogs (time_spent, employee_id, transport_id, route_id, created_at, updated_at)
VALUES (7.00, 3, 1, 1, '2018-04-12 09:00:00', '2021-04-12 19:00:00'),
       (5.00, 4, 2, 2, '2018-04-12 09:00:00', '2021-04-12 17:00:00'),
       (7.00, 3, 3, 3, '2018-04-13 09:00:00', '2021-04-13 19:00:00'),
       (5.00, 4, 4, 4, '2018-04-13 09:00:00', '2021-04-13 17:00:00'),
       (7.00, 3, 5, 5, '2018-04-14 09:00:00', '2021-04-14 19:00:00'),
       (5.00, 4, 3, 6, '2018-04-14 09:00:00', '2021-04-14 17:00:00'),
       (7.00, 3, 2, 11, '2018-04-15 09:00:00', '2021-04-15 19:00:00'),
       (5.00, 4, 3, 8, '2018-04-15 09:00:00', '2021-04-15 17:00:00'),
       (7.00, 3, 1, 10, '2018-04-16 09:00:00', '2021-04-16 19:00:00'),
       (5.00, 4, 4, 7, '2018-04-16 09:00:00', '2021-04-16 17:00:00'),
       (7.00, 3, 4, 7, '2018-04-17 09:00:00', '2021-04-17 19:00:00'),
       (5.00, 4, 3, 6, '2018-04-17 09:00:00', '2021-04-17 17:00:00'),
       (7.00, 3, 2, 11, '2018-04-18 09:00:00', '2021-04-18 19:00:00'),
       (5.00, 4, 4, 9, '2018-04-18 09:00:00', '2021-04-18 17:00:00'),
       (7.00, 3, 4, 9, '2018-04-19 09:00:00', '2021-04-19 19:00:00'),
       (5.00, 4, 2, 11, '2018-04-19 09:00:00', '2021-04-19 17:00:00'),
       (7.00, 3, 1, 7, '2018-04-20 09:00:00', '2021-04-20 19:00:00'),
       (5.00, 4, 4, 2, '2018-04-20 09:00:00', '2021-04-20 17:00:00'),
       (7.00, 3, 4, 10, '2018-04-21 09:00:00', '2021-04-21 19:00:00'),
       (5.00, 4, 1, 11, '2018-04-21 09:00:00', '2021-04-21 17:00:00');

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `salaries`;
CREATE TABLE `salaries`
(
    `id`           INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `total_amount` DECIMAL(8, 2)    NOT NULL DEFAULT 0.00,
    `employee_id`  INT(11) unsigned NOT NULL,
    `created_at`   DATE             NOT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `salaries`
    ADD CONSTRAINT `salaries_fk0` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

INSERT INTO salaries (total_amount, employee_id, created_at)
VALUES (9100.00, 1, '2018-05-04'),
       (3640.00, 2, '2018-05-04'),
       (1050.00, 3, '2018-05-04'),
       (750.00, 4, '2018-05-04'),
       (4550.00, 5, '2018-05-04'),
       (910.00, 6, '2018-05-04'),
       (910.00, 7, '2018-05-04');
