<!-- Creator: ghost1473 -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Goft Bank — Welcome</title>
  <link rel="stylesheet" href="/Group/assets/css/bootstrap-offline-docs-5.1/bootstrap-offline-docs-5.1/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/Group/assets/css/remix%20icons/remixicon.css" />
  <link rel="stylesheet" href="/Group/assets/css/neumorphism.css" />
  <script src="/Group/assets/js/popup.js" defer></script>
</head>

<body>
  
<!-- Navigation -->
  <header style="border-bottom:1px solid var(--border);">
    

    <div class="container align-items-center d-flex justify-content-between p-4" >

      <div>

        <span class="h2 text-secondary ">Goft Bank</span>
        
      </div>

      <nav class="d-flex align-items-center gap-3">

        <a href="index.php?url=auth/login" class="btn text-decoration-none">
          <i class="ri-login-circle-line"></i> 
          Login
        </a>
        
        <a href="index.php?url=auth/register" class="btn text-decoration-none ">
          <i class="ri-user-add-line"></i>
           Register
          </a>

      </nav>
    </div>
  </header>


  <!-- Hero section -->
  <section class="container hero mb-5 mt-5" >

    <div class="row align-items-center g-4">

      <div class="col-lg-7">
        <h1 class="h2 mb-3 text-secondary">Banking made clear, secure, and fast</h1>

        <p class="lead ">
          Goft Bank helps you manage accounts, process transactions, 
          and keep compliant with complete audit trails.
           Enjoy role-based dashboards for admins, managers, cashiers, and customers.
          </p>

        <div class="d-flex flex-wrap mt-6 gap-3 mb-lg-4">
          <a href="index.php?url=auth/login" class="btn btn-outline-secondary text-decoration-none "><i class="ri-login-box-line"></i> Get Started</a>
          <a href="index.php?url=auth/register" class="btn btn-success text-decoration-none"><i class="ri-user-smile-line"></i> Create Account</a>
        </div>
        <div style="display:flex; gap:1rem; margin-top:1.25rem; color:var(--muted);">
          <span class="d-flex align-items-center gap-1"><i class="ri-shield-check-line"></i> Secure</span>
          <span  class="d-flex align-items-center gap-1"><i class="ri-flashlight-line"></i> Fast Onboarding</span>
          <span  class="d-flex align-items-center gap-1" ><i class="ri-history-line"></i> Full Audit</span>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card " style="text-align:left;">
          <h3 class="text-info">Quick Links</h3>
          <ul class="list-group navbar align-items-start list-unstyled">
            <li class=""><a class="cta  text-decoration-none text-secondary" href="index.php?url=account/request"><i class="ri-open-arm-line nav-link text-secondary"></i> Request an Account</a></li>
            <li><a class="cta text-decoration-none text-secondary" href="index.php?url=account/myBalances"><i class="ri-wallet-3-line nav-link text-secondary"></i> View My Balance</a></li>
            <li><a class="cta  text-decoration-none text-secondary" href="index.php?url=transaction/create"><i class="ri-send-plane-2-line nav-link text-secondary"></i> Transfer Money</a></li>
            <li><a class="cta  text-decoration-none text-secondary" href="index.php?url=auditlog/index " ><i class="ri-file-list-3-line nav-link text-secondary"></i> Audit Logs (Admin)</a></li>
          </ul>
        </div>
      </div>
    </div>
  </section>



  <!-- Features grid -->
  <section class="container mb-5 mt-4" style="padding: 1rem 0 2rem;">
    <div class="row g-3">
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <div class="d-flex align-items-center gap-4">
            <i class="ri-secure-payment-line" style="font-size:1.4rem;"></i>
            <h5 class="text-secondary m-0">Secure Transactions</h5>
          </div>
          <p class="lead text-secondary">Atomic balance updates and approvals for large payments.
          </p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <div class="d-flex align-items-center gap-4">
            <i class="ri-dashboard-2-line" style="font-size:1.4rem;"></i>
            <h5 class="text-secondary m-0">Role Dashboards</h5>
          </div>
          <p class="lead text-secondary">Admins, managers, cashiers, and customers get tailored views.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <div class="d-flex align-items-center gap-4">
            <i class="ri-notification-3-line" style="font-size:1.4rem;"></i>
            <h5 class="text-secondary m-0">Smart Notifications</h5>
          </div>
          <p class="lead text-secondary">Clean toasts and flash messages guide each step after the other.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <div class="d-flex align-items-center gap-4">
            <i class="ri-shield-user-line" style="font-size:1.4rem;"></i>
            <h5 class="text-secondary m-0">Audit & Compliance</h5>
          </div>
          <p class="lead text-secondary">Every sensitive action is recorded with user and timestamp.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- How it works -->
  <section class="container" style="padding: 1rem 0 3rem;">
    <h3 class="text-secondary ">How it works</h3>
    <div class="row g-3">
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <strong class="text-secondary">1. Register</strong>
          <p class="lead">Create your account and choose your role.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <strong class="text-secondary">2. Request</strong>
          <p class="lead">Customers request an account; managers approve.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <strong class="text-secondary">3. Transact</strong>
          <p class="lead">Deposit, withdraw, and transfer securely.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card">
          <strong class="text-secondary">4. Track</strong>
          <p class="lead">View balances and audit logs any time.</p>
        </div>
      </div>
    </div>
    <div class="d-flex gap-4 align-items-center">
      <a href="index.php?url=auth/register" class="btn btn-outline-primary text-decoration-none"><i class="ri-rocket-2-line"></i> Get Started Free</a>
      <a href="index.php?url=auth/login" class="btn btn-outline-secondary  text-decoration-none"><i class="ri-login-box-line"></i> I already have an account</a>
    </div>
  </section>

  <!-- Footer -->
  <footer style="border-top:1px solid var(--border);">
    <div class="container " style="padding:1rem 0; display:flex; justify-content:space-between; align-items:center; color:var(--muted);">
      <span>&copy; <?php echo date('Y'); ?> Goft Bank<br><small>Signature: Ghost1473 — ZeroDaySolutions</small></span>
      <div style="display:flex; gap:1rem;">
        <a class="link-secondary" href="#">Privacy</a>
        <a class="link-secondary" href="#">Terms</a>
        <a class="link-secondary" href="index.php?url=auth/login">Login</a>
      </div>
    </div>
  </footer>

</body>

</html>