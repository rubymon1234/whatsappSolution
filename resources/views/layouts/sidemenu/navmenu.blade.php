<nav class="hk-nav hk-nav-light">
    <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><span class="feather-icon"><i data-feather="x"></i></span></a>
    <div class="nicescroll-bar">
        <div class="navbar-nav-wrap">
            <div class="nav-header">
                <span>Navigation</span>
                <span>UI</span>
            </div>
            <ul class="navbar-nav flex-column">
            @permission(('admin.dashboard'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <span class="feather-icon">
                            <i data-feather="activity"></i>
                        </span>
                        <span class="nav-link-text"> Dashboard </span>
                    </a>
                </li>
            @endpermission
            @permission(('user.dashboard'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.dashboard') }}">
                        <span class="feather-icon">
                            <i data-feather="activity"></i>
                        </span>
                        <span class="nav-link-text"> Dashboard </span>
                    </a>
                </li>
            @endpermission
            @permission(('reseller.dashboard'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reseller.dashboard') }}">
                        <span class="feather-icon">
                            <i data-feather="activity"></i>
                        </span>
                        <span class="nav-link-text"> Dashboard </span>
                    </a>
                </li>
            @endpermission
            @permission(('acl.*'))
                <li class="nav-item {{ (Route::is('acl.*') ? 'menu-open' : '') }}">
                    <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#auth_drp1" aria-expanded="true">
                        <span class="feather-icon"><i data-feather="sliders"></i></span>
                        <span class="nav-link-text">Role Management</span>
                    </a>
                    <ul id="auth_drp1" class="nav flex-column collapse-level-1 {{ (Route::is('acl.*') ? 'show' : '') }} collapse ">
                        <li class="nav-item">
                            <ul class="nav flex-column">
                                @permission('acl.role.view')
                                <li class="nav-item {{ (Route::is('acl.role.view') ? 'active' : '' ) }}">
                                    <a class="nav-link" href="{{ route('acl.role.view') }}">List Role</a>
                                </li>
                                @endpermission
                                @permission('acl.perms.view')
                                    <li class="nav-item {{ (Route::is('acl.perms.view') ? 'active' : '' ) }}">
                                        <a class="nav-link" href="{{ route('acl.perms.view') }}">List Permission</a>
                                    </li>
                                @endpermission
                            </ul>
                        </li>
                    </ul>
                </li>
            @endpermission
            @permission(('admin.user.*'))
                <li class="nav-item {{ (Route::is('admin.user.*') ? 'menu-open' : '') }}">
                    <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#auth_drp" aria-expanded="true">
                        <span class="feather-icon"><i data-feather="users"></i></span>
                        <span class="nav-link-text">User Management</span>
                    </a>
                    <ul id="auth_drp" class="nav flex-column collapse-level-1 {{ (Route::is('admin.user.*') ? 'show' : '') }} collapse ">
                        <li class="nav-item">
                            <ul class="nav flex-column">
                                @permission('admin.user.view')
                                <li class="nav-item {{ (Route::is('admin.user.view') ? 'active' : '' ) }}">
                                    <a class="nav-link" href="{{ route('admin.user.view') }}">List Users</a>
                                </li>
                                @endpermission
                                
                            </ul>
                        </li>
                    </ul>
                </li>
            @endpermission
            @permission(('reseller.user.*'))
                <li class="nav-item {{ (Route::is('reseller.user.*') ? 'menu-open' : '') }}">
                    <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#auth_drp" aria-expanded="true">
                        <span class="feather-icon"><i data-feather="users"></i></span>
                        <span class="nav-link-text">User Management</span>
                    </a>
                    <ul id="auth_drp" class="nav flex-column collapse-level-1 {{ (Route::is('reseller.user.*') ? 'show' : '') }} collapse ">
                        <li class="nav-item">
                            <ul class="nav flex-column">
                                @permission('reseller.user.view')
                                <li class="nav-item {{ (Route::is('reseller.user.view') ? 'active' : '' ) }}">
                                    <a class="nav-link" href="{{ route('reseller.user.view') }}">List Users</a>
                                </li>
                                @endpermission
                            </ul>
                        </li>
                    </ul>
                </li>
            @endpermission
            @permission(('global.plan.view'))
                <li class="nav-item {{ (Route::is('global.plan.view') ? 'menu-open' : '') }}">
                    <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#auth_drp4" aria-expanded="true">
                        <span class="feather-icon"><i data-feather="users"></i></span>
                        <span class="nav-link-text">Plan Management</span>
                    </a>
                    <ul id="auth_drp4" class="nav flex-column collapse-level-1 {{ (Route::is('global.plan.view') ? 'show' : '') }} collapse ">
                        <li class="nav-item">
                            <ul class="nav flex-column">
                                @permission('global.plan.view')
                                <li class="nav-item {{ (Route::is('global.plan.view') ? 'active' : '' ) }}">
                                    <a class="nav-link" href="{{ route('global.plan.view') }}">List Plan</a>
                                </li>
                                @endpermission
                            </ul>
                        </li>
                    </ul>
                </li>
            @endpermission
            @permission(('global.reseller.plan.view'))
                <li class="nav-item {{ (Route::is('global.reseller.plan.view') ? 'menu-open' : '') }}">
                    <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#auth_drp4" aria-expanded="true">
                        <span class="feather-icon"><i data-feather="users"></i></span>
                        <span class="nav-link-text">Plan Management</span>
                    </a>
                    <ul id="auth_drp4" class="nav flex-column collapse-level-1 {{ (Route::is('global.reseller.plan.view') ? 'show' : '') }} collapse ">
                        <li class="nav-item">
                            <ul class="nav flex-column">
                                @permission('global.reseller.plan.view')
                                <li class="nav-item {{ (Route::is('global.reseller.plan.view') ? 'active' : '' ) }}">
                                    <a class="nav-link" href="{{ route('global.reseller.plan.view') }}">List Plan</a>
                                </li>
                                @endpermission
                            </ul>
                        </li>
                    </ul>
                </li>
            @endpermission
            @permission(('admin.recharge.*'))
                <li class="nav-item {{ (Route::is('admin.recharge.*') ? 'menu-open' : '') }}">
                    <a class="nav-link" href="javascript:void(0);" data-toggle="collapse" data-target="#auth_drp5" aria-expanded="true">
                        <span class="feather-icon"><i data-feather="credit-card"></i></span>
                        <span class="nav-link-text">Credit Management</span>
                    </a>
                    <ul id="auth_drp5" class="nav flex-column collapse-level-1 {{ (Route::is('admin.recharge.*') ? 'show' : '') }} collapse ">
                        <li class="nav-item">
                            <ul class="nav flex-column">
                                @permission('admin.recharge.request.view')
                                <li class="nav-item {{ (Route::is('admin.recharge.request.view') ? 'active' : '' ) }}">
                                    <a class="nav-link" href="{{ route('admin.recharge.request.view') }}">List Request</a>
                                </li>
                                @endpermission
                            </ul>
                        </li>
                    </ul>
                </li>
            @endpermission
            </ul>
        </div>
    </div>
</nav>
<div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>