# Implementation Plan

## Bug 1: Flag Button Disabled on Locked Status

- [ ] 1. Add guard clause to `toggleFlag()` function
  - Open `resources/views/dashboard/rubrik.blade.php`
  - Locate the `toggleFlag(questionId)` method in the Alpine.js component
  - Add early return: `if (!this.is_edit_enabled) return;` as the first line of the function
  - This prevents flag state from being modified when status is SUBMITTED/GRADED/PUBLISHED
  - _Bug_Condition: isBugCondition_Flag(input) where is_edit_enabled = false AND status IN ['SUBMITTED', 'GRADED', 'PUBLISHED']_
  - _Expected_Behavior: flag state unchanged, no sessionStorage update_
  - _Preservation: toggleFlag still works normally when is_edit_enabled = true (DRAFT/IN_PROGRESS)_
  - _Requirements: 2.1, 3.1, 3.2_

- [ ] 2. Add `:disabled` binding to flag button HTML element
  - Locate the flag button element (bookmark icon button) in the question template
  - Add `:disabled="!is_edit_enabled"` attribute to the button
  - Add `disabled:opacity-50 disabled:cursor-not-allowed` to the button's class list
  - This provides visual feedback that the flag is non-interactive on locked status
  - _Bug_Condition: button should be disabled when is_edit_enabled = false_
  - _Preservation: button remains enabled and clickable when is_edit_enabled = true_
  - _Requirements: 2.1, 3.1_

## Bug 2: Drawer Indicator Reactivity Fix

- [ ] 3. Pre-initialize all question keys in `answers` object during `applyData()`
  - Locate the `applyData(data)` method in the Alpine.js component
  - After the existing answer population logic, iterate all questions
  - For each question whose key does NOT already exist in `this.answers`, set `this.answers[q.id] = ''`
  - This ensures Alpine.js proxy tracks all keys from the start, enabling reactive updates
  - _Bug_Condition: isBugCondition_Drawer(input) where questionId NOT IN Object.keys(answers)_
  - _Expected_Behavior: fillStatus(qId) reflects new value immediately after user input_
  - _Preservation: existing answers populated from API remain unchanged_
  - _Requirements: 2.2, 2.3, 3.4_

- [ ] 4. Pre-initialize all question keys in `links` object during `applyData()`
  - In the same `applyData(data)` method, after the link population logic
  - For each question whose key does NOT already exist in `this.links`, set `this.links[q.id] = ''`
  - Same rationale as task 3 — Alpine.js needs pre-existing keys for reactivity
  - _Bug_Condition: link key not in Object.keys(links) causes fillStatus to not react_
  - _Expected_Behavior: fillStatus(qId) updates immediately when link is first entered_
  - _Preservation: existing links populated from API remain unchanged_
  - _Requirements: 2.2, 2.3, 3.4_

## Validation

- [ ] 5. Manual verification checkpoint
  - Verify Bug 1 fix: Load rubrik with SUBMITTED status → confirm flag buttons are disabled (opacity reduced, cursor not-allowed, click does nothing)
  - Verify Bug 1 preservation: Load rubrik with DRAFT status → confirm flag buttons still toggle normally and persist to sessionStorage
  - Verify Bug 2 fix: Load rubrik with unanswered questions → select an answer → confirm drawer indicator changes color immediately (grey → yellow/green)
  - Verify Bug 2 preservation: Load rubrik with existing answers from API → confirm drawer indicators show correct colors on page load
  - Ensure no console errors in browser DevTools
  - _Requirements: 2.1, 2.2, 2.3, 3.1, 3.2, 3.3, 3.4, 3.5_
