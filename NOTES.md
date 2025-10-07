
# Goft Bank Project Notes

This document explains the architecture, components, data model, security patterns, and user flows of the Goft Bank PHP MVC application. It is designed to help you understand, explain, and extend the system.


1. Overview and Architecture
- Purpose: A lightweight banking application with user roles (admin, manager, cashier, customer), account approvals, transactions (deposit/withdrawal/transfer), and audit logging.
- Stack: PHP (vanilla), MySQL (PDO), Apache/XAMPP, MVC pattern.
- Entry point: public/index.php
- Routing: index.php?url=Controller/method/param1/param2. The router lives in app/core/App.php.
- MVC structure:
  - Core: app/core (App, Controller, Database)
  - Controllers: app/controllers
  - Models: app/models (PDO-based)
  - Views: app/views (feature folders)
  - Assets: assets/css, assets/js, assets/img


2. Core Components

2.1 App.php (Router and Error Handling)
- Parses the url query param into controller/method/params.
- Loads the appropriate controller class and invokes the method with parameters.
- Registers global exception and error handlers which render 500.php for fatal errors.
- Default route: HomeController@index when url is absent.

2.2 Controller.php (Base Controller)
- Helpers:
  - model($name): loads and returns an instance of app/models/$name.php.
  - view($path, $data = []): includes a view file.
  - error403(): sends HTTP 403 and renders app/views/errors/403.php, then exits.

2.3 Database.php (PDO Factory)
- Creates a PDO connection to MySQL with ERRMODE_EXCEPTION.
- Credentials set inside the file (can be externalized later to env variables).


3. Controllers and Their Responsibilities
3.1 HomeController
- index(): Renders the homepage view app/views/home.php (landing entry point).

3.2 AuthController (Authentication)
- login($error = null): Shows login form.
- register($error = null): Shows registration form.
- handleLogin(): Validates credentials. Uses FailedLogin model for brute-force mitigation; on success stores user in session and logs login_success in audit.
- handleRegister(): Creates a user (role selectable), logs registration in audit. Basic duplicate-username and validation checks.
- logout(): Destroys session and redirects to public index.
- forgotPassword(): Renders identity verification form (username + phone).
- handleForgotPassword(): Verifies username+phone, then shows reset form.
- resetPassword(): Optional GET route to display reset form if session username is present.
- handleResetPassword(): Updates the user password and shows the login view with success.

3.3 DashboardController
- index(): Auth-protected. Chooses a role-specific dashboard view (admin, manager, cashier, customer).

3.4 ProfileController (Customer profile)
- editProfile(): Shows editable profile (email/phone).
- updateProfile(): Validates and stores profile updates.
- changePassword(): Renders change password form.
- updatePassword(): Validates and updates password (post-login change).

3.5 AccountController (Admin/Manager/Cashier/Customer functions)
- Admin/Manager:
  - pending(): Lists accounts with status='pending' for approval workflow.
  - approve($id): Sets status='active'; logs account_approved.
  - reject($id): Sets status='closed'; logs account_rejected.
  - all(): Admin-only; lists all accounts across users.
  - users(): Admin/Manager user listing and pagination.
  - block($id)/unlock($id)/delete($id): Manage users via status flags; logs each action.
  - changeRole($id): Changes a user's role with validation; logs role_changed.
- Customer:
  - myBalances(): Lists active accounts for the current user.
  - request(): Form to request a new account (creates pending account on submit).
  - submitRequest(): Creates pending account and sets a flash message; logs account_requested.
- Cashier:
  - customerAccounts(): Lists all accounts with owners to select and process.
  - process(): Displays a form to process transactions for any account (posts to TransactionController@store).

3.6 TransactionController
- create(): Shows transfer/deposit/withdrawal form. For customers, loads their active accounts to prefill.
- store(): Validates and processes transactions via Transaction model:
  - Validates type, amount, and account existence/state.
  - Customers must own the source account; cashiers/managers/admins do not.
  - Threshold logic: customers always pending; cashier >= 5000 pending; else completed.
  - Deposits/withdrawals update balances atomically; transfers debit/credit with linkage.
  - Logs transaction_create in audit; returns success/failure popup.
- edit($id), update($id), delete($id): AuthZ checks (owner or admin/manager). Update supports status change.
- all(): Admin/Manager-only list of all transactions. View includes Approve/Reject for pending.
- approve($id), reject($id): Manager/Admin approve or reject pending; approve applies financial effect and sets completed; reject marks failed.
- exportcsv(): Streams CSV for a specific account's transactions.
- status(): JSON endpoint returning filtered transactions for an account (used for polling).

3.7 AuditLogController
- index(): Admin-only list of audit logs.
- exportcsv(): Admin export of audit logs.


4. Models and Key Methods
4.1 User (app/models/User.php)
- __construct(): obtains PDO connection from Database.
- getPaginated($limit, $offset): lists users excluding status='deleted'.
- countAll(): total count excluding deleted.
- changeRole($id, $role), block($id), unlock($id), delete($id=soft), isLocked($id).
- findByUsername($username), findByUsernameAndPhone($username, $phone).
- create($username, $password, $role, $email, $phone): hashed password.
- updateProfile($id, $email, $phone), updatePassword($id, $newPassword).

4.2 Account (app/models/Account.php)
- getPending(): pending accounts with owner username.
- approve($id): set status='active'.
- getByUser($user_id), getActiveByUser($user_id).
- createPending($user_id, $initialBalance=0.0).
- getAllWithUsers(): list all accounts with usernames.
- exists($id), isActive($id): validation helpers.

4.3 Transaction (app/models/Transaction.php)
- create($account_id, $type, $amount): legacy insert (kept for compat).
- createWithProcessing($accountId, $type, $amount, $toAccountId, $status): main entrypoint used by controller.
  - If status='completed': performs deposit/withdrawal/transfer with atomic balance updates.
  - If status='pending': inserts a pending row; balances unaffected until approval.
- updateStatus($id, $status): if pending→completed, applies financial effect and updates status.
- delete($id), getByAccount($account_id, $type, $status), get($id), getAll().
- belongsToUser($txId, $userId): join with accounts to verify owner.
- accountBelongsToUser($accountId, $userId).
- Internal helpers (atomic):
  - processDeposit($accountId, $amount, $existingTx=null)
  - processWithdrawal($accountId, $amount, $existingTx=null)
  - processTransfer($fromAccountId, $toAccountId, $amount, $existingTx=null)
  - getBalance($accountId), adjustBalance($accountId, $delta)

4.4 AuditLog (app/models/AuditLog.php)
- log($user_id, $action, $details)
- getAll() joined with username

4.5 FailedLogin (app/models/FailedLogin.php)
- record($username, $ip), getAttempts($username, $ip), reset($username, $ip)


5. Views and UI
- Consistent white theme in assets/css/neumorphism.css.
- Remix Icons used across dashboards and forms.
- Role dashboards (app/views/dashboard/*.php): common sidebar with active states and links.
- Notable pages:
  - Auth: login, register, forgotPassword, resetPassword redesigned for clarity.
  - Account approvals: pending.php (Approve/Reject) and all.php (admin overview).
  - Transactions: create.php (dynamic destination account for transfers), all.php (admin list with Approve/Reject for pending).
  - Customer: myBalances.php, request.php.
  - Cashier: customerAccounts.php and process.php.
  - Errors: 403/404/500 redesigned with consistent theme.
- Notifications: assets/js/popup.js provides toasts. Dashboards show flash notifications injected server-side.


6. Routing and URLs (examples)
- Login: index.php?url=auth/login → POST to auth/handleLogin
- Register: index.php?url=auth/register → POST to auth/handleRegister
- Forgot Password: index.php?url=auth/forgotPassword → POST to auth/handleForgotPassword
- Reset Password: index.php?url=auth/resetPassword → POST to auth/handleResetPassword
- Customer request account: index.php?url=account/request → POST to account/submitRequest
- Manager pending approvals: index.php?url=account/pending
- Manager/Admin approve account: index.php?url=account/approve/{id}
- Manager/Admin reject account: index.php?url=account/reject/{id}
- Customer balances: index.php?url=account/myBalances
- Transaction create: index.php?url=transaction/create → POST to transaction/store
- Admin/Manager transactions list: index.php?url=transaction/all
- Approve pending transaction: index.php?url=transaction/approve/{id}
- Reject pending transaction: index.php?url=transaction/reject/{id}
- Audit logs (admin): index.php?url=auditlog/index


7. Data Model and Schema (MySQL)
- See goft_schema.sql for full schema; highlights:
  - users: id, username, password, email, phone, role, status
  - accounts: id, user_id (FK→users), balance, status
  - transactions: id, account_id (FK→accounts), to_account_id (FK→accounts), pair_id (FK→transactions), type, amount, status, timestamps
  - audit_logs: id, user_id (FK→users), action, details, created_at
  - failed_logins: username, ip_address, attempts, last_attempt
- Important constraints:
  - Foreign keys ensure referential integrity for accounts and transactions.
  - Indexes on status/type/user improve filtering performance.


8. Security, Validation, and Authorization
- Sessions: session_start() invoked in protected actions.
- Roles: admin, manager, cashier, customer. Critical actions check role allowlists.
- Ownership checks: customers can only act on their own accounts/transactions.
- Input validation:
  - Casting IDs to int; amount to float.
  - Validating transaction type; requiring to_account_id for transfers.
  - Account existence and status checks before processing.
- Passwords: hashed using password_hash; verification with password_verify.
- Brute force mitigation: FailedLogin attempts enforced in AuthController.


9. Transaction Processing Semantics
- Deposits/Withdrawals: immediate balance changes when status='completed'; otherwise insert pending.
- Transfers: debit source and credit destination atomically; a paired row links the two sides (pair_id). For pending→approved, the effect is applied at approval time.
- Threshold policy: approval required for cashier transactions ≥ 5000 and for all customer-submitted transactions.


10. User Flows
10.1 Registration and Login
- User registers with username/password/email/phone and selects role (default customer).
- Login writes the user object into session and redirects to the role dashboard.
- Failed attempts tracked; too many attempts lock temporarily.

10.2 Customer Account Lifecycle
- Customer requests an account (optional initial deposit) → account becomes pending.
- Manager/Admin approves or rejects from Pending Accounts.
- Active accounts are visible on View Balance; customers can initiate transactions on their own active accounts.

10.3 Cashier Processing
- Cashier can view all customer accounts and process deposits/withdrawals/transfers for any account.
- Larger transactions require manager/admin approval (pending). Smaller ones complete immediately.

10.4 Transactions Approval (Manager/Admin)
- View all transactions and approve/reject pending ones.
- Approval applies the actual balance changes (for pending records) and marks completed.

10.5 Auditing
- Sensitive actions (login success/failure, registration, role changes, user management, transaction lifecycle) write records to audit_logs with optional JSON details.


11. Extending the System
- Add a Controller:
  - Create app/controllers/FooController.php, extend Controller, define methods.
  - Access via index.php?url=foo/method.
- Add a Model:
  - Create app/models/Bar.php, use (new Database())->getConnection(), write PDO queries with prepared statements.
- Add a View:
  - Create app/views/<feature>/<file>.php and include it from a controller action.
- Add a Route:
  - No separate routing table; routes are convention-based via the url query param.


12. Deployment and Setup
- Import goft_schema.sql into MySQL.
- Set DB credentials in app/core/Database.php.
- Serve the site from public/; access at http://localhost/Group/public/index.php.
- Optional: convert to Composer autoloading, environment config, and add a migration framework for production.


13. Known Limitations and Next Steps
- CSRF tokens and stricter session hardening are recommended for production.
- Convert echoed JS control flow to PRG (redirect + flash message) consistently.
- Migrate dynamic features to a service layer for better testability and reuse.
- Add tests (PHPUnit) for auth, account approvals, transaction flows, and approvals.
- Add filters/pagination to transaction and audit log admin pages, and global search.


14. Glossary of Roles and Permissions
- Admin: full access to users, accounts, transactions, and audit logs.
- Manager: approves/rejects accounts and transactions; views transactions and users.
- Cashier: processes transactions for any account; cannot manage roles.
- Customer: owns accounts; can request accounts and perform transactions only on owned, active accounts.


15. Quick Reference of Key Files
- public/index.php: front controller bootstrap
- app/core/App.php: router + error handlers
- app/core/Controller.php: base controller helper
- app/core/Database.php: PDO factory
- app/controllers/*.php: feature controllers
- app/models/*.php: PDO models (User, Account, Transaction, AuditLog, FailedLogin)
- app/views/**: views per feature and error pages
- assets/css/neumorphism.css: modern white theme
- assets/js/popup.js: simple toast notifications
- goft_schema.sql: canonical database schema


End of document.
