-- ============================================================
-- WEEK 4a: Self-registration + Role Request Workflow
-- ============================================================

-- Add a 4th role: general "User" (pending / unassigned staff role)
INSERT INTO roles (role_name) VALUES ('User');
COMMIT;

-- ------------------------------------------------------------
-- ROLE_REQUESTS table — tracks a user's request to become
-- Officer or Analyst, and the admin's decision
-- ------------------------------------------------------------
CREATE TABLE role_requests (
    request_id      NUMBER PRIMARY KEY,
    user_id         NUMBER NOT NULL,
    requested_role_id NUMBER NOT NULL,
    status          VARCHAR2(20) DEFAULT 'PENDING'
                    CHECK (status IN ('PENDING', 'APPROVED', 'REJECTED')),
    reason          VARCHAR2(500),
    requested_at    TIMESTAMP DEFAULT SYSTIMESTAMP,
    reviewed_by     NUMBER,
    reviewed_at     TIMESTAMP,
    review_notes    VARCHAR2(500),
    CONSTRAINT fk_rolereq_user FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT fk_rolereq_role FOREIGN KEY (requested_role_id) REFERENCES roles(role_id),
    CONSTRAINT fk_rolereq_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(user_id)
);

CREATE SEQUENCE role_requests_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_role_requests_bi
BEFORE INSERT ON role_requests
FOR EACH ROW
WHEN (NEW.request_id IS NULL)
BEGIN
    SELECT role_requests_seq.NEXTVAL INTO :NEW.request_id FROM dual;
END;
/

-- Auto-log every role request + decision into audit_logs
CREATE OR REPLACE TRIGGER trg_role_request_audit
AFTER INSERT OR UPDATE ON role_requests
FOR EACH ROW
BEGIN
    IF INSERTING THEN
        INSERT INTO audit_logs (user_id, action_type, table_name, description)
        VALUES (:NEW.user_id, 'ROLE_REQUEST', 'ROLE_REQUESTS',
                'User #' || :NEW.user_id || ' requested role #' || :NEW.requested_role_id);
    ELSIF UPDATING AND :NEW.status != :OLD.status THEN
        INSERT INTO audit_logs (user_id, action_type, table_name, description)
        VALUES (:NEW.reviewed_by, 'ROLE_' || :NEW.status, 'ROLE_REQUESTS',
                'Request #' || :NEW.request_id || ' was ' || :NEW.status);
    END IF;
END;
/

COMMIT;
