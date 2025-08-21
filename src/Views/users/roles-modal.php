<form id="rolesForm">
    <div class="space-y-4">
        <p class="text-gray-600 mb-4">Selecciona los roles que tiene el usuario en este grupo:</p>
        
        <?php if (empty($allRoles)): ?>
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-3">🎭</div>
                <p class="text-gray-600">No hay roles disponibles en este grupo</p>
                <p class="text-sm text-gray-500 mt-2">Los roles se pueden crear desde la gestión de grupos de trabajo</p>
            </div>
        <?php else: ?>
            <div class="max-h-96 overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <?php foreach ($allRoles as $role): ?>
                        <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="roles[]" 
                                value="<?= $role['id'] ?>" 
                                <?= in_array($role['id'], $userRoleIds) ? 'checked' : '' ?>
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <div class="flex items-center space-x-2 ml-3">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($role['name']) ?></span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <button type="button" onclick="closeRoleModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
            Cancelar
        </button>
        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
            Guardar Roles
        </button>
    </div>
</form>

<script>
document.getElementById('rolesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const selectedRoles = formData.getAll('roles[]');
    
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.textContent;
    button.textContent = 'Guardando...';
    button.disabled = true;
    
    fetch(`/users/<?= $userId ?>/update-roles/<?= $groupId ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({ roles: selectedRoles })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRoleModal();
            location.reload(); // Recargar para mostrar cambios
        } else {
            alert('Error al actualizar los roles');
        }
    })
    .catch(error => {
        alert('Error de conexión');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled = false;
    });
});
</script>