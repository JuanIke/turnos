<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($group['name']) ?> - Grupos de Trabajo</title>
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
                <a href="<?= $_SESSION['user_role'] === 'superadmin' ? '/dashboard' : '/shifts' ?>" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-lg">⛪</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($group['name']) ?></h1>
                        <p class="text-sm text-gray-600">Ministerio Comunicaciones</p>
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/work-groups" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 text-sm font-medium">Volver</a>
                    <a href="/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-sm font-medium">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Usuarios del Grupo -->
            <div class="glass-card rounded-2xl shadow-xl border border-white/20">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Usuarios</h2>
                            <p class="text-gray-600 mt-1"><?= count($users) ?> usuario<?= count($users) !== 1 ? 's' : '' ?></p>
                        </div>
                        <div class="relative">
                            <button onclick="toggleGroupMenu()" class="text-gray-400 hover:text-gray-600 p-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                            <div id="groupMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                <button onclick="openEditModal(<?= $group['id'] ?>, '<?= htmlspecialchars($group['name']) ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Editar Nombre
                                </button>
                                <button onclick="openAddUsersModal()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Añadir Usuario
                                </button>
                                <button onclick="confirmDeleteGroup(<?= $group['id'] ?>, '<?= htmlspecialchars($group['name']) ?>')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Eliminar Grupo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (empty($users)): ?>
                        <div class="text-center py-8">
                            <p class="text-gray-500">No hay usuarios asignados</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($users as $user): ?>
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200 hover:shadow-lg transition-all duration-300">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($user['name']) ?></h4>
                                            <?php if (!empty($user['ministry_roles'])): ?>
                                                <div class="text-xs text-blue-600 mt-1">
                                                    <?= implode(', ', array_column($user['ministry_roles'], 'name')) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <?php if ($user['role'] === 'superadmin'): ?>
                                                <span class="bg-gradient-to-r from-red-100 to-red-200 text-red-800 text-xs px-2 py-1 rounded-full font-medium">Super Admin</span>
                                            <?php elseif ($user['role'] === 'admin'): ?>
                                                <span class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">Admin</span>
                                            <?php else: ?>
                                                <span class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 text-xs px-2 py-1 rounded-full font-medium">Usuario</span>
                                            <?php endif; ?>
                                            <div class="relative">
                                                <button onclick="toggleUserMenu(<?= $user['id'] ?>)" class="text-gray-400 hover:text-gray-600 p-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                    </svg>
                                                </button>
                                                <div id="userMenu-<?= $user['id'] ?>" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                                    <button onclick="openRoleModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Gestionar Roles
                                                    </button>
                                                    <button onclick="removeUserFromGroup(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Roles del Ministerio -->
            <div class="glass-card rounded-2xl shadow-xl border border-white/20">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Roles del Ministerio</h2>
                    <p class="text-gray-600 mt-1"><?= count($groupRoles) ?> rol<?= count($groupRoles) !== 1 ? 'es' : '' ?> disponible<?= count($groupRoles) !== 1 ? 's' : '' ?></p>
                </div>
                <div class="p-6">
                    <?php if (empty($groupRoles)): ?>
                        <div class="text-center py-8">
                            <p class="text-gray-500">No hay roles definidos</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($groupRoles as $role): ?>
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
                                    <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($role['name']) ?></h4>
                                    <?php if ($role['description']): ?>
                                        <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($role['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Grupo -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Editar Grupo</h3>
            <form id="editForm" method="POST">
                <input type="hidden" id="editId" name="id">
                <div class="space-y-4">
                    <div>
                        <label for="editName" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Grupo</label>
                        <input type="text" id="editName" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-cyan-600 hover:to-blue-700 transition-all duration-200">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Confirmación de Eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Eliminar Grupo</h3>
                <p class="text-sm text-gray-600 mb-6">
                    ¿Estás seguro de eliminar el grupo "<span id="deleteGroupName" class="font-medium"></span>"?
                    <br><br>
                    Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="button" id="confirmDeleteBtn" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Gestionar Roles -->
    <div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Gestionar Roles</h3>
            <div id="roleModalContent">
                <p class="text-sm text-gray-600 mb-4">Selecciona los roles para <span id="roleUserName" class="font-medium"></span>:</p>
                <div class="space-y-2" id="rolesList">
                    <?php foreach ($groupRoles as $role): ?>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" class="role-checkbox" data-role-id="<?= $role['id'] ?>" class="rounded">
                            <span class="text-sm"><?= htmlspecialchars($role['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeRoleModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                    Cancelar
                </button>
                <button type="button" onclick="saveUserRoles()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                    Guardar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Confirmación Eliminar Usuario -->
    <div id="deleteUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Eliminar Usuario</h3>
                <p class="text-sm text-gray-600 mb-6">
                    ¿Estás seguro de eliminar a "<span id="deleteUserName" class="font-medium"></span>" del grupo?
                    <br><br>
                    Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteUserModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="button" id="confirmDeleteUserBtn" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Añadir Usuarios -->
    <div id="addUsersModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Añadir Usuarios al Grupo</h3>
            <form method="POST" action="/work-groups/<?= $group['id'] ?>/add-user">
                <div class="mb-4">
                    <label for="userId" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Usuario</label>
                    <select id="userId" name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione un usuario...</option>
                        <?php foreach ($availableUsers as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddUsersModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200">
                        Añadir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.glass-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        function openAddUsersModal() {
            document.getElementById('addUsersModal').classList.remove('hidden');
        }

        function closeAddUsersModal() {
            document.getElementById('addUsersModal').classList.add('hidden');
        }
        
        function toggleGroupMenu() {
            document.getElementById('groupMenu').classList.toggle('hidden');
        }
        
        function openEditModal(id, name) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editForm').action = '/work-groups/' + id + '/edit';
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('groupMenu').classList.add('hidden');
        }
        
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        function confirmDeleteGroup(id, name) {
            document.getElementById('deleteGroupName').textContent = name;
            document.getElementById('confirmDeleteBtn').onclick = function() {
                window.location.href = '/work-groups/' + id + '/delete';
            };
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('groupMenu').classList.add('hidden');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        
        function toggleUserMenu(userId) {
            const menu = document.getElementById('userMenu-' + userId);
            // Cerrar otros menús de usuarios
            document.querySelectorAll('[id^="userMenu-"]').forEach(m => {
                if (m.id !== 'userMenu-' + userId) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        }
        
        function removeUserFromGroup(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('confirmDeleteUserBtn').onclick = function() {
                window.location.href = '/work-groups/<?= $group['id'] ?>/remove-user/' + userId;
            };
            document.getElementById('deleteUserModal').classList.remove('hidden');
            // Cerrar menús
            document.querySelectorAll('[id^="userMenu-"]').forEach(m => m.classList.add('hidden'));
        }
        
        function closeDeleteUserModal() {
            document.getElementById('deleteUserModal').classList.add('hidden');
        }
        
        let currentUserId = null;
        
        function openRoleModal(userId, userName) {
            currentUserId = userId;
            document.getElementById('roleUserName').textContent = userName;
            
            // Cargar roles actuales del usuario
            fetch(`/work-groups/<?= $group['id'] ?>/user-roles/${userId}`)
                .then(response => response.json())
                .then(data => {
                    // Marcar checkboxes según roles actuales
                    document.querySelectorAll('.role-checkbox').forEach(checkbox => {
                        const roleId = parseInt(checkbox.dataset.roleId);
                        checkbox.checked = data.roles.includes(roleId);
                    });
                });
            
            document.getElementById('roleModal').classList.remove('hidden');
            // Cerrar menús
            document.querySelectorAll('[id^="userMenu-"]').forEach(m => m.classList.add('hidden'));
        }
        
        function closeRoleModal() {
            document.getElementById('roleModal').classList.add('hidden');
        }
        
        function saveUserRoles() {
            const selectedRoles = [];
            document.querySelectorAll('.role-checkbox:checked').forEach(checkbox => {
                selectedRoles.push(parseInt(checkbox.dataset.roleId));
            });
            
            fetch(`/work-groups/<?= $group['id'] ?>/assign-roles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: currentUserId,
                    role_ids: selectedRoles
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al guardar roles');
                }
            });
        }

        // Cerrar modales y menús al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (e.target.id === 'addUsersModal') {
                closeAddUsersModal();
            }
            if (e.target.id === 'editModal') {
                closeEditModal();
            }
            if (e.target.id === 'deleteModal') {
                closeDeleteModal();
            }
            if (!e.target.closest('#groupMenu') && !e.target.closest('button')) {
                document.getElementById('groupMenu').classList.add('hidden');
            }
            if (!e.target.closest('[id^="userMenu-"]') && !e.target.closest('button')) {
                document.querySelectorAll('[id^="userMenu-"]').forEach(m => m.classList.add('hidden'));
            }
            if (e.target.id === 'deleteUserModal') {
                closeDeleteUserModal();
            }
            if (e.target.id === 'roleModal') {
                closeRoleModal();
            }
        });
    </script>
</body>
</html>