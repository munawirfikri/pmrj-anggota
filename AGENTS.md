# AGENTS.md

# AI Agent Operating Instructions

This repository follows a documentation-first and architecture-driven engineering model.

The purpose of this document is to guide AI agents when analyzing, designing, implementing, reviewing, or modifying the system.

The goal is not simply to generate code.

The goal is to make safe, maintainable, traceable changes while preserving architectural consistency, operational stability, and project knowledge.

---

# Primary Responsibilities

Before making any recommendation or change, the AI agent must:

* Understand the business context (Sistem Informasi Anggota PMRJ)
* Understand architectural constraints (Laravel 8.x, PostgreSQL, Custom Auth Guard)
* Understand existing design decisions (Image compression, Auto-generation of member numbers)
* Understand ongoing changes
* Minimize implementation risk
* Preserve maintainability
* Preserve backward compatibility whenever possible

The AI agent should prioritize correctness and maintainability over implementation speed.

---

# Service Boundary

This application is a monolithic web service built on Laravel 8.x.

Authentication and authorization are handled in-app using separate guards:

* The default Laravel guard `web` is reserved for standard administrator/user context.
* A custom guard `anggota` is dedicated exclusively to members (anggota) using the `App\Models\Anggota` model.

Therefore, this service **MUST**:

* Protect all member routes with `auth:anggota` middleware.
* Separate session/auth contexts between admin users and members to avoid overlap.
* Auto-generate member numbers (`no_anggota`) using the format `PMRJ-{IKK_CODE}-{COUNTER}`.
* Automatically compress and store all uploaded member photos/KTP files via the `CompressesImages` trait.
* Ensure files are stored in `storage/app/public/photos/` and made accessible via symbolic links under `public/storage/`.

---

# Mandatory Context Retrieval Order

Before proposing or implementing changes, retrieve context in the following order.

## Step 1 — Project Domain Context

Read:

```text
docs/project/project-context.md
docs/project/business-rules.md
```

Purpose:

Understand the business domain, system purpose, constraints, and expected behavior.

---

## Step 2 — Architecture Context

Read:

```text
docs/architecture/system-overview.md
docs/architecture/request-flow.md
docs/architecture/security-model.md
docs/architecture/integration-map.md
```

Purpose:

Understand the overall architecture, request flow, service boundaries, and responsibilities delegated to upstream infrastructure.

---

## Step 3 — Architecture Decision Records (ADR)

Read relevant ADR documents.

Location:

```text
docs/architecture/adr/
```

Purpose:

Understand previous architectural decisions and constraints.

Do not violate accepted ADRs without proposing a new Architecture Decision Record.

---

## Step 4 — Technical Debt & Backlog

Read:

```text
docs/backlog/technical-debt.md
docs/backlog/debt/
```

Purpose:

Understand known technical issues, implementation constraints, and planned improvements.

---

## Step 5 — Active Changes

Read relevant documents.

Location:

```text
docs/changes/active/
```

Purpose:

Understand ongoing work and avoid conflicting implementations.

---

## Step 6 — Source Code

Only after understanding the documentation should source code analysis begin.

---

# Working Rules

## Understand Before Changing

Always understand:

* Problem statement
* Existing behavior
* Constraints
* Risks
* Dependencies

Analyze before modifying.

---

## Respect Existing Architecture

Follow established architecture and project conventions.

Prefer consistency over introducing new patterns.

Avoid architectural drift.

---

## Minimize Blast Radius

Prefer targeted changes.

Avoid modifying unrelated components.

Avoid unnecessary refactoring.

---

## Preserve Backward Compatibility

Avoid breaking existing database records or validation behaviors unless explicitly required.

When breaking changes are unavoidable:

* Clearly identify impacted database models or UI screens.
* Explain migration considerations (especially data backfills for `no_anggota`).
* Explain associated risks.

---

## Prefer Existing Patterns

When multiple implementation options exist, prefer patterns already used within the repository (e.g. standardizing validation messages in Indonesian, checking unique constraints excluding current ID).

Avoid introducing unnecessary frameworks, abstractions, or architectural styles.

---

# AI Guardrails

Unless explicitly requested or documented otherwise, do **NOT**:

* Modify or bypass the custom guard `anggota` with default Laravel `web` authentication.
* Upload member profile pictures manually using standard `$file->store()`; always call `compressAndStore()` from the `CompressesImages` trait.
* Leave old photo files in storage when updating a profile picture; always delete old photos to prevent storage build-up.
* Run raw database updates without writing proper Laravel migrations.
* Generate `no_anggota` manually without using `generateNoAnggota()` on the model.

---

# Domain Validation

Business validation remains the responsibility of this service.

For `Anggota` profiles, ensure:

* The email is valid, unique, and nullable (excluding the current user ID on updates).
* The NIK (Nomor Induk Kependudukan) is unique and exactly 16 digits.
* Asal IKK must exist in the `ikk` table.
* Form validation messages must be in Indonesian.
* Recalculate and update the member number (`no_anggota`) whenever the IKK (`asal_ikk`) changes.

Do not confuse business validation with authentication or authorization.

---

# Security Awareness

Always evaluate:

* Input validation on member data.
* Unique field constraints during profile updates.
* Password hashing (`Hash::make`) when modifying member passwords.
* Protection against mass assignment (`$fillable` attributes in `Anggota.php`).
* Cross-Site Request Forgery (CSRF) protection in Blade forms.

Do not weaken the existing security posture.

---

# Operational Awareness

Consider:

* Symbolic link setup (`php artisan storage:link`) for accessing user files.
* Cache management: cleaning configurations (`php artisan config:clear`) and routes (`php artisan route:clear`) on environment changes.
* Development server execution using `php artisan serve`.
* PostgreSQL compatibility (case sensitivity on string lookups, boolean checks).

Implementation should remain supportable in production.

---

# Documentation Rules

## Documentation Before Significant Changes

For significant changes, ensure design and requirements are documented before implementation.

Examples include:

* New fields in the `anggota` schema.
* New master tables for member attributes.
* Logic updates in image compression or format requirements.
* Integration of QR codes or PDF generation tools.

---

## Preserve Historical Knowledge

Do not delete:

* Existing migrations.
* Trait configurations.
* Crucial documentation such as `README.md` and `AGENTS.md`.

---

## Keep Documentation Consistent

When behavior changes, review whether related documentation should also be updated.

Examples:

* Database schemas in `README.md`.
* Custom validation fields or endpoints.

---

# Architecture Decision Rules

The following changes require additional architectural review:

* Changing custom guard settings in `config/auth.php`.
* Modifying default image compression rules (e.g. changing output format from JPG or maximum resolution).
* Introducing external API connections.
* Altering the generation logic of `no_anggota`.

Review existing setup before proposing architectural changes.

---

# Implementation Expectations

Before any code modification or implementation is performed, the AI agent **MUST** follow this strict flow:

1. **Analyze and Research**: Understand the codebase, dependencies, constraints, and requirements before proposing any changes.
2. **Create a Plan**: Prepare a structured change plan (such as `implementation_plan.md` in planning mode or a change proposal). The plan must include:
   * Summary of changes.
   * Affected files (migrations, models, controllers, blade files).
   * Database migration/seeding plans.
   * Testing plans using PHPUnit.
3. **Obtain Approval**: Present the plan to the user and **STOP**. Do **NOT** perform any code implementation or database updates until the user has reviewed and explicitly approved the plan.
4. **Execute**: Once approved, proceed with the implementation, tracking progress using a task list (such as `task.md`) and documenting changes via a walkthrough (such as `walkthrough.md`).

Implementation should prioritize:

* Clear, clean code (PSR-12).
* Tailwind CSS utility styling matching PMRJ branding (Blue #3985c3).
* Font Awesome 6 icons.
* Non-blocking, targeted modifications.

---

# Testing Expectations

Every significant change should be validated.

Consider:

* Member registration and login flows.
* Profile updates (verifying validation triggers and image compression).
* Re-generation of `no_anggota` when changing IKK.
* PHPUnit execution via `vendor/bin/phpunit`.

Testing depth should match implementation risk.

---

# Review Expectations

When reviewing code, evaluate:

* Correctness of Laravel routes and custom auth guard behavior.
* Formatting and styling consistency.
* Safety of image uploads and storage cleanup.
* SQL query syntax compatibility with PostgreSQL.

Review both implementation quality and long-term sustainability.

---

# Prohibited Behavior

Do not:

* Skip context retrieval.
* Bypass `CompressesImages` when handling member photo files.
* Modify database structures directly on PostgreSQL without Laravel migrations.
* Mix admin `web` guard context with member `anggota` guard context.
* Leave old unused files in storage during updates.
* Perform unrelated refactoring or introduce unnecessary complexity.

---

# Preferred Decision Framework

When multiple solutions are available, prefer the solution that:

1. Solves the member's registration or profile update problem.
2. Aligns with custom auth guard and Laravel 8.x architecture.
3. Minimizes storage impact via compression and cleanup.
4. Preserves Indonesian error messages.
5. Keeps code clean and Tailwind UI consistent.

---

# Standard Change Flow

```text
Discovery
    ↓
Analysis
    ↓
Design
    ↓
Implementation
    ↓
Testing
    ↓
Review
    ↓
Verification
    ↓
Documentation Update
```

---

# Definition of Successful Change

A successful change:

* Solves the intended feature request or bug.
* Preserves separation between administrative users and member profiles.
* Optimizes file storage and maintains database consistency.
* Retains responsive Tailwind CSS mobile and desktop interfaces.
* Remains clean, readable, and fully documented in English/Indonesian as appropriate.

---

# Final Principle

Always optimize for:

* Long-term maintainability.
* Custom auth guard security consistency.
* Optimal storage utilization.
* Accurate membership numbering.

The objective is not to generate the most code.

The objective is to make the safest, simplest, and most sustainable improvement to the system.
