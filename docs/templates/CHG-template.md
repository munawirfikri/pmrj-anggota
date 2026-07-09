# CHG-YYYY-NNN: [Title of the Change]

* **Date**: [YYYY-MM-DD]
* **Author**: [Name/Email]
* **Status**: [Draft | In Progress | Completed]
* **Related Issues/Debt**: [e.g., Resolves TD-XXX, relates to ADR-YYY]

## Summary

Provide a brief, high-level summary of what this change accomplishes.

## Background & Context

Explain why this change is necessary. What business or technical problems are we addressing?

## Current State

Describe how the system currently works prior to this change. 

## Proposed State

Describe how the system will work after this change is implemented. Highlight the key differences and improvements.

## Design Notes

Provide design details, architectural diagrams, database schema updates, or interface specifications relevant to the implementation.

## Expected Areas of Change

List the main directories and files that will be added, modified, or deleted:

* `[NEW]` [file basename](file:///path/to/newfile)
* `[MODIFY]` [file basename](file:///path/to/modifiedfile)
* `[DELETE]` [file basename](file:///path/to/deletedfile)

## Impact Analysis

Assess the impact of this change across the following areas:

* **Security**: Does this change introduce new endpoints, data inputs, or alter authentication/authorization?
* **Performance**: Are there changes to database queries, caching, or latency?
* **Backward Compatibility**: Will this break existing database records, APIs, or user configurations? If so, what is the mitigation?

## Testing Plan

Specify how this change will be validated:

### Automated Tests
- Command to run existing or new tests (e.g., `vendor/bin/phpunit`).

### Manual Verification
- Step-by-step instructions for manual validation (e.g., registration flow, photo upload check, database verification).

## Rollback Plan

Define how to revert this change in case of failure or issues in production:
1. Steps to rollback code.
2. Steps to rollback database migrations or data changes.

## Deployment Considerations

Identify any requirements for deployment:
* Run database migrations (`php artisan migrate`).
* Clear configuration cache (`php artisan config:clear`).
* Create symbolic links (`php artisan storage:link`).
* Any specific data backfills or seeding needed.
