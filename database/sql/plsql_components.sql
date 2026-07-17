-- ====================================================================
-- Crime Evidence & Forensic Lab Management System (CEFL)
-- PL/SQL Database Components (Procedures, Functions, Triggers, Cursors, Varrays, Nest Tables)
-- ====================================================================

-- --------------------------------------------------------------------
-- 1. PL/SQL COLLECTIONS (VARRAY and Nested Table Types)
-- --------------------------------------------------------------------

-- VARRAY Type for holding a fixed list of Forensic Method categories
CREATE OR REPLACE TYPE forensic_method_list AS VARRAY(10) OF VARCHAR2(100);
/

-- Nested Table Type for holding dynamic collections of evidence IDs
CREATE OR REPLACE TYPE evidence_id_list AS TABLE OF NUMBER;
/


-- --------------------------------------------------------------------
-- 2. PL/SQL FUNCTIONS
-- --------------------------------------------------------------------

-- Function to count active evidence pieces under a specific case
CREATE OR REPLACE FUNCTION get_evidence_count(p_case_id IN NUMBER) 
RETURN NUMBER 
IS
    v_count NUMBER := 0;
BEGIN
    SELECT COUNT(*) 
    INTO v_count 
    FROM evidence 
    WHERE case_id = p_case_id;
    
    RETURN v_count;
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END;
/

-- Function to check pending test requests load for an analyst
CREATE OR REPLACE FUNCTION get_analyst_load(p_analyst_id IN NUMBER)
RETURN NUMBER
IS
    v_load_count NUMBER := 0;
BEGIN
    SELECT COUNT(*)
    INTO v_load_count
    FROM test_requests
    WHERE assigned_analyst_id = p_analyst_id 
      AND test_status IN ('PENDING', 'IN_PROGRESS');
      
    RETURN v_load_count;
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END;
/


-- --------------------------------------------------------------------
-- 3. PL/SQL PROCEDURES WITH CURSORS
-- --------------------------------------------------------------------

-- Procedure to transfer case ownership from one officer to another
CREATE OR REPLACE PROCEDURE transfer_case_ownership(
    p_old_officer_id IN NUMBER,
    p_new_officer_id IN NUMBER
)
IS
    -- Cursor to select all open cases belonging to the old officer
    CURSOR c_old_cases IS
        SELECT case_id, case_title 
        FROM cases 
        WHERE officer_id = p_old_officer_id 
          AND case_status = 'OPEN';
          
    v_case_id    cases.case_id%TYPE;
    v_case_title cases.case_title%TYPE;
BEGIN
    -- Open cursor and loop through cases
    OPEN c_old_cases;
    LOOP
        FETCH c_old_cases INTO v_case_id, v_case_title;
        EXIT WHEN c_old_cases%NOTFOUND;
        
        -- Update the case owner
        UPDATE cases 
        SET officer_id = p_new_officer_id 
        WHERE case_id = v_case_id;
        
        -- Log the ownership change to the audit logs
        INSERT INTO audit_logs (user_id, action_type, table_name, description)
        VALUES (
            p_new_officer_id,
            'OWNERSHIP_TRANSFER',
            'CASES',
            'Ownership of Case #' || v_case_id || ' ("' || v_case_title || '") reassigned from Officer #' || p_old_officer_id
        );
    END LOOP;
    CLOSE c_old_cases;
    
    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        IF c_old_cases%ISOPEN THEN
            CLOSE c_old_cases;
        END IF;
        ROLLBACK;
        RAISE;
END;
/

-- Procedure to process custody transfers in bulk using the Nested Table collection type
CREATE OR REPLACE PROCEDURE process_bulk_custody_transfer(
    p_evidence_ids   IN evidence_id_list,
    p_from_user_id   IN NUMBER,
    p_to_user_id     IN NUMBER,
    p_transferred_by IN NUMBER,
    p_reason         IN VARCHAR2
)
IS
BEGIN
    -- Loop through the collection of evidence IDs
    FOR i IN 1..p_evidence_ids.COUNT LOOP
        -- Insert a new custody record
        INSERT INTO custody_records (evidence_id, from_user_id, to_user_id, transferred_by, reason)
        VALUES (p_evidence_ids(i), p_from_user_id, p_to_user_id, p_transferred_by, p_reason);
        
        -- Update evidence status to in transit or in storage depending on context
        UPDATE evidence 
        SET current_status = 'IN_STORAGE' 
        WHERE evidence_id = p_evidence_ids(i);
    END LOOP;
    
    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20001, 'Failed to complete bulk custody transfer operation: ' || SQLERRM);
END;
/


-- --------------------------------------------------------------------
-- 4. ADDITIONAL BUSINESS TRIGGERS
-- --------------------------------------------------------------------

-- Trigger to prevent adding new evidence to a CLOSED case
CREATE OR REPLACE TRIGGER trg_restrict_closed_case_evidence
BEFORE INSERT ON evidence
FOR EACH ROW
DECLARE
    v_case_status VARCHAR2(20);
BEGIN
    SELECT case_status 
    INTO v_case_status 
    FROM cases 
    WHERE case_id = :NEW.case_id;
    
    IF v_case_status = 'CLOSED' THEN
        RAISE_APPLICATION_ERROR(-20002, 'Cannot add new evidence to a closed case.');
    END IF;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(-20003, 'Referenced case ID does not exist.');
END;
/

-- Trigger to prevent transferring custody to yourself
CREATE OR REPLACE TRIGGER trg_prevent_self_transfer
BEFORE INSERT ON custody_records
FOR EACH ROW
BEGIN
    IF :NEW.from_user_id = :NEW.to_user_id THEN
        RAISE_APPLICATION_ERROR(-20004, 'Origin and destination custodian users cannot be identical.');
    END IF;
END;
/


-- --------------------------------------------------------------------
-- 5. DEMONSTRATIVE CURSOR BLOCK (ANONYMOUS PL/SQL BLOCK)
-- --------------------------------------------------------------------
-- This block selects all equipment logs with 'NEEDS_MAINTENANCE' status,
-- loops through using a cursor, and logs simulated notification details.

DECLARE
    CURSOR c_equipment_maintenance IS
        SELECT equipment_id, equipment_name, condition_status 
        FROM equipment
        WHERE condition_status = 'NEEDS_MAINTENANCE';
        
    r_equip c_equipment_maintenance%ROWTYPE;
BEGIN
    DBMS_OUTPUT.PUT_LINE('--- Scanning Equipment Requiring Maintenance ---');
    OPEN c_equipment_maintenance;
    LOOP
        FETCH c_equipment_maintenance INTO r_equip;
        EXIT WHEN c_equipment_maintenance%NOTFOUND;
        
        DBMS_OUTPUT.PUT_LINE('ALERT: Device #' || r_equip.equipment_id || 
                             ' (' || r_equip.equipment_name || ') status is ' || r_equip.condition_status);
        
        -- Insert warning logs
        INSERT INTO audit_logs (user_id, action_type, table_name, description)
        VALUES (
            NULL, 
            'MAINTENANCE_SCAN', 
            'EQUIPMENT', 
            'System scanner logged maintenance warning for: ' || r_equip.equipment_name
        );
    END LOOP;
    CLOSE c_equipment_maintenance;
    COMMIT;
END;
/
