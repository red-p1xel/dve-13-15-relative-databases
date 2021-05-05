Lesson 14: SQL-queries
======================

## Make queries

In case of this lesson need make next SQL-queries:

- Get a list of all employees, ordered by last name;
- [*] Get average salary by employee;
- Get total profit for any given vehicle in depot (use `avg()` on this case) AND total exploitation days, sorted by profit descending.
- [*] Get total working days and total profit from this employee on his working days.
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
