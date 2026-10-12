# QA Testing with MySQL Data Verification

**Repository:** `QA-Testing-With-MySQL-DataVerification-WendelFlores`  
**Test Case ID:** TC-DataEntry-001  
**Test Case Title:** Data Entry - Valid  
**Application:** Kara David's Wish Note  
**Testing Type:** Functional Testing and Database Verification

## 1. Repository Overview

This repository contains the test documentation, AI-generation prompt, and single-file PHP application used to test the Kara David's Wish Note data-entry workflow.

The toolkit consists of:

- **Part A — `readme.md`:** Repository overview and test case documentation.
- **Part B — `webapp-generation-prompt.txt`:** The prompt used to specify the AI-generated web application.
- **Part C — `test-webapp.php`:** The single-file PHP application containing embedded HTML, CSS, JavaScript, and MySQL database operations.
- **Additional documentation — `TestCase.txt`:** The original test case steps and results.

The filename `textcase.txt` is referenced here as required by the assignment's formatting instructions. The actual test case file in this repository is named `TestCase.txt`.

## 2. Test Case Documentation

### 2.1 Test Case Details

| Field | Description |
|---|---|
| Test Case ID | TC-DataEntry-001 |
| Test Case Title | Data Entry - Valid |
| Description | Functional testing of the data-entry page with backend verification using MySQL queries. |
| Preconditions | The application, Apache, and MariaDB are running locally; the `birthday_wishnote` database and `main_corrupt_politicians` table exist. |
| Test Data | Five valid entries containing First Name, Last Name, Government Position, and Location within their maximum character limits. |
| Browser | Local browser testing |
| Overall Status | **Failed — Location was not saved to the database.** |

### 2.2 Test Steps, Expected Results, and Actual Results

| Step | Action | Expected Result | Actual Result |
|---|---|---|---|
| a | Verify the form labels' order and confirm that all text boxes are writable. | Labels appear in the order First Name, Last Name, Government Position, and Location. All text boxes are writable. | The four fields appeared in the required order and were writable. |
| b | Verify the "Send Wish Note" button. | The button is enabled and changes color on hover. | The button was enabled. Hover-color behavior requires confirmation. |
| c | Enter valid test data in the respective fields. | All fields accept valid data within their maximum character limits. | The application accepted the sample entries. |
| d | Click "Send Wish Note". | A verification modal appears. | A modal appeared during submission. |
| e | Compare the modal values with the entered test data. | The modal displays all four entered values correctly. | *Pending Execution* — exact displayed values have not been confirmed. |
| f | Click "Cancel". | The modal closes without submitting the form. | *Pending Execution* — cancellation behavior has not been confirmed. |
| g | Submit again and click "Confirm & Send". | The form submits the data after confirmation. | The application submitted the data after confirmation. |
| h | Verify the success message. | "Success! The wish note has been sent." appears. | The success message appeared after submission. |
| i | Query the database to verify all submitted fields. | First Name, Last Name, Government Position, and Location are correctly stored. | The query returned names and government positions, but `Location` was `NULL`. The expected database result was not achieved. |

### 2.3 Database Verification

Execute the following query in MariaDB:

```sql
USE birthday_wishnote;

SELECT FirstName, LastName, GovPosition, Location
FROM main_corrupt_politicians;
```

The query verifies whether the submitted values exist in the database. During testing, the `Location` column returned `NULL` for the observed records.

### 2.4 Test Conclusion

**Overall Status: Failed**

The application accepted the submitted data and displayed its success message. However, database verification revealed that the submitted Location value was not stored.

The PHP application's SQL `INSERT` statement omits the `Location` column and does not bind the location value. Consequently, the database stores `NULL` in that column for these submissions. This is the intentional flaw specified in the application-generation prompt.

The result demonstrates why a successful front-end message alone is insufficient to confirm that all submitted data has been persisted correctly.

## 3. Technologies Used

- PHP
- MariaDB / MySQL
- HTML
- CSS
- Vanilla JavaScript
- Apache HTTP Server
- Kali Linux

## 4. Repository Files

| File | Purpose |
|---|---|
| `readme.md` | Project overview, test results, and database verification documentation |
| `webapp-generation-prompt.txt` | AI prompt specifying the application requirements and intentional SQL flaw |
| `test-webapp.php` | PHP application under test |
| `TestCase.txt` | Detailed test case source documentation |

## 5. Notes and Limitations

- The test database was created locally for this exercise.
- The recorded database observations demonstrate the missing Location insertion.
- The assignment specifies five valid test entries; ensure all five have been submitted and verified before claiming that requirement is complete.
- The reported test results reflect observed behavior; steps marked *Pending Execution* still require confirmation.
- Do not publish real database passwords, private credentials, or production connection details in this repository.
