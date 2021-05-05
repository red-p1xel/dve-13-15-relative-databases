# 1:
SELECT (first_name, last_name, date_of_birth, position_id) FROM employees ORDER BY last_name ASC;

# 2:
SELECT first_name, last_name, date_of_birth,
       AVG(p.salary)
FROM employees
    INNER JOIN positions p on employees.position_id = p.position_id
ORDER BY last_name;

# 3: Get all employees list with average salary sorted by last name
SELECT e.employee_id, e.last_name, e.first_name, e.date_of_birth,
       AVG(p.salary) AS "avg Salary"
FROM employees AS e
         INNER JOIN positions AS p
                    ON e.position_id = p.position_id
GROUP BY e.employee_id, e.last_name
ORDER BY e.last_name;

# 3: Total transport profit -> Total transports profit average -> Total count of working days for transport unit
# SELECT