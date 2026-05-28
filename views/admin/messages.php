<h1>Kontakt poruke</h1>

<div class="message-filters">

    <a 
        class="<?= ($status ?? '') === '' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=contact_messages"
    >
        All
    </a>

    <a 
        class="<?= ($status ?? '') === 'Unread' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=contact_messages&status=Unread"
    >
        Unread
    </a>

    <a 
        class="<?= ($status ?? '') === 'Read' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=contact_messages&status=Read"
    >
        Read
    </a>

    <a 
        class="<?= ($status ?? '') === 'Archived' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=contact_messages&status=Archived"
    >
        Archived
    </a>

</div>

<table class="products-table messages-table">

    <tr>
        <th>ID</th>
        <th>Ime</th>
        <th>Email</th>
        <th>Tema</th>
        <th>Status</th>
        <th>Vrijeme</th>
        <th>Akcija</th>
    </tr>

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?= (int)$message->id ?></td>

                <td><?= htmlspecialchars($message->full_name) ?></td>

                <td><?= htmlspecialchars($message->email) ?></td>

                <td><?= htmlspecialchars($message->subject) ?></td>

                <td>
                    <span class="message-status status-<?= strtolower($message->status) ?>">
                        <?= htmlspecialchars($message->status) ?>
                    </span>
                </td>

                <td><?= htmlspecialchars($message->created_at) ?></td>

                <td>
                    <a 
                        class="table-action"
                        href="index.php?page=adminPanel&view=contact_message_info&id=<?= (int)$message->id ?>"
                    >
                        Pregledaj
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Nema poruka.</td>
        </tr>
    <?php endif; ?>

</table>