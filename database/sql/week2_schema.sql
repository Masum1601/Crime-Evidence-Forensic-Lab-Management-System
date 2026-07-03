
-- 1. STORAGE_LOCATIONS table
CREATE TABLE storage_locations (
    location_id     NUMBER PRIMARY KEY,
    location_name   VARCHAR2(100) NOT NULL,
    room_no         VARCHAR2(20),
    shelf_no        VARCHAR2(20),
    locker_no       VARCHAR2(20),
    description     VARCHAR2(255)
);

CREATE SEQUENCE storage_locations_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_storage_locations_bi
BEFORE INSERT ON storage_locations
FOR EACH ROW
WHEN (NEW.location_id IS NULL)
BEGIN
    SELECT storage_locations_seq.NEXTVAL INTO :NEW.location_id FROM dual;
END;
/

CREATE TABLE evidence (
    evidence_id     NUMBER PRIMARY KEY,
    case_id         NUMBER NOT NULL,
    collected_by    NUMBER NOT NULL,
    location_id     NUMBER,
    evidence_name   VARCHAR2(150) NOT NULL,
    evidence_type   VARCHAR2(50),
    description     VARCHAR2(1000),
    collection_date DATE DEFAULT SYSDATE,
    current_status  VARCHAR2(30) DEFAULT 'IN_STORAGE'
                    CHECK (current_status IN ('IN_STORAGE', 'IN_ANALYSIS', 'IN_TRANSIT', 'RELEASED', 'DISPOSED')),
    barcode_no      VARCHAR2(50) UNIQUE,
    CONSTRAINT fk_evidence_case FOREIGN KEY (case_id) REFERENCES cases(case_id),
    CONSTRAINT fk_evidence_collector FOREIGN KEY (collected_by) REFERENCES users(user_id),
    CONSTRAINT fk_evidence_location FOREIGN KEY (location_id) REFERENCES storage_locations(location_id)
);

CREATE SEQUENCE evidence_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_evidence_bi
BEFORE INSERT ON evidence
FOR EACH ROW
WHEN (NEW.evidence_id IS NULL)
BEGIN
    SELECT evidence_seq.NEXTVAL INTO :NEW.evidence_id FROM dual;
END;
/

CREATE TABLE custody_records (
    custody_id      NUMBER PRIMARY KEY,
    evidence_id     NUMBER NOT NULL,
    from_user_id    NUMBER,
    to_user_id      NUMBER NOT NULL,
    transferred_by  NUMBER NOT NULL,
    transfer_date   TIMESTAMP DEFAULT SYSTIMESTAMP,
    reason          VARCHAR2(255),
    remarks         VARCHAR2(500),
    CONSTRAINT fk_custody_evidence FOREIGN KEY (evidence_id) REFERENCES evidence(evidence_id),
    CONSTRAINT fk_custody_from FOREIGN KEY (from_user_id) REFERENCES users(user_id),
    CONSTRAINT fk_custody_to FOREIGN KEY (to_user_id) REFERENCES users(user_id),
    CONSTRAINT fk_custody_transferredby FOREIGN KEY (transferred_by) REFERENCES users(user_id)
);

CREATE SEQUENCE custody_records_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER trg_custody_records_bi
BEFORE INSERT ON custody_records
FOR EACH ROW
WHEN (NEW.custody_id IS NULL)
BEGIN
    SELECT custody_records_seq.NEXTVAL INTO :NEW.custody_id FROM dual;
END;
/


CREATE OR REPLACE TRIGGER trg_custody_audit
AFTER INSERT ON custody_records
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (user_id, action_type, table_name, description)
    VALUES (
        :NEW.transferred_by,
        'TRANSFER',
        'CUSTODY_RECORDS',
        'Evidence #' || :NEW.evidence_id || ' transferred to user #' || :NEW.to_user_id
    );
END;
/

INSERT INTO storage_locations (location_name, room_no, shelf_no, locker_no, description)
VALUES ('Main Evidence Room', 'R-101', 'S-04', 'L-12', 'Primary secure evidence storage');

INSERT INTO storage_locations (location_name, room_no, shelf_no, locker_no, description)
VALUES ('Cold Storage', 'R-102', 'S-01', 'L-03', 'Temperature-controlled storage for biological evidence');

INSERT INTO evidence (case_id, collected_by, location_id, evidence_name, evidence_type, description, current_status, barcode_no)
VALUES (1, 2, 1, 'Bloodstained Knife', 'Weapon', 'Found at the scene near the back entrance', 'IN_STORAGE', 'EVD-0001');

INSERT INTO evidence (case_id, collected_by, location_id, evidence_name, evidence_type, description, current_status, barcode_no)
VALUES (1, 2, 2, 'Blood Sample', 'Biological', 'Sample taken from the weapon for DNA analysis', 'IN_STORAGE', 'EVD-0002');

INSERT INTO custody_records (evidence_id, from_user_id, to_user_id, transferred_by, reason, remarks)
VALUES (1, NULL, 2, 2, 'Initial collection', 'Collected at crime scene and logged into evidence room');

COMMIT;