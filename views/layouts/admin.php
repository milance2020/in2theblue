
<div class="admin-layout">

    <aside class="sidebar">
            <div  class="admin-sidebar">
        
        
        <div id="links-admin">
          <ul>
            <li><a href="index.php?page=adminPanel">Dashboard</a></li>
            <hr>
            <li><a href="<?= appUrl('shop') ?>">Shop</a></li>
            <li><a href="index.php?page=adminPanel&view=view">Pregled Proizvoda</a></li>
            <li><a href="index.php?page=adminPanel&view=insert">Unesi proizvod</a></li>
            
            <hr>
            <li><a href="index.php?page=adminPanel&view=orders">Pregled narudzbi</a></li>

            <hr>
            <li><a href="index.php?page=adminPanel&view=viewMessages">Pregled poruka</a></li>
            <li><a href="index.php?page=adminPanel&view=viewComments">Pregled komentara</a></li>
            <hr>
            <li><a href="index.php?page=adminPanel&view=insertNews">Unesi vesti</a></li>
            <li><a href="index.php?page=adminPanel&view=viewNews">Pregledaj vesti</a></li>
            <hr>
            <li><a href="index.php?page=adminPanel&view=insertUsers">Unesi radnike</a></li>
            <hr>
            <?php $page = $_GET['page'] ?? ''; ?>
            <?php if ($page !== 'adminPanel'): ?>
                <a href="<?= appUrl('login') ?>">Log in</a>
            <?php else: ?>
                <a href="<?= logoutUrl() ?>" id="logout-admin">Log out</a>
            <?php endif; ?>
            </ul>
        </div>


    </div>
    </aside>

    <main class="content">
        <?= flash_render() ?>

        <?php if (!empty($_output['view'])): ?>
            <?php
            $__viewFile = view_path($_output['view']);
            if (is_file($__viewFile)) {
                include $__viewFile;
            } else {
                print_r($_output['view']);
                echo 'View not found: ' . htmlspecialchars($_output['view']);
            }
            ?>
        <?php endif; ?>
    </main>

</div>
