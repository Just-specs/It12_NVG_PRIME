# DELETION & AUDIT LOG TESTING GUIDE

## Test Data Created
- **17 new drivers** (IDs: 19-35)
- **20 new vehicles** (IDs: 33-52)

## Testing Scenarios

### Scenario 1: Admin Direct Deletion (No Approval Needed)
1. Login as **admin**
2. Go to Drivers page
3. Try to delete "DANIEL GARCIA" (ID: 19)
4. Should delete immediately without approval
5. Check Audit Logs to see the deletion record

### Scenario 2: Head Dispatch Request Deletion
1. Login as **head_dispatch**
2. Go to Drivers page
3. Try to delete "JAMES MOORE" (ID: 20)
4. Should redirect to deletion request form
5. Fill in reason (min 10 chars): "Driver no longer available for work"
6. Submit request
7. Should see success message

### Scenario 3: Admin Approves Deletion Request
1. Login as **admin**
2. Check sidebar - should see notification badge on "Deletion Requests"
3. Click "Deletion Requests" menu
4. See pending request for "JAMES MOORE"
5. Click "Approve"
6. Add optional notes: "Approved - driver resigned"
7. Confirm approval
8. Driver should be deleted
9. Check Audit Logs for the deletion

### Scenario 4: Admin Rejects Deletion Request
1. Login as **head_dispatch**
2. Request deletion for "JAMES THOMAS" (ID: 21)
3. Reason: "Driver frequently absent"
4. Login as **admin**
5. Go to Deletion Requests
6. Click "Reject" on the request
7. Add rejection reason: "Need to review attendance records first"
8. Confirm rejection
9. Driver should NOT be deleted

### Scenario 5: Test Vehicle Deletion (Same Process)
1. Login as **head_dispatch**
2. Go to Vehicles page
3. Try to delete "ORP-9458" (ID: 33)
4. Fill deletion request form
5. Login as **admin** to approve

### Scenario 6: View Deleted Records
1. Go to Drivers page
2. Click "Deleted Records" submenu
3. Should see deleted drivers with:
   - Who deleted it
   - When it was deleted
   - Restore option (if needed)
4. Same for Vehicles

### Scenario 7: Check Audit Logs
1. Login as **admin**
2. Go to Admin > Audit Logs
3. Filter by:
   - Model: Driver or Vehicle
   - Action: deleted
4. Should see all deletion records with:
   - Who performed the action
   - What was deleted
   - When it happened
   - Old values (before deletion)

### Scenario 8: Restore Deleted Record
1. Go to Drivers > Deleted Records
2. Find a deleted driver
3. Click "Restore"
4. Driver should be active again
5. Check Audit Logs - should show "restored" action

## Database Locations

**Test Drivers (IDs 19-35):**
- DANIEL GARCIA (19)
- JAMES MOORE (20)
- JAMES THOMAS (21)
- ANTHONY ANDERSON (22)
- PAUL DAVIS (23)
- CHARLES DAVIS (24)
- RICHARD JOHNSON (25)
- ANTHONY JOHNSON (26)
- JOSHUA DAVIS (27)
- MARK MILLER (28)
- JAMES WILSON (29)
- RICHARD SMITH (30)
- DAVID JOHNSON (31)
- THOMAS MILLER (32)
- WILLIAM SMITH (33)
- RICHARD THOMAS (34)
- RICHARD MILLER (35)

**Test Vehicles (IDs 33-52):**
- ORP-9458 (33)
- PUX-7968 (34)
- PEX-2648 (35)
- OLH-1098 (36)
- IWR-4615 (37)
- UNL-9608 (38)
- GEE-1092 (39)
- NRF-2782 (40)
- KQR-9488 (41)
- EQM-8852 (42)
- PKY-4379 (43)
- RFG-5286 (44)
- XKO-1426 (45)
- SHD-6526 (46)
- FTS-6394 (47)
- QGG-9341 (48)
- ACH-7337 (49)
- YTY-1265 (50)
- XJO-8332 (51)
- OJH-8352 (52)

## Expected Results

### Audit Log Fields:
- **user_id**: Who performed the action
- **model_type**: Driver or Vehicle
- **model_id**: ID of the record
- **action**: created, updated, deleted, restored
- **old_values**: Record data before deletion (JSON)
- **new_values**: null for deletions
- **ip_address**: User's IP
- **user_agent**: Browser info
- **created_at**: Timestamp

### Deletion Request Fields:
- **requested_by**: Head dispatch user ID
- **resource_type**: driver, client, vehicle, delivery_request
- **resource_id**: ID of the record
- **reason**: Why deletion is needed
- **status**: pending, approved, rejected
- **reviewed_by**: Admin who reviewed
- **review_notes**: Admin's comments
- **reviewed_at**: When reviewed

## Cleanup After Testing

To remove test data after testing:
\\\ash
php artisan tinker --execute="
App\Models\Driver::whereIn('id', range(19, 35))->forceDelete();
App\Models\Vehicle::whereIn('id', range(33, 52))->forceDelete();
echo 'Test data removed';
"
\\\

## Notes

- Test data has random names, plates, and license numbers
- All test drivers have mobile format: 09XXXXXXXXX
- All test vehicles have 3-letter + 4-digit plate format
- Soft deletes are enabled - use forceDelete() to permanently remove
