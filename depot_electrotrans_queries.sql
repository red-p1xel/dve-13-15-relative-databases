##################################################################################
# Get a list of all employees, ordered by last name
#--------------------------------------------------------------------------------
# 34 rows retrieved starting from 1 in 118 ms (execution: 7 ms, fetching: 111 ms)
##################################################################################
SELECT last_name
FROM employees
GROUP BY last_name;


###############################################################################
# Get average salary by employee
#------------------------------------------------------------------------------
# 50 rows retrieved starting from 1 in 49 ms (execution: 5 ms, fetching: 44 ms)
###############################################################################
SELECT e.employee_id,
       AVG (p.salary) AS "Employee salary (avg.)"
FROM employees AS e
         INNER JOIN positions AS p ON e.position_id = p.position_id
GROUP BY e.employee_id, p.salary
ORDER BY p.salary desc;


###################################################################################################
# Get average and highest current salary by position (store current salary in the employees table)
#--------------------------------------------------------------------------------------------------
# 1 row retrieved starting from 1 in 33 ms (execution: 5 ms, fetching: 28 ms)
###################################################################################################
SELECT e.position_id,
       AVG (p.salary) AS "Current salary (avg.)"
FROM employees AS e
         INNER JOIN positions AS p ON e.position_id = p.position_id
GROUP BY e.position_id, p.salary
ORDER BY p.salary desc;


################################################################################
# Get all employees list with average salary sorted by last name
#-------------------------------------------------------------------------------
# 50 rows retrieved starting from 1 in 38 ms (execution: 4 ms, fetching: 34 ms)
################################################################################
SELECT e.employee_id, e.last_name, e.first_name, e.date_of_birth,
       AVG(p.salary) AS "Salary (avg.)"
FROM employees AS e
         INNER JOIN positions AS p ON e.position_id = p.position_id
GROUP BY e.employee_id, e.last_name
ORDER BY e.last_name;


###################################################################################
# Get total number of days every person worked and total income
#----------------------------------------------------------------------------------
# 10 rows retrieved starting from 1 in 454 ms (execution: 430 ms, fetching: 24 ms)
###################################################################################
SELECT e.employee_id, p.position_id, e.last_name, e.first_name,
       COUNT(DISTINCT DATE_FORMAT(s.created_at, '%Y-%m')) * p.salary AS total_employee_incomes,
       COUNT(DISTINCT DATE_FORMAT(s.created_at, '%Y-%m'))            AS total_working_months
FROM employees AS e
         INNER JOIN positions AS p ON e.position_id = p.position_id
         INNER JOIN salaries s ON e.employee_id = s.employee_id
GROUP BY e.employee_id
ORDER BY e.employee_id;


###############################################################################################################
# Get overall (total) income by transport, average income and a number of working days in the descending order
#--------------------------------------------------------------------------------------------------------------
# 10 rows retrieved starting from 1 in 5 s 138 ms (execution: 5 s 107 ms, fetching: 31 ms)
###############################################################################################################
SELECT transport_unit.transport_id, tt.transport_id,
       COUNT(DISTINCT tt.sold_at) AS total_working_days,
       SUM(t.price)               AS total_profit,
       AVG(t.price)               AS total_avg_profit
FROM transports AS transport_unit
         INNER JOIN transport_tickets tt ON transport_unit.transport_id = tt.transport_id
         INNER JOIN tickets t ON t.ticket_id = tt.ticket_id
GROUP BY transport_unit.transport_id
ORDER BY total_working_days DESC;


#################################################################################
# Get a number of years every person works in `CK-ELECTRO-TRANS`
#--------------------------------------------------------------------------------
# 10 rows retrieved starting from 1 in 87 ms (execution: 63 ms, fetching: 24 ms)
#################################################################################
SELECT ttl.employee_id, e.first_name, e.last_name, e.hired_at, ttl.created_at,
       DATEDIFF(ttl.created_at, e.hired_at) / 365.25 AS total_years_of_working_exp
FROM employees AS e
         INNER JOIN timelogs ttl ON e.employee_id = ttl.employee_id
    AND ttl.timelog_id =
        (
            SELECT timelog_id
            FROM timelogs tl
            WHERE tl.employee_id = e.employee_id AND tl.created_at > e.hired_at
            ORDER BY tl.created_at DESC
            LIMIT 1
        )
GROUP BY ttl.created_at, e.first_name, e.last_name, e.hired_at, e.employee_id, total_years_of_working_exp
ORDER BY total_years_of_working_exp DESC;
