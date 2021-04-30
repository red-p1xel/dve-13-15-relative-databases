# ----------------------------------------------------------------------------------------------------------------------
DROP DATABASE IF EXISTS `ck_electrotrans_depot`;


# ----------------------------------------------------------------------------------------------------------------------
# noinspection SpellCheckingInspection
CREATE DATABASE IF NOT EXISTS `ck_electrotrans_depot` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transports`;
CREATE TABLE `transports`
(
    `id`           INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `serial`       VARCHAR(36)      NOT NULL,
    `created_at`   DATETIME         NOT NULL,
    `updated_at`   DATETIME         NULL DEFAULT NULL,
    `deleted_at`   DATETIME         NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO `transports` (serial, created_at)
VALUES ('7c69d2b4-a906-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('e8bf1330-a906-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('f1325afc-a906-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('f541036b-a906-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('f8cbfa43-a906-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('fc26253d-a906-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('009cbfd4-a907-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('03f94e2b-a907-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('07fd3ddc-a907-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('0bea44cd-a907-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('11f6e462-a907-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00'),
       ('182574f2-a907-11eb-bf69-0242ac11000a', '2000-04-27 09:35:00');


# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets`
(
    `id`         BIGINT(11) unsigned NOT NULL AUTO_INCREMENT,
    `code`       VARCHAR(2)          NOT NULL DEFAULT 'AC',
    `price`      DECIMAL(2, 1)       NOT NULL DEFAULT 4.00,
    `created_at` DATETIME            NOT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `tickets`
    AUTO_INCREMENT = 10000;
INSERT INTO `tickets` (created_at)
VALUES ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00'),
       ('2000-01-01 03:00:00');

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transport_tickets`;
CREATE TABLE `transport_tickets`
(
    `transport_id` INT(11) unsigned    NOT NULL,
    `ticket_id`    BIGINT(11) unsigned NOT NULL,
    `sold_at`      DATETIME            NULL DEFAULT NULL
);


ALTER TABLE `transport_tickets`
    ADD CONSTRAINT `transport_tickets_fk0` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`) ON DELETE NO ACTION;

ALTER TABLE `transport_tickets`
    ADD CONSTRAINT `transport_tickets_fk1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE NO ACTION;

INSERT INTO `transport_tickets` (transport_id, ticket_id)
VALUES (3, 10001),
       (4, 10002),
       (3, 10003),
       (4, 10004),
       (3, 10005),
       (4, 10006),
       (3, 10007),
       (4, 10008),
       (3, 10009),
       (4, 10010),
       (3, 10011),
       (4, 10012),
       (3, 10013),
       (4, 10014),
       (3, 10015),
       (4, 10016),
       (3, 10017),
       (4, 10018),
       (3, 10019);


# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes`
(
    `id`         INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `code`       varchar(3)       NOT NULL UNIQUE,
    `created_at` DATETIME         NOT NULL,
    `updated_at` DATETIME         NULL DEFAULT NULL,
    `deleted_at` DATETIME         NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO `routes` (code, created_at)
VALUES ('1', '2000-04-27 11:35:09'),
       ('2', '2000-04-27 11:35:09'),
       ('3', '2000-04-27 11:35:09'),
       ('4', '2000-04-27 11:35:09'),
       ('5', '2000-04-27 11:35:09'),
       ('7', '2000-04-27 11:35:09'),
       ('7A', '2000-04-27 11:35:09'),
       ('8', '2000-04-27 11:35:09'),
       ('10', '2010-04-27 11:35:09'),
       ('12', '2012-04-27 11:35:09'),
       ('14', '2018-04-27 11:35:09'),
       ('15', '2019-04-27 11:35:09');


# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions`
(
    `id`         INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `title`      varchar(60)      NOT NULL UNIQUE,
    `created_at` DATETIME         NOT NULL,
    `updated_at` DATETIME         NULL DEFAULT NULL,
    `deleted_at` DATETIME         NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO `positions` (title, created_at)
VALUES ('Depot Chief', '2021-04-27 11:28:38'),
       ('Accountant Manager', '2021-04-27 11:28:38'),
       ('Driver', '2021-04-27 11:28:38'),
       ('Mechanic', '2021-04-27 11:28:38'),
       ('Dispatcher', '2021-04-27 11:28:38');

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees`
(
    `id`          INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `first_name`  varchar(25)      NOT NULL,
    `last_name`   varchar(25)      NOT NULL,
    `rate`        DECIMAL(4, 2)    NOT NULL DEFAULT 5.00,
    `position_id` INT(11) unsigned NOT NULL,
    `birth_at`    DATETIME         NOT NULL,
    `hired_at`    DATETIME         NOT NULL,
    `updated_at`  DATETIME         NULL DEFAULT NULL,
    `deleted_at`  DATETIME         NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `employees`
    ADD CONSTRAINT `employees_fk0` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE;

INSERT INTO employees (first_name, last_name, rate, position_id, birth_at, hired_at)
VALUES ('Avdei', 'Volodin', 50.00, 1, '1984-04-25 11:34:44', '2018-04-27 11:34:54'),
       ('Nancy', 'Carter', 20.00, 2, '1986-02-17 11:34:44', '2018-04-27 11:34:54'),
       ('Ruslana', 'Alexeeva', 15.00, 3, '1994-03-11 11:34:44', '2018-04-27 11:34:54'),
       ('Nick', 'Gavrilov', 15.00, 3, '1980-01-01 11:34:44', '2018-04-27 11:34:54'),
       ('Borislav', 'Zakharov', 25.00, 4, '1976-11-15 11:34:44', '2018-04-27 11:34:54'),
       ('Dementi', 'Yermakov', 25.00, 4, '1984-03-22 11:34:44', '2018-04-27 11:34:54'),
       ('Rodion', 'Bulgakov', 5.00, 5, '1993-09-01 11:34:44', '2018-04-27 11:34:54');

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `timelogs`;
CREATE TABLE `timelogs`
(
    `id`           INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `time_spent`   DECIMAL(2, 1)    NOT NULL DEFAULT 0.00,
    `employee_id`  INT(11) unsigned NOT NULL,
    `transport_id` INT(11) unsigned NOT NULL,
    `route_id`     INT(11) unsigned NOT NULL,
    `created_at`   DATETIME         NOT NULL DEFAULT '2018-04-12 09:00:00',
    `updated_at`   DATETIME         NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `timelogs`
    ADD CONSTRAINT `timelogs_fk0` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

ALTER TABLE `timelogs`
    ADD CONSTRAINT `timelogs_fk1` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`) ON DELETE CASCADE;

ALTER TABLE `timelogs`
    ADD CONSTRAINT `timelogs_fk2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;

INSERT INTO timelogs (time_spent, employee_id, transport_id, route_id)
VALUES (7.00, 3, 1, 1),
       (5.00, 4, 2, 2),
       (7.00, 3, 3, 3),
       (5.00, 4, 4, 4),
       (7.00, 3, 5, 5),
       (5.00, 4, 3, 6),
       (7.00, 3, 2, 11),
       (5.00, 4, 3, 8),
       (7.00, 3, 1, 10),
       (5.00, 4, 4, 7),
       (7.00, 3, 4, 7),
       (5.00, 4, 3, 6),
       (7.00, 3, 2, 11),
       (5.00, 4, 4, 9),
       (7.00, 3, 4, 9),
       (5.00, 4, 2, 11),
       (7.00, 3, 1, 7),
       (5.00, 4, 4, 2),
       (7.00, 3, 4, 10),
       (5.00, 4, 1, 11);

# ----------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `salaries`;
CREATE TABLE `salaries`
(
    `id`           INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `total_amount` DECIMAL(8, 2)    NOT NULL DEFAULT 500.00,
    `employee_id`  INT(11) unsigned NOT NULL,
    `created_at`   DATE             NOT NULL DEFAULT '1994-03-03',
    PRIMARY KEY (`id`)
);

ALTER TABLE `salaries`
    ADD CONSTRAINT `salaries_fk0` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

INSERT INTO `salaries` (total_amount, employee_id)
VALUES (9100.00, 1),
       (3640.00, 2),
       (1050.00, 3),
       (750.00, 4),
       (4550.00, 5),
       (910.00, 6),
       (910.00, 7);
