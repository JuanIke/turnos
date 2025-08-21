<?php if (empty($availableGroups)): ?>
    <div class="text-center py-8">
        <div class="text-gray-400 text-4xl mb-3">👥</div>
        <p class="text-gray-600">No hay grupos disponibles para agregar</p>
    </div>
<?php else: ?>
    <div class="space-y-3">
        <p class="text-gray-600 mb-4">Selecciona un grupo para agregar al usuario:</p>
        <?php foreach ($availableGroups as $group): ?>
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-medium" style="background-color: <?= htmlspecialchars($group['color']) ?>">
                        <?= strtoupper(substr($group['name'], 0, 1)) ?>
                    </div>
                    <span class="font-medium text-gray-900"><?= htmlspecialchars($group['name']) ?></span>
                </div>
                <button onclick="assignUserToGroup(<?= $group['id'] ?>)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                    Agregar
                </button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function assignUserToGroup(groupId) {
    fetch(`/users/${userId}/groups/${groupId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddGroupModal();
            location.reload();
        } else {
            alert('Error al agregar al grupo');
        }
    })
    .catch(() => alert('Error de conexión'));
}
</script>