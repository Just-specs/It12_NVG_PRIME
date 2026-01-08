# DELIVERY_REQUEST_ID FIX - December 20, 2025

## Problem
When assigning drivers from the requests index page (/requests), the form was submitting with delivery_request_id = null, causing the error:
`
production.ERROR: Trip assignment failed: Missing delivery_request_id
`

## Root Cause
The hidden input field for delivery_request_id had value="" in the HTML:
`html
<input type="hidden" name="delivery_request_id" id="modal-request-id" value="">
`

When JavaScript set requestIdInput.value = requestId, it only changed the element's property, NOT the HTML attribute.
When form.reset() was called (in hideModal function), it reset the field back to the attribute value (empty string).

## Solution Applied

### Fix 1: Use setAttribute() to preserve value through form.reset()
`javascript
requestIdInput.value = requestId;
requestIdInput.setAttribute('value', requestId); // Set attribute so form.reset() preserves it
window.currentAssignRequestId = requestId;
`

### Fix 2: Restore value after form.reset() in hideModal()
`javascript
function hideModal() {
    const requestId = document.getElementById('modal-request-id')?.value;
    form.reset();
    // Restore the delivery_request_id after reset
    if (requestId && document.getElementById('modal-request-id')) {
        document.getElementById('modal-request-id').value = requestId;
    }
}
`

### Fix 3: Fallback restoration before form submission
`javascript
// CRITICAL FIX: Restore from global storage if missing
if ((!requestIdInput.value) && window.currentAssignRequestId) {
    requestIdInput.value = window.currentAssignRequestId;
}
`

## Commits
- 8df1a14: Initial fix with restoration logic
- 198a9a8: Added setAttribute() and hideModal() restoration

## Testing
After deployment completes, test by:
1. Go to /requests
2. Click "Assign Driver" on a verified request
3. Select driver and vehicle
4. Submit form
5. Verify trip is created successfully without error

## File Modified
- resources/views/dispatch/requests/partials/assign-driver-modal-table.blade.php
