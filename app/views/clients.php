<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Gestione Clienti</h2>
        <a href="clients.php?action=new" class="btn">Nuovo Cliente</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client['nome']); ?></td>
                        <td><?php echo htmlspecialchars($client['cognome']); ?></td>
                        <td><?php echo htmlspecialchars($client['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td>
                            <a href="clients.php?action=show&id=<?php echo $client['cliente_id']; ?>" class="btn">Vedi</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nessun cliente trovato.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
