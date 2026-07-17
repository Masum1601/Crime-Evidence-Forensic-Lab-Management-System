-- database/sql/week4b_schema.sql (part 1)

ALTER TABLE public_submissions ADD submitted_by NUMBER;
ALTER TABLE public_submissions ADD CONSTRAINT fk_submission_user
    FOREIGN KEY (submitted_by) REFERENCES users(user_id);
COMMIT;

-- database/sql/week4b_schema.sql (part 2)

CREATE TABLE court_submissions (
    submission_id     NUMBER PRIMARY KEY,
    evidence_id        NUMBER NOT NULL,
    submitted_by        NUMBER NOT NULL,
    court_name          VARCHAR2(150) NOT NULL,
    case_reference_no    VARCHAR2(100),
    submission_date     DATE DEFAULT SYSDATE,
    return_date          DATE,
    status               VARCHAR2(20) DEFAULT 'SUBMITTED'
                         CHECK (status IN ('SUBMITTED', 'RETURNED', 'RETAINED')),
    remarks              VARCHAR2(500),
    CONSTRAINT fk_courtsub_evidence FOREIGN KEY (evidence_id) REFERENCES evidence(evidence_id),
    CONSTRAINT fk_courtsub_user FOREIGN KEY (submitted_by) REFERENCES users(user_id)
);

CREATE SEQUENCE court_submissions_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_court_submissions_bi
BEFORE INSERT ON court_submissions
FOR EACH ROW
WHEN (NEW.submission_id IS NULL)
BEGIN
    SELECT court_submissions_seq.NEXTVAL INTO :NEW.submission_id FROM dual;
END;
/

-- Auto-update evidence status when submitted to court
CREATE OR REPLACE TRIGGER trg_court_submission_evidence
AFTER INSERT ON court_submissions
FOR EACH ROW
BEGIN
    UPDATE evidence SET current_status = 'IN_TRANSIT' WHERE evidence_id = :NEW.evidence_id;

    INSERT INTO audit_logs (user_id, action_type, table_name, description)
    VALUES (:NEW.submitted_by, 'COURT_SUBMIT', 'COURT_SUBMISSIONS',
            'Evidence #' || :NEW.evidence_id || ' submitted to ' || :NEW.court_name);
END;
/

COMMIT;

-- database/sql/week4b_schema.sql (part 3)

CREATE TABLE test_reports (
    report_id       NUMBER PRIMARY KEY,
    request_id      NUMBER NOT NULL,
    result_summary  VARCHAR2(500) NOT NULL,
    detailed_report VARCHAR2(2000),
    report_date     TIMESTAMP DEFAULT SYSTIMESTAMP,
    verified_by     NUMBER,
    CONSTRAINT fk_report_request FOREIGN KEY (request_id) REFERENCES test_requests(request_id),
    CONSTRAINT fk_report_verifier FOREIGN KEY (verified_by) REFERENCES users(user_id)
);

CREATE SEQUENCE test_reports_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_test_reports_bi
BEFORE INSERT ON test_reports
FOR EACH ROW
WHEN (NEW.report_id IS NULL)
BEGIN
    SELECT test_reports_seq.NEXTVAL INTO :NEW.report_id FROM dual;
END;
/

-- Auto-mark test request COMPLETED when a report is filed
CREATE OR REPLACE TRIGGER trg_report_completes_test
AFTER INSERT ON test_reports
FOR EACH ROW
BEGIN
    UPDATE test_requests SET test_status = 'COMPLETED' WHERE request_id = :NEW.request_id;
END;
/

COMMIT;

-- database/sql/week4b_schema.sql (part 4)

CREATE TABLE equipment (
    equipment_id       NUMBER PRIMARY KEY,
    equipment_name     VARCHAR2(150) NOT NULL,
    equipment_type     VARCHAR2(50),
    serial_no          VARCHAR2(100) UNIQUE,
    condition_status   VARCHAR2(20) DEFAULT 'GOOD'
                       CHECK (condition_status IN ('GOOD', 'NEEDS_MAINTENANCE', 'OUT_OF_SERVICE')),
    availability_status VARCHAR2(20) DEFAULT 'AVAILABLE'
                       CHECK (availability_status IN ('AVAILABLE', 'IN_USE', 'RESERVED'))
);

CREATE SEQUENCE equipment_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_equipment_bi
BEFORE INSERT ON equipment
FOR EACH ROW
WHEN (NEW.equipment_id IS NULL)
BEGIN
    SELECT equipment_seq.NEXTVAL INTO :NEW.equipment_id FROM dual;
END;
/

CREATE TABLE equipment_usage (
    usage_id      NUMBER PRIMARY KEY,
    equipment_id  NUMBER NOT NULL,
    request_id    NUMBER,
    used_by       NUMBER NOT NULL,
    usage_date    TIMESTAMP DEFAULT SYSTIMESTAMP,
    remarks       VARCHAR2(500),
    CONSTRAINT fk_usage_equipment FOREIGN KEY (equipment_id) REFERENCES equipment(equipment_id),
    CONSTRAINT fk_usage_request FOREIGN KEY (request_id) REFERENCES test_requests(request_id),
    CONSTRAINT fk_usage_user FOREIGN KEY (used_by) REFERENCES users(user_id)
);

CREATE SEQUENCE equipment_usage_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_equipment_usage_bi
BEFORE INSERT ON equipment_usage
FOR EACH ROW
WHEN (NEW.usage_id IS NULL)
BEGIN
    SELECT equipment_usage_seq.NEXTVAL INTO :NEW.usage_id FROM dual;
END;
/

-- Auto-flip equipment to IN_USE when logged, back to AVAILABLE isn't automatic
-- (kept manual since usage duration isn't tracked here)
CREATE OR REPLACE TRIGGER trg_equipment_usage_status
AFTER INSERT ON equipment_usage
FOR EACH ROW
BEGIN
    UPDATE equipment SET availability_status = 'IN_USE' WHERE equipment_id = :NEW.equipment_id;
END;
/

-- Sample data
INSERT INTO equipment (equipment_name, equipment_type, serial_no, condition_status, availability_status)
VALUES ('DNA Sequencer Model X200', 'Sequencer', 'SN-DNA-2201', 'GOOD', 'AVAILABLE');

INSERT INTO equipment (equipment_name, equipment_type, serial_no, condition_status, availability_status)
VALUES ('Fingerprint Scanner FP-500', 'Scanner', 'SN-FP-0087', 'GOOD', 'AVAILABLE');

COMMIT;


