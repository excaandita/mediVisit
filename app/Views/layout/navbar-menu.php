<?php 
$menuItems = [
    [
        'name'  => 'Home',
        'url'   => '/',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>',
    ],
    [
        'name'      => 'Dashboard',
        'url'       => '/dashboard',
        'icon'      => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>',
        'submenu'   => [
            ['name' => 'Pendapatan',                'url' => '/dashboard/pendapatan'],
            ['name' => 'Kunjungan',                 'url' => '/dashboard/kunjungan'],
            
        ],
    ],
    [
        'name'  => 'Kunjungan Penjamin',
        'url'   => '/kunjungan',
        'icon'  => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-align-box-bottom-right"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M11 15v2" /><path d="M14 11v6" /><path d="M17 13v4" /></svg>',
        'submenu'   => [
            ['name' => 'Poliklinik',                'url' => '/kunjungan/poliklinik'],
            ['name' => 'IGD',                       'url' => '/kunjungan/igd'],
            ['name' => 'Rawat Inap',                'url' => '/kunjungan/rawatInap'],
            ['name' => 'Penunjang Radiologi',       'url' => '/kunjungan/penunjangRadiologi'],
            ['name' => 'Penunjang Laboratorium',    'url' => '/kunjungan/penunjangLaboratorium'],
        ],
    ],
    [
        'name'  => 'Pengaturan',
        'url'   => '/setting',
        'icon'  => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>',
        'submenu'   => [
            ['name' => 'User', 'url' => '/setting/user'],
        ],
    ]
];

$currentURL = $_SERVER['REQUEST_URI'];

?>
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <?php foreach ($menuItems as $item): ?>
                        <?php 
                            $isActive = ($currentURL === $item['url']) || ($item['url'] !== '/' && strpos($currentURL, $item['url']) === 0);
                        ?>
                        <li class="nav-item <?= $isActive ? 'active' : ''; ?> <?= isset($item['submenu']) ? 'dropdown' : ''; ?>">
                            <a class="nav-link <?= isset($item['submenu']) ? 'dropdown-toggle' : ''; ?>" href="<?= $item['url']; ?>" <?= isset($item['submenu']) ? 'data-bs-toggle="dropdown"' : ''; ?>>
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <?= $item['icon']; ?>
                                </span>
                                <span class="nav-link-title">
                                    <?= $item['name']; ?>
                                </span>
                            </a>
                            <?php if (isset($item['submenu'])): ?>
                                <ul class="dropdown-menu">
                                    <?php foreach ($item['submenu'] as $submenu): ?>
                                        <li>
                                            <a class="dropdown-item <?= strpos($currentURL, $submenu['url']) === 0 ? 'active' : ''; ?>" href="<?= $submenu['url']; ?>">
                                                <?= $submenu['name']; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</header>
