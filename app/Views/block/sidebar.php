<!-- Sidemenu -->
<div class="main-sidebar main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="<?= url('') ?>">
            <img src="<?= LOGO; ?>" class="header-brand-img desktop-logo" alt="logo">
            <img src="<?= LOGOICON; ?>" class="header-brand-img icon-logo" alt="logo">
            <img src="<?= LOGO; ?>" class="header-brand-img desktop-logo theme-logo" alt="logo">
            <img src="<?= LOGOICON; ?>" class="header-brand-img icon-logo theme-logo" alt="logo">
        </a>
    </div>
    <div class="main-sidebar-body">
    <ul class="nav">
            <li class="nav-label">Dashboard</li>
            <li class="nav-item ">
                <a class="nav-link" href="<?= url('Company/CreateCompany') ?>"><i class="fe fe-plus"></i><span class="sidemenu-label">New Company</span></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="<?= url('Company') ?>"><i class="fe fe-briefcase"></i><span class="sidemenu-label">Company</span></a>
            </li>
            <!-- <li class="nav-item ">
                <a class="nav-link" href="<?= url('Company/company_grp') ?>"><i class="fa fa-business-time"></i><span class="sidemenu-label">Company Group</span></a>
            </li> -->
            <li class="nav-item ">
                <a class="nav-link" href="<?= url('Company/bkup') ?>"><i class="fa fa-hdd"></i><span class="sidemenu-label">Back Up</span></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="<?= url('Company') ?>"><i class="fa fa-window-restore"></i><span class="sidemenu-label">Restore</span></a>
            </li>
            <?php
            //print_r(session('utype'));exit;
            if(session('utype') == 1)
            {
            ?>
            <li class="nav-item ">
                <a class="nav-link" href="<?= url('User') ?>"><i class="fa fa-window-restore"></i><span class="sidemenu-label">Users</span></a>
            </li>
            <?php
            }
            ?>
            <!-- <li class="nav-item ">
                <a class="nav-link" href="<?= url('Account') ?>"><i class="fe fe-airplay"></i><span class="sidemenu-label">Log in to Compay</span></a>
            </li> -->
        </ul>
    </div>
</div>
<!-- End Sidemenu -->