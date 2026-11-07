# SQL Lab Exercises: DDL, DML, and Constraints

This repository file contains SQL scripts covering basic table management, data constraint configurations (Primary Key, Foreign Key, Check, Unique, Not Null, Default), `ALTER`, `UPDATE`, and schema definitions for institutional tables.

---

## Part 1: Basic Operations & Schema Exploration

### 1. Create and Explore Employee Schema
```sql
-- Creating a basic employee table
create table employee (
    eid varchar2(5), 
    ename varchar2(10), 
    dob date, 
    salary numeric(10,2), 
    gender varchar2(10)
);

-- Describe table structure
desc employee;
```

### 2. Data Population & Hardening
```sql
-- Dynamic record insertion using positional parameters
insert into employee values (&eid, &ename, &dob, &salary);

-- Positional explicit entry
insert into employee (eid, ename, dob, salary, gender) values ('E01', 'Arya', '07-may-2004', 15000, 'female');

-- Column-selective record entry
insert into employee (eid, ename, salary) values ('E01', 'Arya', 15000);

-- Display table contents
select * from employee;

-- Commit transactions and close session
commit; 
exit;   

-- Drop table schema
drop table employee;
```

---

## Part 2: Working with Constraints

### 3. Primary Key Configurations
```sql
-- Inline basic primary key mapping
create table employee (
    eid varchar2(5) primary key, 
    ename varchar2(10), 
    dob date, 
    salary numeric(10,2)
);

-- Named primary key constraint configuration
create table employee (
    eid varchar2(5) constraint emp_pk primary key, 
    ename varchar2(10), 
    dob date, 
    salary numeric(10,2)
);
```

### 4. Foreign Key Constraints (Referential Integrity)
```sql
-- Master Table Setup
create table department (
    did varchar2(5) primary key, 
    dname varchar2(15)
);

-- Column Level Foreign Key Configuration
create table employee (
    eid varchar2(5) primary key, 
    ename varchar2(15), 
    salary numeric(10,2), 
    did varchar2(5) references department (did)
);

-- Table Level Foreign Key Configuration
create table employee (
    eid varchar2(5) primary key, 
    ename varchar2(15), 
    salary numeric(10,2), 
    did varchar2(5), 
    foreign key (did) references department (did)
);
```

> **Note on Relationships:** In a One-to-Many relationship, we append the primary key of the **One-Side Table** into the schema structure of the **Many-Side Table** as a reference. Adding `ON DELETE CASCADE` automatically cleans up child records when a parent record is deleted.

### 5. Not Null & Unique Constraints
```sql
-- Inline Not Null rule assertion
create table employee (
    eid varchar2(5), 
    ename varchar2(10) not null, 
    dob date, 
    salary numeric(10,2)
);

-- Named Not Null rule assertion
create table employee (
    eid varchar2(5), 
    ename varchar2(10) constraint emp_not_null not null, 
    dob date
);

-- Inline Unique constraint declaration
create table employee (
    eid varchar2(5) primary key,
    adhaarno varchar2(15) unique, 
    ename varchar2(10), 
    dob date, 
    salary numeric(10,2)
);

-- Named Unique constraint declaration
create table employee (
    eid varchar2(5), 
    adhaarno varchar2(15) constraint emp_unique_adhrno unique, 
    ename varchar2(10), 
    dob date
); 
```
*Note: Table Level Constraint mappings cannot be added to `NOT NULL` and `DEFAULT` parameters.*

### 6. Default & Check Constraints
```sql
-- Setting default literal valuations for unsupplied column markers
create table employee (
    eid varchar2(5), 
    ename varchar2(10), 
    dob date default '01-jan-2000', 
    salary numeric(10,2)
);

-- Basic programmatic Check assertion on numerical metrics
create table employee (
    eid varchar2(5), 
    ename varchar2(10), 
    dob date, 
    salary numeric(10,2) check (salary > 10000)
);

-- Set Membership constraint verification rule
create table employee (
    eid varchar2(5), 
    ename varchar2(10), 
    dob date, 
    salary numeric(10,2), 
    gender varchar2(10) check (gender in ('male', 'female', 'tg'))
);

-- Explicitly named validation rule implementation
create table employee (
    eid varchar2(5), 
    ename varchar2(10), 
    dob date, 
    salary numeric(10,2) constraint emp_salcheck check (salary > 10000)
);

-- Syntactic prefix string evaluation rule and threshold checks
-- check (eid like 'E%')
-- check (dob > '01-jan-1985')
```

---

## Part 3: Table Modifications (ALTER, UPDATE, DELETE)

### 7. Altering Structural Schema Definitions
```sql
-- Add a new structural attribute column
alter table employee add location varchar2(20);  

-- Modify the allocation scale of an existing datatype column
alter table employee modify ename varchar2(30); 

-- Rename column attributes
alter table employee rename column ename to emp_name;

-- Drop an unwanted column from the schema matrix
alter table employee drop column location;    

-- Bind primary key rules to an existing table column
alter table employee add primary key(eid);     

-- Strip primary key identifiers out of the layout block
alter table employee drop primary key;   

-- Drop custom designated schema rule barriers
alter table employee drop constraint constraint_name;  

-- Add structural mathematical data check rules
alter table employee add constraint chksal check (salary > 1000);
```

### 8. Data Deletion Operations
```sql
-- Target selective element removals matching evaluations
delete from employee where emp_name = 'Arya';

-- Rapid clear layout wipeout (Trashes data contents but retains structure indices)
truncate table employee;
```

### 9. Structural Data Updates
```sql
-- Direct criteria-driven row data updating
update employee set salary = 80000 where eid = 'E2';

-- Applying a rolling 10% raise to all entries globally
update employee set salary = salary + (0.1 * salary);
```

### 10. Advanced Query Migrations and Metadata Lookups
```sql
-- Copying explicit columns wholesale into a target repository from secondary tables
-- insert into table_2 (select column_1, column_2 from table_1);

-- Inspect schema design documentation profiles
desc all_constraints;   

-- Extract custom configuration metrics tracking targeted systems
select constraint_name, constraint_type 
from all_constraints 
where table_name = 'EMPLOYEE';
```

---

## Part 4: Production Lab Application Case Schemas

### 11. Core Department Setup
```sql
create table Department (
    deptno char(5) primary key,
    deptname varchar2(35), 
    location varchar2(15) default 'kochi'
);
```

### 12. Complex Verified Employee Schema
```sql
create table Employee_1 (
    empno char(10) primary key check (empno like 'E%'),
    empname varchar2(30) unique not null,
    mobile char(12), 
    hire_date date, 
    job varchar2(15),
    gender char(1) check (gender in ('M', 'F')), 
    dob date check (dob > '01-jan-1985'), 
    salary numeric(10,2) check (salary > 0),
    bonus numeric(10,2)
);
```

### 13. Project Schema Trackers
```sql
create table project (
    projno char(10) primary key, 
    projname varchar2(30),
    stdate date, 
    enddate date
);
```

### 14. Academic Institution Master Schemas (System Interlocked)
```sql
-- Table 1: Institutional Operations Base
create table Dept (
    deptid char(5) primary key,
    deptname varchar2(35) unique not null,
    hod_name varchar2(30), 
    office_location varchar2(15) default 'kochi', 
    contact_number numeric(10)
);

-- Table 2: Student Roster Ledger
create table stud (
    sid varchar2(5) primary key, 
    sname varchar2(20), 
    dob date, 
    gender char(1) check (gender in ('m', 'f')), 
    mobile numeric(10), 
    email varchar2(30) unique, 
    deptid char(5), 
    adms_date date, 
    cgpa numeric(4,2) check (cgpa >= 0 and cgpa <= 10), 
    foreign key (deptid) references Dept(deptid)
);

-- Table 3: Faculty Roster Matrix
create table faculty (
    fid varchar2(10) primary key, 
    fname varchar2(30), 
    fqual varchar2(40), 
    fdesgn varchar2(30), 
    f_doj date, 
    sal numeric(10,2) check (sal > 0), 
    email varchar2(35) unique, 
    deptid char(5), 
    foreign key (deptid) references Dept(deptid)
);
```
