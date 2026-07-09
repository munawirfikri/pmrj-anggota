# Documentation Standard

## Purpose

This document defines the documentation structure, organization model, ownership rules, and documentation conventions used across all application repositories.

The objectives are:

* Preserve project knowledge
* Improve onboarding efficiency
* Reduce knowledge silos
* Improve maintainability
* Support AI-assisted development
* Improve context retrieval
* Establish consistent documentation across projects

Documentation should explain information that cannot be easily derived from source code.

---

# Documentation Philosophy

Documentation exists to explain:

* Why the system exists
* What the system does
* How the system works
* Why decisions were made
* What constraints exist
* What changes occurred
* How the system is operated

Documentation should focus on:

* Intent
* Decisions
* Constraints
* Architecture
* Business Rules
* Operational Knowledge

Documentation should not duplicate source code.

Documentation should not become a second implementation.

---

# Repository Documentation Blueprint

Every repository should follow the structure below.

```text
docs/
│
├── templates/
│   ├── ADR-template.md
│   ├── CHG-template.md
│   └── TD-template.md
│
├── project/
│   ├── project-context.md
│   ├── business-rules.md
│   ├── glossary.md
│   └── coding-standards.md
│
├── architecture/
│   ├── system-overview.md
│   ├── request-flow.md
│   ├── security-model.md
│   ├── integration-map.md
│   │
│   └── adr/
│       ├── ADR-001-*.md
│       └── ADR-XXX-*.md
│
├── backlog/
│   ├── technical-debt.md
│   ├── feature-backlog.md
│   ├── roadmap.md
│   │
│   └── debt/
│       ├── TD-001-*.md
│       └── TD-XXX-*.md
│
├── changes/
│   ├── active/
│   └── completed/
│
├── testing/
│   ├── test-strategy.md
│   ├── security-test-checklist.md
│   └── performance-baseline.md
│
└── operations/
    ├── deployment.md
    ├── rollback.md
    ├── runbook.md
    ├── troubleshooting.md
    ├── monitoring.md
    └── disaster-recovery.md
```

---

# Required Documentation

The following documents are mandatory for every repository.

```text
docs/project/project-context.md

docs/project/business-rules.md

docs/architecture/system-overview.md

docs/architecture/request-flow.md

docs/backlog/technical-debt.md

docs/backlog/feature-backlog.md

docs/testing/test-strategy.md

docs/operations/runbook.md

docs/templates/ADR-template.md

docs/templates/CHG-template.md

docs/templates/TD-template.md
```

Additional documentation may be added as needed.

---

# Knowledge Domains

Documentation is organized into six knowledge domains.

```text
Project
Architecture
Backlog
Changes
Testing
Operations
```

Each domain has a specific responsibility.

Documentation should be placed in the correct domain.

---

# Project Domain

Location:

```text
docs/project/
```

Purpose:

Store stable project knowledge.

Documents:

```text
project-context.md
business-rules.md
glossary.md
coding-standards.md
```

Typical Content:

* Business objectives
* Core capabilities
* Domain terminology
* Repository conventions
* Business rules

---

# Architecture Domain

Location:

```text
docs/architecture/
```

Purpose:

Explain how the system works.

Documents:

```text
system-overview.md
request-flow.md
security-model.md
integration-map.md
```

Typical Content:

* System structure
* Component responsibilities
* Request lifecycle
* Security design
* External dependencies

---

# Architecture Decision Records

Location:

```text
docs/architecture/adr/
```

Purpose:

Preserve significant architectural decisions.

Naming Convention:

```text
ADR-001-title.md
ADR-002-title.md
ADR-003-title.md
```

Architecture decisions should remain available throughout the lifetime of the project.

Do not delete historical ADRs.

---

# Backlog Domain

Location:

```text
docs/backlog/
```

Purpose:

Track future work and improvement opportunities.

Documents:

```text
technical-debt.md
feature-backlog.md
roadmap.md
```

---

## technical-debt.md

Purpose:

Serve as the master index of all technical debt items.

Recommended Fields:

```text
ID
Title
Category
Priority
Status
Owner
```

Small technical debt items may exist only in this file.

Large technical debt items should have dedicated debt documents.

---

## debt/

Location:

```text
docs/backlog/debt/
```

Purpose:

Store detailed technical debt analysis.

Naming Convention:

```text
TD-001-title.md
TD-002-title.md
TD-003-title.md
```

Recommended Usage:

* Security issues
* Performance issues
* Scalability issues
* Reliability issues
* Architectural issues
* Major refactoring efforts

---

# Change Domain

Location:

```text
docs/changes/
```

Purpose:

Preserve project evolution and implementation history.

Structure:

```text
active/
completed/
```

Naming Convention:

```text
CHG-YYYY-NNN-title.md
```

Examples:

```text
CHG-2026-001-api-key-authentication.md

CHG-2026-002-hmac-hardening.md

CHG-2026-003-redis-optimization.md
```

Change documents should explain:

* What changed
* Why it changed
* Impact of the change
* Rollback considerations

Completed change documents should not be deleted.

---

# Technical Debt Traceability

Technical debt and implementation history should be linked.

Example:

```text
TD-002
        ↓
CHG-2026-004
```

Recommended flow:

```text
Problem
        ↓
Debt Analysis
        ↓
Implementation
        ↓
Historical Record
```

Technical debt documents should reference related change documents.

Change documents should reference resolved technical debt items.

This preserves long-term project knowledge.

---

# Testing Domain

Location:

```text
docs/testing/
```

Purpose:

Document quality expectations and validation practices.

Documents:

```text
test-strategy.md
security-test-checklist.md
performance-baseline.md
```

Typical Content:

* Testing approach
* Security validation areas
* Performance expectations
* Quality gates

---

# Operations Domain

Location:

```text
docs/operations/
```

Purpose:

Preserve operational knowledge.

Documents:

```text
deployment.md
rollback.md
runbook.md
troubleshooting.md
monitoring.md
disaster-recovery.md
```

Typical Content:

* Deployment procedures
* Recovery procedures
* Monitoring practices
* Incident handling
* Operational troubleshooting

---

# Templates

Location:

```text
docs/templates/
```

Purpose:

Provide reusable document templates.

Templates ensure:

* Consistent formatting
* Consistent terminology
* Easier AI generation
* Easier onboarding

Templates should be used when creating:

* ADR documents
* Technical debt documents
* Change documents

---

# Documentation Ownership

Documentation ownership follows code ownership.

Teams are responsible for maintaining documentation accuracy.

Documentation should be updated as part of the same change whenever practical.

---

# Documentation Quality Principles

Good documentation:

* Explains intent
* Explains decisions
* Explains constraints
* Explains trade-offs
* Is easy to navigate
* Is easy to maintain

Poor documentation:

* Duplicates source code
* Contains obsolete information
* Scatters related knowledge
* Requires excessive maintenance
* Stores implementation details better expressed in code

---

# Documentation Retention

Historical knowledge should be preserved whenever possible.

Prefer:

```text
Update
Archive
Deprecate
```

Avoid deleting documentation that contains architectural, operational, or historical context.

---

# Guiding Principle

Documentation is a knowledge system.

Its purpose is not to describe every line of code.

Its purpose is to preserve business context, architectural intent, technical debt analysis, operational knowledge, and historical decisions so that engineers and AI agents can safely understand, maintain, and evolve the system.