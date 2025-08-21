<?php if (empty($availableRoles)): ?>
    <div class="text-center py-8">
        <div class="text-gray-400 text-4xl mb-3">💼</div>
        <p class="text-gray-600">No hay puestos disponibles para agregar</p>
        <p class="text-sm text-gray-500 mt-2">Todos los puestos ya están asignados a este usuario</p>
    </div>
<?php else: ?>
    <div class="space-y-3">
        <p class="text-gray-600 mb-4">Selecciona un puesto para agregar al usuario:</p>
        <?php foreach ($availableRoles as $role): ?>
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium">
                        💼
                    </div>
                    <span class="font-medium text-gray-900"><?= htmlspecialchars($role['name']) ?></span>
                </div>
                <button onclick="assignRoleToUser(<?= $role['id'] ?>)" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                    Agregar
                </button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function assignRoleToUser(roleId) {
    fetch(`/users/${userId}/roles/${roleId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddRoleModal();
            location.reload();
        } else {
            alert('Error al agregar el puesto');
        }
    })
    .catch(() => alert('Error de conexión'));
}
</script>