<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos de Trabajo - Sistema de Turnos</title>
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
                <a href="/dashboard" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-lg">⛪</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Grupos de Trabajo</h1>
                        <p class="text-sm text-gray-600">Ministerio Comunicaciones</p>
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    <button onclick="openCreateModal()" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 text-sm font-medium">
                        + Crear Grupo
                    </button>
                    <a href="/dashboard" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 text-sm font-medium">Volver</a>
                    <a href="/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-sm font-medium">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Lista de Grupos -->
        <div class="glass-card rounded-2xl shadow-xl border border-white/20">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Grupos Disponibles</h2>
            </div>
            <div class="p-6">
                <?php if (empty($groups)): ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">No hay grupos creados</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($groups as $group): ?>
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
                                <div class="grid grid-cols-3 items-center gap-4">
                                    <!-- Columna Izquierda: Nombre -->
                                    <div onclick="window.location.href='/work-groups/<?= $group['id'] ?>/users'">
                                        <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($group['name']) ?></h3>
                                    </div>
                                    
                                    <!-- Columna Central: Cantidad -->
                                    <div class="text-center" onclick="window.location.href='/work-groups/<?= $group['id'] ?>/users'">
                                        <p class="text-sm text-gray-600"><?= count($group['users']) ?> usuario<?= count($group['users']) !== 1 ? 's' : '' ?></p>
                                    </div>
                                    
                                    <!-- Columna Derecha: Botones -->
                                    <div class="flex justify-end items-center space-x-2">
                                        <div class="relative">
                                            <button onclick="toggleMenu(<?= $group['id'] ?>)" class="text-gray-400 hover:text-gray-600 p-2">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                            <div id="menu-<?= $group['id'] ?>" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                                <button onclick="openEditModal(<?= $group['id'] ?>, '<?= htmlspecialchars($group['name']) ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Editar Nombre
                                                </button>
                                                <button onclick="confirmDelete(<?= $group['id'] ?>, '<?= htmlspecialchars($group['name']) ?>')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    Eliminar Grupo
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
    </div>

    <!-- Modal de Edición/Creación -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900 mb-4">Editar Grupo</h3>
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

    <!-- Modal de Confirmación de Eliminación -->
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

        function toggleMenu(groupId) {
            const menu = document.getElementById('menu-' + groupId);
            // Cerrar otros menús
            document.querySelectorAll('[id^="menu-"]').forEach(m => {
                if (m.id !== 'menu-' + groupId) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        }

        function openEditModal(id, name) {
            document.getElementById('modalTitle').textContent = 'Editar Grupo';
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editForm').action = '/work-groups/' + id + '/edit';
            document.getElementById('editModal').classList.remove('hidden');
            // Cerrar menús
            document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
        }
        
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Crear Nuevo Grupo';
            document.getElementById('editId').value = '';
            document.getElementById('editName').value = '';
            document.getElementById('editForm').action = '/work-groups/create';
            document.getElementById('editModal').classList.remove('hidden');
        }
        
        function confirmDelete(id, name) {
            document.getElementById('deleteGroupName').textContent = name;
            document.getElementById('confirmDeleteBtn').onclick = function() {
                window.location.href = '/work-groups/' + id + '/delete';
            };
            document.getElementById('deleteModal').classList.remove('hidden');
            // Cerrar menús
            document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Cerrar modal y menús al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (e.target.id === 'editModal') {
                closeEditModal();
            }
            if (e.target.id === 'deleteModal') {
                closeDeleteModal();
            }
            if (!e.target.closest('[id^="menu-"]') && !e.target.closest('button')) {
                document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
</body>
</html>