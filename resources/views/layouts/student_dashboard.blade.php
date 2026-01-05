@props(['classes' => []])
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EvalSpark - Student Dashboard</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <!-- Material Symbols -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
   
  <style>
    :root {
      --primary-color: #0078bd;
      --bg-color: #F4F5F7;
      --card-bg: #FFFFFF;
      --text-primary: #172B4D;
      --text-secondary: #5E6C84;
      --border-color: #dfe1e6;
      --bs-primary: #0078bd; /* Override Bootstrap var */
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-color);
      color: var(--text-primary);
      height: 100vh;
      overflow: hidden;
    }

    /* Bootstrap Overrides */
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: #00629b; /* Darker shade */
      border-color: #00629b;
    }

    .text-primary, .text-primary-custom {
      color: var(--primary-color) !important;
    }


    /* Layout */
    .app-container {
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    .main-wrapper {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    /* Header */
    .header {
      height: 64px;
      background: var(--card-bg);
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
      z-index: 1000;
    }

    .search-container {
      flex: 1;
      max-width: 600px;
      margin: 0 2rem;
      position: relative;
    }

    .search-container .material-icons-outlined {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-size: 20px;
    }

    .search-input {
      width: 100%;
      padding: 0.5rem 1rem 0.5rem 2.5rem;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      background-color: var(--bg-color);
      font-size: 0.9rem;
    }

    .search-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(0, 120, 189, 0.2);
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: var(--card-bg);
      border-right: 1px solid var(--border-color);
      padding: 1.5rem 1rem;
      overflow-y: auto;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0.6rem 0.8rem;
      color: var(--text-primary);
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.2s;
      text-decoration: none;
      font-size: 0.9rem;
      margin-bottom: 4px;
    }

    .nav-link:hover {
      background-color: var(--bg-color);
      color: var(--primary-color);
    }

    .nav-link.active {
      background-color: rgba(0, 120, 189, 0.1);
      color: var(--primary-color);
    }

    .nav-link .material-icons-outlined {
      font-size: 20px;
    }

    .sidebar-section-title {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      color: var(--text-secondary);
      margin-top: 1.5rem;
      margin-bottom: 0.75rem;
      padding-left: 0.8rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Dashboard */
    .content-area {
      flex: 1;
      padding: 2rem;
      overflow-y: auto;
    }

    .group-card {
      height: 120px;
      border-radius: 12px;
      padding: 1rem;
      color: white;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      border: none;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      text-decoration: none;
    }

    .group-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .card-title {
      font-size: 1rem;
      font-weight: 700;
      margin: 0;
    }

    .card-subtitle {
      font-size: 0.75rem;
      opacity: 0.9;
    }

    .banner-card {
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
      cursor: pointer;
      transition: opacity 0.2s;
    }

    .banner-card:hover {
      opacity: 0.9;
    }

    .avatar-circle {
      width: 36px;
      height: 36px;
      background-color: #9c27b0;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.85rem;
    }

    .class-tag {
      width: 20px;
      height: 20px;
      border-radius: 4px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 10px;
      font-weight: bold;
      margin-right: 8px;
    }

    /* Floating Button */
    .fab {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: white;
      border: 1px solid var(--border-color);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-secondary);
      cursor: pointer;
      transition: all 0.2s;
    }

    .fab:hover {
      color: var(--primary-color);
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .logout-btn {
      color: var(--text-secondary);
      border: none;
      background: none;
      padding: 0.5rem;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.9rem;
      font-weight: 500;
    }

    .logout-btn:hover {
      background-color: rgba(220, 53, 69, 0.1);
      color: #dc3545;
    }

    /* Modal styling */
    .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }

    .modal-content .material-symbols-outlined {
        vertical-align: middle;
        font-size: 20px;
    }

    .x-small {
        font-size: 0.7rem;
    }

    .input-group-text {
        border-color: #dee2e6;
    }

    #joinCode.form-control:focus {
        border-color: var(--primary-color);
        box-shadow: none;
    }
  </style>
</head>
<body>
  <div class="app-container">
    <!-- Header -->
    <header class="header">
      <div class="d-flex align-items-center gap-2">
        <span class="material-symbols-outlined text-primary-custom" style="font-size: 32px; color: var(--primary-color)">school</span>

        <span class="fs-5 fw-bold" style="color: var(--text-primary)">EvalSpark</span>
        <span class="material-icons-outlined">notifications</span>
      </div>

      <div class="search-container">
        <span class="material-icons-outlined">search</span>
        <input type="text" id="searchInput" class="search-input" placeholder="Search groups, tasks, or classes...">
      </div>

      <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
           <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="avatar-circle overflow-hidden" 
                   style="{{ Auth::user()->avatar_url ? 'background-image: url(' . Auth::user()->avatar_url . '); background-size: cover; background-position: center;' : '' }}">
                   {{ Auth::user()->avatar_url ? '' : substr(Auth::user()->name, 0, 1) }}
              </div>
              <div class="d-none d-md-block text-start">
                <div class="fw-bold small" style="line-height: 1.2">{{ Auth::user()->name }}</div>
                <div class="text-muted extra-small" style="font-size: 0.7rem">Student</div>
              </div>
              <span class="material-icons-outlined text-muted" style="font-size: 18px">expand_more</span>
           </div>
           <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
             <li><a class="dropdown-item small fw-bold d-flex align-items-center gap-2" href="{{ route('profile.edit') }}"><span class="material-icons-outlined fs-6">person</span> Profile</a></li>
             <li><hr class="dropdown-divider"></li>
             <li>
                 <form method="POST" action="{{ route('logout') }}">
                     @csrf
                     <button type="submit" class="dropdown-item small fw-bold text-danger d-flex align-items-center gap-2">
                         <span class="material-icons-outlined fs-6">logout</span> Logout
                     </button>
                 </form>
             </li>
           </ul>
        </div>
      </div>
    </header>

    <div class="main-wrapper">
      <!-- Sidebar -->
      @unless(isset($hideSidebar) && $hideSidebar)
      <aside class="sidebar">
        <nav class="nav flex-column">
          <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <span class="material-icons-outlined">dashboard</span>
            Dashboard
          </a>
          <a href="{{ route('groups.create') }}" class="nav-link {{ request()->routeIs('groups.create') ? 'active' : '' }}">
            <span class="material-icons-outlined">add_box</span>
            Create Group
          </a>
          <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#joinModal">
            <span class="material-icons-outlined">group_add</span>
            Join Class
          </a>
          
          <a href="{{ route('groups.index') }}" class="nav-link {{ request()->routeIs('groups.*') && !request()->routeIs('groups.create') ? 'active' : '' }}">
             <span class="material-icons-outlined">groups</span>
             Groups
          </a>
          <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') && !request()->routeIs('classes.join') ? 'active' : '' }}">
            <span class="material-icons-outlined">class</span>
            My Classes
          </a>
          <div class="sidebar-section-title cursor-pointer" data-bs-toggle="collapse" data-bs-target="#studentClasses" aria-expanded="true">
            <span>Classes</span>
            <span class="material-icons-outlined transition-transform" style="font-size: 16px">expand_more</span>
          </div>
          
          <div class="collapse show" id="studentClasses">
            <!-- Dynamic Classes in Sidebar -->
            @if(isset($classes))
                @foreach($classes as $class)
                <a href="{{ route('classes.show', $class->id) }}" class="nav-link d-flex justify-content-between">
                    <div class="d-flex align-items-center text-primary">
                    {{ $class->name }}
                    </div>
                </a>
                @endforeach
            @endif
          </div>
          
          <style>
              .cursor-pointer { cursor: pointer; }
              .transition-transform { transition: transform 0.2s; }
              [aria-expanded="true"] .material-icons-outlined { transform: rotate(180deg); }
          </style>
          
        </nav>
      </aside>
      @endunless

      <!-- Main Content -->
      <main class="content-area">
        @yield('content')
      </main>
    </div>

    <!-- Help Button -->
    <div class="fab">
      <span class="material-icons-outlined">help_outline</span>
    </div>
  </div>

  <!-- Join Class Modal -->
  <div class="modal fade" id="joinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('classes.join') }}" method="POST">
        @csrf
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-primary fs-3">school</span>
                    Join New Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="text-muted small mb-4">Enter the unique code provided by your lecturer to enroll in the course.</p>
                <div class="mb-4">
                    <label class="form-label fw-bold small">Class Code</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <span class="material-symbols-outlined text-muted">key</span>
                        </span>
                        <input type="text" name="join_code" id="joinCode" class="form-control bg-light border-start-0 py-2 fs-6" placeholder="e.g. X7Y29A" required>
                    </div>
                    <div class="form-text x-small mt-2">Codes are usually 6 characters long.</div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow">Join Class</button>
            </div>
        </div>
        </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
