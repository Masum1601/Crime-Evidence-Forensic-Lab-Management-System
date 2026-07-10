-- ============================================================
-- WEEK 3: Role-Based Access + Public Submissions +
--         Forensic Test Types + Test Requests
-- Run AFTER week1 and week2 scripts
-- ============================================================

-- ------------------------------------------------------------
-- 1. PUBLIC_SUBMISSIONS table
--    General public can submit tips/complaints without logging in
-- ------------------------------------------------------------
CREATE TABLE public_submissions (
    submission_id   NUMBER PRIMARY KEY,
    submitter_name  VARCHAR2(100) NOT NULL,
    submitter_email VARCHAR2(100),
    submitter_phone VARCHAR2(20),
    subject         VARCHAR2(200) NOT NULL,
    description     VARCHAR2(2000) NOT NULL,
    related_case_id NUMBER,
    status          VARCHAR2(20) DEFAULT 'PENDING'
                    CHECK (status IN ('PENDING', 'REVIEWED', 'DISMISSED')),
    submitted_at    TIMESTAMP DEFAULT SYSTIMESTAMP,
    reviewed_by     NUMBER,
    review_notes    VARCHAR2(500),
    CONSTRAINT fk_submission_case FOREIGN KEY (related_case_id) REFERENCES cases(case_id),
    CONSTRAINT fk_submission_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(user_id)
);

CREATE SEQUENCE public_submissions_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_public_submissions_bi
BEFORE INSERT ON public_submissions
FOR EACH ROW
WHEN (NEW.submission_id IS NULL)
BEGIN
    SELECT public_submissions_seq.NEXTVAL INTO :NEW.submission_id FROM dual;
END;
/

-- Auto-log every new public submission into audit_logs
CREATE OR REPLACE TRIGGER trg_submission_audit
AFTER INSERT ON public_submissions
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action_type, table_name, description)
    VALUES (
        NULL,
        'PUBLIC_SUBMIT',
        'PUBLIC_SUBMISSIONS',
        'New public submission: ' || :NEW.subject || ' by ' || :NEW.submitter_name
    );
END;
/

-- ------------------------------------------------------------
-- 2. FORENSIC_TEST_TYPES table
-- ------------------------------------------------------------
CREATE TABLE forensic_test_types (
    test_type_id        NUMBER PRIMARY KEY,
    test_name           VARCHAR2(100) NOT NULL,
    description         VARCHAR2(500),
    estimated_duration  VARCHAR2(50)
);

CREATE SEQUENCE forensic_test_types_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_forensic_test_types_bi
BEFORE INSERT ON forensic_test_types
FOR EACH ROW
WHEN (NEW.test_type_id IS NULL)
BEGIN
    SELECT forensic_test_types_seq.NEXTVAL INTO :NEW.test_type_id FROM dual;
END;
/

-- ------------------------------------------------------------
-- 3. TEST_REQUESTS table
-- ------------------------------------------------------------
CREATE TABLE test_requests (
    request_id          NUMBER PRIMARY KEY,
    evidence_id         NUMBER NOT NULL,
    test_type_id        NUMBER NOT NULL,
    requested_by        NUMBER NOT NULL,
    assigned_analyst_id NUMBER,
    request_date        DATE DEFAULT SYSDATE,
    test_status         VARCHAR2(20) DEFAULT 'PENDING'
                        CHECK (test_status IN ('PENDING', 'IN_PROGRESS', 'COMPLETED', 'CANCELLED')),
    priority            VARCHAR2(10) DEFAULT 'NORMAL'
                        CHECK (priority IN ('LOW', 'NORMAL', 'HIGH', 'URGENT')),
    notes               VARCHAR2(500),
    CONSTRAINT fk_testreq_evidence FOREIGN KEY (evidence_id) REFERENCES evidence(evidence_id),
    CONSTRAINT fk_testreq_testtype FOREIGN KEY (test_type_id) REFERENCES forensic_test_types(test_type_id),
    CONSTRAINT fk_testreq_requestedby FOREIGN KEY (requested_by) REFERENCES users(user_id),
    CONSTRAINT fk_testreq_analyst FOREIGN KEY (assigned_analyst_id) REFERENCES users(user_id)
);

CREATE SEQUENCE test_requests_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_test_requests_bi
BEFORE INSERT ON test_requests
FOR EACH ROW
WHEN (NEW.request_id IS NULL)
BEGIN
    SELECT test_requests_seq.NEXTVAL INTO :NEW.request_id FROM dual;
END;
/

-- ============================================================
-- SAMPLE DATA
-- ============================================================
INSERT INTO forensic_test_types (test_name, description, estimated_duration)
VALUES ('DNA Analysis', 'Full DNA extraction and profiling', '5-7 days');

INSERT INTO forensic_test_types (test_name, description, estimated_duration)
VALUES ('Fingerprint Analysis', 'Latent fingerprint lifting and matching', '2-3 days');

INSERT INTO forensic_test_types (test_name, description, estimated_duration)
VALUES ('Ballistics', 'Firearm and projectile analysis', '3-5 days');

INSERT INTO forensic_test_types (test_name, description, estimated_duration)
VALUES ('Toxicology', 'Chemical and substance testing', '7-10 days');

INSERT INTO public_submissions (submitter_name, submitter_email, submitter_phone, subject, description, status)
VALUES ('Anonymous Witness', 'witness@email.com', '01700000000', 'Suspicious Activity Near Warehouse', 'I saw two suspicious individuals near the warehouse on the night of the incident carrying bags.', 'PENDING');

COMMIT;