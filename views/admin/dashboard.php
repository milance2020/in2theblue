<section class="admin-dashboard">
    <div class="dashboard-header">
        <span>Upravljački sustav</span>
        <h1>Dashboard</h1>
        <p>Brzi pregled najvažnijih stvari u shopu.</p>
    </div>

    <div class="dashboard-cards">
        <a href="index.php?page=adminPanel&view=orders&status=Pending" class="dashboard-card">
            <span>Narudžbe na čekanju</span>
            <strong><?= (int) $dashboard['orders_pending'] ?></strong>
            <small>Otvori narudzbe</small>
        </a>

        <a href="index.php?page=adminPanel&view=view" class="dashboard-card">
            <span>Aktivni proizvodi</span>
            <strong><?= (int) $dashboard['products_active'] ?></strong>
            <small>Pregled proizvoda</small>
        </a>

        <a href="index.php?page=adminPanel&view=viewComments" class="dashboard-card">
            <span>Komentari na čekanju</span>
            <strong><?= (int) $dashboard['comments_pending'] ?></strong>
            <small>Moderacija komentara</small>
        </a>

        <a href="index.php?page=adminPanel&view=viewMessages&status=Unread" class="dashboard-card">
            <span>Nepročitane poruke</span>
            <strong><?= (int) $dashboard['messages_unread'] ?></strong>
            <small>Pregled poruka</small>
        </a>
    </div>
</section>
