

CREATE TABLE roles (
    role_id     NUMBER PRIMARY KEY,
    role_name   VARCHAR2(50) NOT NULL UNIQUE
);

CREATE SEQUENCE roles_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_roles_bi
BEFORE INSERT ON roles
FOR EACH ROW
WHEN (NEW.role_id IS NULL)
BEGIN
    SELECT roles_seq.NEXTVAL INTO :NEW.role_id FROM dual;
END;
/

CREATE TABLE users (
    user_id     NUMBER PRIMARY KEY,
    role_id     NUMBER NOT NULL,
    full_name   VARCHAR2(100) NOT NULL,
    email       VARCHAR2(100) NOT NULL UNIQUE,
    password    VARCHAR2(255) NOT NULL,
    phone       VARCHAR2(20),
    status      VARCHAR2(20) DEFAULT 'ACTIVE' CHECK (status IN ('ACTIVE', 'INACTIVE')),
    created_at  TIMESTAMP DEFAULT SYSTIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE SEQUENCE users_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_users_bi
BEFORE INSERT ON users
FOR EACH ROW
WHEN (NEW.user_id IS NULL)
BEGIN
    SELECT users_seq.NEXTVAL INTO :NEW.user_id FROM dual;
END;
/


CREATE TABLE cases (
    case_id         NUMBER PRIMARY KEY,
    case_title      VARCHAR2(150) NOT NULL,
    case_type       VARCHAR2(50),
    case_description VARCHAR2(1000),
    case_status     VARCHAR2(20) DEFAULT 'OPEN' CHECK (case_status IN ('OPEN', 'CLOSED', 'PENDING')),
    opened_date     DATE DEFAULT SYSDATE,
    closed_date     DATE,
    officer_id      NUMBER NOT NULL,
    CONSTRAINT fk_cases_officer FOREIGN KEY (officer_id) REFERENCES users(user_id)
);

CREATE SEQUENCE cases_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_cases_bi
BEFORE INSERT ON cases
FOR EACH ROW
WHEN (NEW.case_id IS NULL)
BEGIN
    SELECT cases_seq.NEXTVAL INTO :NEW.case_id FROM dual;
END;
/


CREATE TABLE audit_logs (
    log_id      NUMBER PRIMARY KEY,
    user_id     NUMBER,
    action_type VARCHAR2(20),
    table_name  VARCHAR2(50),
    action_time TIMESTAMP DEFAULT SYSTIMESTAMP,
    description VARCHAR2(500)
);

CREATE SEQUENCE audit_logs_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_audit_logs_bi
BEFORE INSERT ON audit_logs
FOR EACH ROW
WHEN (NEW.log_id IS NULL)
BEGIN
    SELECT audit_logs_seq.NEXTVAL INTO :NEW.log_id FROM dual;
END;
/

CREATE OR REPLACE TRIGGER trg_case_audit
AFTER INSERT ON cases
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action_type, table_name, description)
    VALUES (
        :NEW.officer_id,
        'INSERT',
        'CASES',
        'New case created: ' || :NEW.case_title
    );
END;
/

INSERT INTO roles (role_name) VALUES ('Admin');
INSERT INTO roles (role_name) VALUES ('Officer');
INSERT INTO roles (role_name) VALUES ('Analyst');

INSERT INTO users (role_id, full_name, email, password, phone, status)
VALUES (1, 'System Admin', 'admin@cefl.test', 'hashed_password_here', '0000000000', 'ACTIVE');

INSERT INTO users (role_id, full_name, email, password, phone, status)
VALUES (2, 'Officer John Doe', 'officer.john@cefl.test', 'hashed_password_here', '0111111111', 'ACTIVE');

INSERT INTO users (role_id, full_name, email, password, phone, status)
VALUES (3, 'Analyst Jane Smith', 'analyst.jane@cefl.test', 'hashed_password_here', '0222222222', 'ACTIVE');

INSERT INTO cases (case_title, case_type, case_description, case_status, officer_id)
VALUES ('Downtown Robbery Case', 'Robbery', 'Robbery reported at downtown branch on 12th June', 'OPEN', 2);

COMMIT;

