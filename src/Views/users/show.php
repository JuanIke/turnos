<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['name']) ?> - Perfil de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #87CEEB 0%, #1E90FF 50%, #0066CC 100%);
        }
        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <!-- Header -->
    <header class="glass-card shadow-lg border-b border-white/20 mb-8 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <a href="/users" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-lg">👤</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Perfil de Usuario</h1>
                        <p class="text-sm text-gray-600">Información Detallada</p>
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/users" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 text-sm font-medium">← Volver</a>
                    <a href="/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-sm font-medium">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Panel Izquierdo: Información del Usuario -->
            <div class="glass-card rounded-2xl shadow-xl border border-white/20">
                <div class="p-8">
                    <!-- Avatar y Info Principal -->
                    <div class="text-center mb-8">
                        <div class="w-32 h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4 shadow-lg">
                            <?= strtoupper(substr($user['name'], 0, 2)) ?>
                        </div>
                        <div class="flex items-center justify-center mb-2">
                            <h2 id="userName" class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($user['name']) ?></h2>
                            <button onclick="editUserName()" class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <input id="userNameInput" type="text" value="<?= htmlspecialchars($user['name']) ?>" class="hidden text-2xl font-bold text-gray-900 bg-transparent border-b-2 border-blue-500 focus:outline-none text-center">
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($user['email']) ?></p>
                        
                        <?php
                        $roleColors = [
                            'superadmin' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'admin' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'user' => 'bg-green-100 text-green-800 border-green-200'
                        ];
                        $roleColor = $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border <?= $roleColor ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>

                    <!-- Información Detallada -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Información Personal</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium"><?= htmlspecialchars($user['email']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipo:</span>
                                <span class="font-medium"><?= ucfirst($user['role']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Registro:</span>
                                <span class="font-medium"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Estado:</span>
                                <span class="font-medium <?= $user['is_active'] ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $user['is_active'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Grupos:</span>
                                <span class="font-medium text-blue-600"><?= count($userGroupsWithRoles) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho: Grupos de Trabajo -->
            <div class="glass-card rounded-2xl shadow-xl border border-white/20">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">Grupos de Trabajo</h3>
                        <button onclick="addUserToGroup()" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 text-sm font-medium">
                            + Agregar Grupo
                        </button>
                    </div>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div id="userGroups" class="space-y-4">
                        <?php if (empty($userGroupsWithRoles)): ?>
                            <div class="text-center py-8">
                                <div class="text-gray-400 text-4xl mb-3">👥</div>
                                <p class="text-gray-600">No pertenece a ningún grupo de trabajo</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($userGroupsWithRoles as $groupData): ?>
                                <div class="group-card bg-white/70 rounded-xl border border-gray-200 p-4" data-group-id="<?= $groupData['group']['id'] ?>">
                                    <!-- Header del grupo -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-medium" style="background-color: <?= htmlspecialchars($groupData['group']['color']) ?>">
                                                <?= strtoupper(substr($groupData['group']['name'], 0, 1)) ?>
                                            </div>
                                            <span class="font-medium text-gray-900"><?= htmlspecialchars($groupData['group']['name']) ?></span>
                                        </div>
                                        <button onclick="removeUserFromGroup(<?= $groupData['group']['id'] ?>)" class="text-red-500 hover:text-red-700 transition-colors" title="Eliminar del grupo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Roles del usuario en este grupo -->
                                    <div class="roles-section">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-700">Puestos de Trabajo:</span>
                                            <button onclick="addRoleToGroup(<?= $groupData['group']['id'] ?>, '<?= htmlspecialchars($groupData['group']['name']) ?>')" class="text-blue-500 hover:text-blue-700 text-sm">
                                                + Agregar Puesto
                                            </button>
                                        </div>
                                        <div class="roles-list space-y-1">
                                            <?php if (empty($groupData['roles'])): ?>
                                                <p class="text-sm text-gray-500 italic">Sin puestos asignados</p>
                                            <?php else: ?>
                                                <?php foreach ($groupData['roles'] as $role): ?>
                                                    <div class="flex items-center justify-between bg-blue-50 px-3 py-1 rounded-lg">
                                                        <span class="text-sm text-blue-800"><?= htmlspecialchars($role['name']) ?></span>
                                                        <button onclick="removeRoleFromUser(<?= $role['id'] ?>, <?= $groupData['group']['id'] ?>)" class="text-red-500 hover:text-red-700">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar grupo -->
    <div id="addGroupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Agregar a Grupo</h3>
                <button onclick="closeAddGroupModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="addGroupContent">
                <!-- Contenido será cargado dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Modal para agregar rol -->
    <div id="addRoleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="addRoleTitle" class="text-xl font-bold text-gray-900">Agregar Puesto</h3>
                <button onclick="closeAddRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="addRoleContent">
                <!-- Contenido será cargado dinámicamente -->
            </div>
        </div>
    </div>

    <script>
        const userId = <?= $user['id'] ?>;
        
        // Edición del nombre de usuario
        function editUserName() {
            const nameDisplay = document.getElementById('userName');
            const nameInput = document.getElementById('userNameInput');
            
            nameDisplay.classList.add('hidden');
            nameInput.classList.remove('hidden');
            nameInput.focus();
            nameInput.select();
            
            nameInput.addEventListener('blur', saveUserName);
            nameInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    saveUserName();
                }
            });
        }

        function saveUserName() {
            const nameDisplay = document.getElementById('userName');
            const nameInput = document.getElementById('userNameInput');
            const newName = nameInput.value.trim();
            
            if (newName && newName !== nameDisplay.textContent) {
                fetch(`/users/${userId}/update-name`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: newName })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nameDisplay.textContent = newName;
                        location.reload();
                    } else {
                        alert('Error al actualizar el nombre');
                    }
                })
                .catch(() => alert('Error de conexión'));
            }
            
            nameInput.classList.add('hidden');
            nameDisplay.classList.remove('hidden');
        }

        // Agregar usuario a grupo
        function addUserToGroup() {
            document.getElementById('addGroupModal').classList.remove('hidden');
            loadAvailableGroups();
        }

        function closeAddGroupModal() {
            document.getElementById('addGroupModal').classList.add('hidden');
        }

        function loadAvailableGroups() {
            fetch(`/users/${userId}/available-groups`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('addGroupContent').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('addGroupContent').innerHTML = '<p class="text-red-600">Error al cargar grupos</p>';
            });
        }

        // Eliminar usuario de grupo
        function removeUserFromGroup(groupId) {
            if (confirm('¿Eliminar usuario de este grupo?')) {
                fetch(`/users/${userId}/groups/${groupId}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar del grupo');
                    }
                })
                .catch(() => alert('Error de conexión'));
            }
        }

        // Agregar rol a usuario en grupo
        function addRoleToGroup(groupId, groupName) {
            document.getElementById('addRoleModal').classList.remove('hidden');
            document.getElementById('addRoleTitle').textContent = `Agregar Puesto en ${groupName}`;
            loadAvailableRoles(groupId);
        }

        function closeAddRoleModal() {
            document.getElementById('addRoleModal').classList.add('hidden');
        }

        function loadAvailableRoles(groupId) {
            fetch(`/users/${userId}/groups/${groupId}/available-roles`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('addRoleContent').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('addRoleContent').innerHTML = '<p class="text-red-600">Error al cargar roles</p>';
            });
        }

        // Eliminar rol de usuario
        function removeRoleFromUser(roleId, groupId) {
            if (confirm('¿Eliminar este puesto del usuario?')) {
                fetch(`/users/${userId}/roles/${roleId}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar el puesto');
                    }
                })
                .catch(() => alert('Error de conexión'));
            }
        }

        // Cerrar modales al hacer clic fuera
        document.getElementById('addGroupModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddGroupModal();
        });

        document.getElementById('addRoleModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddRoleModal();
        });
    </script>
</body>
</html>