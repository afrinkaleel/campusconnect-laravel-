<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body{
            background:#f0f2f5;
            font-family:'Segoe UI',sans-serif;
            margin:0;
        }

        .navbar{
            background:#1a73e8;
            padding:0 24px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            height:56px;
            box-shadow:0 2px 6px rgba(0,0,0,.2);
        }

        .navbar .brand{
            color:white;
            font-size:20px;
            font-weight:700;
            text-decoration:none;
        }

        .nav-links{
            display:flex;
            align-items:center;
            gap:8px;
        }

        .nav-links a{
            color:white;
            font-size:13px;
            padding:6px 12px;
            border-radius:6px;
            text-decoration:none;
            transition:background .2s;
        }

        .nav-links a:hover{
            background:rgba(255,255,255,.15);
        }

        .container{
            max-width:1100px;
            margin:30px auto;
            padding:0 20px;
        }

        .card{
            background:white;
            border-radius:10px;
            padding:25px;
            margin-bottom:20px;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        .card h2{
            color:#1a73e8;
            font-size:18px;
            margin-bottom:15px;
        }

        .stats-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
            gap:16px;
            margin-bottom:20px;
        }

        .stat-card{
            background:white;
            border-radius:10px;
            padding:20px;
            text-align:center;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
            border-left:4px solid #1a73e8;
        }

        .stat-card .number{
            font-size:32px;
            font-weight:700;
            color:#1a73e8;
        }

        .stat-card .label{
            font-size:13px;
            color:#666;
            margin-top:4px;
        }

        .btn{
            padding:9px 20px;
            border:none;
            border-radius:6px;
            font-size:14px;
            cursor:pointer;
            text-decoration:none;
            display:inline-block;
            transition:opacity .2s;
        }

        .btn:hover{
            opacity:.85;
        }

        .btn-primary{
            background:#1a73e8;
            color:white;
        }

        .btn-success{
            background:#34a853;
            color:white;
        }

        .btn-warning{
            background:#fbbc04;
            color:#333;
        }

        .btn-danger{
            background:#ea4335;
            color:white;
        }

        table{
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        }

        table th{
            background:#1a73e8;
            color:white;
            padding:11px 12px;
            text-align:left;
        }

        table td{
            padding:10px 12px;
            border-bottom:1px solid #eee;
        }

        table tr:hover{
            background:#f9f9f9;
        }

        .badge{
            padding:3px 10px;
            border-radius:12px;
            font-size:12px;
            font-weight:600;
        }

        .badge-pending{background:#fff3cd;color:#856404;}
        .badge-approved{background:#d4edda;color:#155724;}
        .badge-rejected{background:#f8d7da;color:#721c24;}
        .badge-planning{background:#e2e3e5;color:#383d41;}
        .badge-design{background:#cce5ff;color:#004085;}
        .badge-implementation{background:#fff3cd;color:#856404;}
        .badge-testing{background:#d4edda;color:#155724;}
        .badge-completed{background:#d1ecf1;color:#0c5460;}

        .alert{
            padding:12px 16px;
            border-radius:6px;
            margin-bottom:16px;
            font-size:14px;
        }

        .alert-success{
            background:#d4edda;
            color:#155724;
            border:1px solid #c3e6cb;
        }

        .alert-error{
            background:#f8d7da;
            color:#721c24;
            border:1px solid #f5c6cb;
        }

        .form-group{
            margin-bottom:16px;
        }

        .form-group label{
            display:block;
            font-weight:600;
            font-size:13px;
            color:#444;
            margin-bottom:5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea{
            width:100%;
            padding:9px 12px;
            border:1px solid #ddd;
            border-radius:6px;
            font-size:14px;
            box-sizing:border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus{
            outline:none;
            border-color:#1a73e8;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <a href="/" class="brand">🎓 CampusConnect</a>

    <div class="nav-links">

        @auth

            @php
                $unread = \App\Models\Notification::where('user_id', auth()->id())
                            ->where('is_read', 0)->count();
            @endphp

            @if(auth()->user()->user_type === 'student')

                <a href="{{ route('dashboard.student') }}">🏠 Dashboard</a>
                <a href="{{ route('projects.index') }}">📁 Projects</a>
                <a href="{{ route('resources.bookForm') }}">🔧 Book Resource</a>
                <a href="{{ route('resources.myBookings') }}">📋 My Bookings</a>
                <a href="{{ route('resources.calendar') }}">📅 Resource Cal</a>
                <a href="{{ route('notifications.index') }}">
                    🔔 @if($unread > 0)
                        <span style="background:#ea4335;color:white;border-radius:10px;
                            padding:1px 6px;font-size:10px;">{{ $unread }}</span>
                    @endif
                </a>

            @elseif(auth()->user()->user_type === 'lecturer')

                <a href="{{ route('dashboard.lecturer') }}">🏠 Dashboard</a>
                <a href="{{ route('projects.supervise') }}">📋 Projects</a>
                <a href="{{ route('projects.unassigned') }}">🔍 Find Projects</a>
                <a href="{{ route('resources.bookForm') }}">🔧 Book Resource</a>
                <a href="{{ route('resources.myBookings') }}">📋 Bookings</a>
                <a href="{{ route('leave.applyForm') }}">📝 Apply Leave</a>
                <a href="{{ route('leave.myLeaves') }}">My Leaves</a>
                <a href="{{ route('leave.calendar') }}">📅 Leave Cal</a>
                <a href="{{ route('notifications.index') }}">
                    🔔 @if($unread > 0)
                        <span style="background:#ea4335;color:white;border-radius:10px;
                            padding:1px 6px;font-size:10px;">{{ $unread }}</span>
                    @endif
                </a>

            @elseif(auth()->user()->user_type === 'hod')

                <a href="{{ route('dashboard.hod') }}">🏠 Dashboard</a>
                <a href="{{ route('projects.all') }}">📁 Projects</a>
                <a href="{{ route('projects.supervisionRequests') }}">👨‍🏫 Supervision</a>
                <a href="{{ route('resources.manage') }}">🔧 Resources</a>
                <a href="{{ route('resources.manageBookings') }}">✅ Bookings</a>
                <a href="{{ route('leave.manage') }}">📝 Leave</a>
                <a href="{{ route('leave.calendar') }}">📅 Leave Cal</a>
                <a href="{{ route('resources.calendar') }}">📅 Resource Cal</a>
                <a href="{{ route('reports.index') }}">📊 Reports</a>
                <a href="{{ route('notifications.index') }}">
                    🔔 @if($unread > 0)
                        <span style="background:#ea4335;color:white;border-radius:10px;
                            padding:1px 6px;font-size:10px;">{{ $unread }}</span>
                    @endif
                </a>

            @endif

            <form method="POST" action="/logout" style="display:inline;">
                @csrf
                <button
                    type="submit"
                    class="btn"
                    style="background:rgba(255,255,255,0.15);color:white;font-size:13px;padding:6px 12px;">
                    Logout ({{ auth()->user()->name }})
                </button>
            </form>

        @endauth

    </div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>