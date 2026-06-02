<div class="admin-message-page">

    <div class="message-header-card">

        <div>
            <h1>Poruka #<?= (int)$message->id ?></h1>
            <p>Primljeno: <?= htmlspecialchars($message->created_at) ?></p>
        </div>

        <span class="message-status status-<?= strtolower($message->status) ?>">
            <?= htmlspecialchars($message->status) ?>
        </span>

    </div>

    <div class="message-details-grid">

        <div class="message-info-card">

            <h2>Podaci pošiljaoca</h2>

            <div class="message-info-row">
                <span>Ime</span>
                <strong><?= htmlspecialchars($message->full_name) ?></strong>
            </div>

            <div class="message-info-row">
                <span>Email</span>
                <strong><?= htmlspecialchars($message->email) ?></strong>
            </div>

            <div class="message-info-row">
                <span>Tema</span>
                <strong><?= htmlspecialchars($message->subject) ?></strong>
            </div>

            <form 
                method="POST" 
                action="index.php?page=adminPanel&action=update_contact_message_status"
                class="message-status-form"
            >

                <input 
                    type="hidden" 
                    name="message_id" 
                    value="<?= (int)$message->id ?>"
                >

                <div class="message-info-row">
                    <span>Status</span>

                    <select name="status" required>
                        <option value="Unread" <?= $message->status === 'Unread' ? 'selected' : '' ?>>Nepročitana</option>
                        <option value="Read" <?= $message->status === 'Read' ? 'selected' : '' ?>>Pročitana</option>
                        <option value="Archived" <?= $message->status === 'Archived' ? 'selected' : '' ?>>Arhivirana</option>
                    </select>
                </div>

                <button type="submit" class="btn">
                    Spremi stanje
                </button>

            </form>

        </div>

        <div class="message-content-card">

            <h2>Poruka</h2>

            <div class="message-body">
                <?= nl2br(htmlspecialchars($message->message)) ?>
            </div>

            <a 
                class="back-link"
                href="index.php?page=adminPanel&view=viewMessages"
            >
                Nazad na poruke
            </a>

        </div>

    </div>

</div>