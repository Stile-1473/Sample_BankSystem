
# Goft Bank Project Notes

This document explains the architecture, components, data model, security patterns and user flows of the Goft Bank PHP MVC application. It is designed to help you understand, explain and extend the system


1. Overview and Architecture
- Purpose: A lightweight banking application with user roles (admin, manager, cashier, customer), account approvals, transactions (deposit/withdrawal/transfer) and audit logging
- Stack: PHP (vanilla), MySQL (PDO), Apache/XAMPP, MVC pattern
- Entry point: public/index.php
- Routing: index.php?url=Controller/method/param1/param2. The router is in app/core/App.php
- Used mvc structure
- MVC structure:
  - Core: app/core (App, Controller, Database)
  - Controllers: app/controllers
  - Models: app/models (PDO-based)
  - Views: app/views (feature folders)
  - Assets: assets/css, assets/js, assets/img


2. Core Components

2.1 App.php (Router and Error Handling)
- Parses the url query param into controller/method/params
- Loads the appropriate controller class and calss the method with parameters
- Registers global exception and error handlers which render 500.php for fatal errors,403 for forbidden access and 404 for page not found
- Default route: HomeController@index when url  is absent

2.2 Controller.php (Base Controller)

- Helpers:help to load other classes

  - model($name): loads and returns an instance of app/models/$name.php

  - view($path, $data = []): includes a view file to be displayed

  - error403(): sends HTTP 403 and renders app/views/errors/403.php

2.3 Database.php (PDO Factory)

- Creates a PDO connection to MySQL with ERRMODE_EXCEPTION

- Credentials set inside the file so you can connect to db


3. Controllers and Their Responsibilities

3.1 HomeController

- index(): Renders the homepage view app/views/home.php (landing entry point)

3.2 AuthController (Authentication)

- login($error = null): Shows login form

- register($error = null): Shows registration form

- handleLogin(): Validates credentials. Uses FailedLogin model for brute-force attacks; on success stores user in session and logs login_success in audit.

- handleRegister(): Creates a user (role selectable), logs registration in audit. Basic duplicate-username and validation checks.

- logout(): Destroys session and redirects to public index

- forgotPassword(): Renders identity verification form (username + phone)

- handleForgotPassword(): Verifies username+phone, then shows reset form.

- resetPassword(): Optional GET route to display reset form if session username is present

- handleResetPassword(): Updates the user password and shows the login view with success

3.3 DashboardController

- index(): Auth-protected. Chooses a role-specific dashboard view (admin, manager, cashier, customer) according to the logged role of the user

3.4 ProfileController (Customer profile)

- editProfile(): Shows editable profile (email/phone)

- updateProfile(): Validates and stores profile updates

- changePassword(): displays change password form

- updatePassword(): Validates and updates password

3.5 AccountController (Admin/Manager/Cashier/Customer functions)

- Admin/Manager:

  - pending(): Lists accounts with status=pending for approval 

  - approve($id): Sets status=active; logs account_approved

  - reject($id): Sets status='closed'; logs account_rejected

  - all(): Admin & Manager only; lists all accounts across users

  - users(): Admin/Manager user listing 

  - block($id)/unlock($id)/delete($id): Manage users via status flags; logs each action

  - changeRole($id): Changes a user role with and logs role_changed

- Customer:

  - myBalances(): Lists active accounts for the current user

  - request(): Form to request a new account (creates pending account on submit)

  - submitRequest(): Creates pending account and sets a flash message; logs account_requested

- Cashier:

  - customerAccounts(): Lists all accounts with owners to select and process

  - process(): Displays a form to process transactions for any account 
  
  

3.6 TransactionController

- create(): Shows transfer/deposit/withdrawal form

- store(): Validates and processes transactions via Transaction model:

  - Validates type, amount and account existence/state

  - Customers must own the source account; cashiers/managers/admins do not

  - Deposits/withdrawals update balances atomically; transfers debit/credit with linkage

  - Logs transaction_create in audit; returns success/failure popup

- edit($id), update($id), delete($id)

- all(): Admin/Manager-only list of all transactions. View includes Approve/Reject for pending

- approve($id), reject($id): Manager/Admin approve or reject pending; approve applies financial effect and sets completed; reject marks failed

- exportcsv(): Streams CSV for a specific account's transactions

- status(): JSON endpoint returning filtered transactions for an account 

3.7 AuditLogController

- index(): Admin-only list of audit logs

- exportcsv(): Admin export of audit logs


4. Models and Key Methods

4.1 User (app/models/User.php)

- __construct(): obtains PDO connection from Database

- getPaginated($limit, $offset): lists users excluding status='deleted'

- countAll(): total count excluding deleted

- changeRole($id, $role), block($id), unlock($id), delete($id=soft), isLocked($id)

- findByUsername($username), findByUsernameAndPhone($username, $phone)

- create($username, $password, $role, $email, $phone): hashed password

- updateProfile($id, $email, $phone)

- updatePassword($id, $newPassword)

4.2 Account (app/models/Account.php)

- getPending(): pending accounts with owner username

- approve($id): set status='active'

- getByUser($user_id), getActiveByUser($user_id)

- createPending($user_id, $initialBalance=0.0)

- getAllWithUsers(): list all accounts with usernames

- exists($id), isActive($id): validation helpers


4.3 Transaction (app/models/Transaction.php)

- create($account_id, $type, $amount)

- createWithProcessing($accountId, $type, $amount, $toAccountId, $status): main entrypoint used by controller

  - If status='completed': performs deposit/withdrawal/transfer with atomic balance updates

  - If status='pending': inserts a pending row; balances unaffected until approval

- updateStatus($id, $status): if pending→completed, applies financial effect and updates status

- delete($id), getByAccount($account_id, $type, $status), get($id), getAll()

- belongsToUser($txId, $userId): join with accounts to verify owner

- accountBelongsToUser($accountId, $userId)

- Internal helpers

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

- Design in assets/css/neumorphism.css

- Remix Icons used across dashboards and forms

- Role dashboards (app/views/dashboard/*.php): common sidebar with active states and links

- Notable pages

  - Auth: login, register, forgotPassword, resetPassword 

  - Account approvals: pending.php (Approve/Reject) and all.php (admin overview)

  - Transactions: create.php , all.php (admin list with Approve/Reject for pending)

  - Customer: myBalances.php, request.php

  - Cashier: customerAccounts.php and process.php

  - Errors: 403/404/500 
- Notifications: assets/js/popup.js provides toasts. Dashboards show flash notifications 

6. Routing and URLs 

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

-  goft_schema.sql 
  - users: id, username, password, email, phone, role, status

  - accounts: id, user_id (FK→users), balance, status

  - transactions: id, account_id (FK→accounts), to_account_id (FK→accounts), pair_id (FK→transactions), type, amount, status, timestamps

  - audit_logs: id, user_id (FK→users), action, details, created_at

  - failed_logins: username, ip_address, attempts, last_attempt

- Important constraints:

  - Foreign keys ensure referential integrity for accounts and transactions.



8. Security, Validation, and Authorization

- Sessions: session_start() called in protected methods

- Roles: admin, manager, cashier, customer. Critical actions check roles permisssion

- Ownership checks: customers can only act on their own accounts/transactions.

- Input validation:

  - Validating transaction type; requiring to_account_id for transfers

  - Account existence and status checks before processing

- Passwords: hashed using password_hash; verification with password_verify

- Brute force mitigation: FailedLogin attempts enforced in AuthController


9. Transaction Processing Semantics

- Deposits/Withdrawals: immediate balance changes when status='completed'


- Threshold policy: approval required for cashier transactions ≥ 5000  from managers
and for all customer-submitted transactions.


10. User Flows

10.1 Registration and Login

- User registers with username/password/email/phone and selects role (default customer)

- Login writes the user  into session and redirects to the role dashboard

- Failed attempts tracked; too many attempts lock temporarily

10.2 Customer Account Lifecycle

- Customer requests an account (optional initial deposit) → account becomes pending

- Manager/Admin approves or rejects from Pending Accounts

- Active accounts are visible on View Balance

10.3 Cashier Processing

- Cashier can view all customer accounts and process deposits/withdrawals/transfers for any account

- Larger transactions require manager/admin approval (pending). Smaller ones complete immediately


10.4 Transactions Approval (Manager/Admin)

- View all transactions and approve/reject pending ones

- Approval applies the actual balance changes (for pending records) and marks completed

10.5 Auditing
- Sensitive actions (login success/failure, registration, role changes, user management transaction lifecycle) write records to audit_logs with  JSON details.



11. Deployment and Setup

- Import goft_schema.sql into MySQL

- Set DB credentials in app/core/Database.php

- Serve the site from public/; access at http://localhost/Group/public/index.php




14.  Roles and Permissions
- Admin: full access to users, accounts, transactions, and audit logs

- Manager: approves/rejects accounts and transactions; views transactions and users

- Cashier: processes transactions for any account; cannot manage roles

- Customer: owns accounts; can request accounts and perform transactions only on owned, active accounts


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

Mufunde zvakanaka
