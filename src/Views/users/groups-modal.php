<div id="groupsForm">
    <div class="space-y-4">
        <p class="text-gray-600 mb-4">Selecciona los grupos de trabajo a los que pertenece el usuario:</p>
        
        <?php if (empty($allGroups)): ?>
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-3">👥</div>
                <p class="text-gray-600">No hay grupos disponibles</p>
            </div>
        <?php else: ?>
            <div class="max-h-96 overflow-y-auto space-y-3">
                <?php foreach ($allGroups as $group): ?>
                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="groups[]" 
                            value="<?= $group['id'] ?>" 
                            <?= in_array($group['id'], $userGroupIds) ? 'checked' : '' ?>
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <div class="flex items-center space-x-3 ml-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-medium" style="background-color: <?= htmlspecialchars($group['color']) ?>">
                                <?= strtoupper(substr($group['name'], 0, 1)) ?>
                            </div>
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($group['name']) ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <button type="button" onclick="closeGroupModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
            Cancelar
        </button>
        <button type="button" id="saveGroupsBtn" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200" data-user-id="<?= $user['id'] ?? '' ?>" onclick="handleSaveGroups()">
            Guardar Cambios
        </button>
    </div>
</div>

<script>
window.handleSaveGroups = function() {
    const button = document.getElementById('saveGroupsBtn');
    const checkboxes = document.querySelectorAll('input[name="groups[]"]');
    const selectedGroups = [];
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            selectedGroups.push(checkbox.value);
        }
    });
    
    const originalText = button.textContent;
    button.textContent = 'Guardando...';
    button.disabled = true;
    
    const currentUserId = window.userId || button.dataset.userId;
    
    if (!currentUserId) {
        alert('Error: No se pudo identificar el usuario');
        button.textContent = originalText;
        button.disabled = false;
        return;
    }
    
    fetch('/users/' + currentUserId + '/update-groups', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ groups: selectedGroups })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeGroupModal();
            location.reload();
        } else {
            alert('Error al actualizar los grupos');
        }
    })
    .catch(error => {
        alert('Error de conexión');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled = false;
    });
};
</script>

