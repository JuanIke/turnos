<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Turnos - Sistema de Turnos</title>
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
    <header class="glass-card shadow-lg border-b border-white/20 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <a href="<?= $_SESSION['user_role'] === 'superadmin' ? '/dashboard' : '/shifts' ?>" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-lg">⛪</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Gestión de Turnos</h1>
                        <p class="text-sm text-gray-600">Ministerio Comunicaciones</p>
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 text-sm font-medium">Volver</a>
                    <a href="/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-sm font-medium">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (isset($error)): ?>
            <div class="glass-card rounded-2xl p-4 mb-6 border-l-4 border-red-400 text-red-700">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Crear Turno (Solo SuperAdmin) -->
        <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
        <div class="glass-card rounded-2xl shadow-xl p-6 border border-white/20 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Crear Nuevo Turno</h2>
            <form method="POST" action="/shifts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Turno</label>
                    <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" id="date" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio</label>
                    <input type="time" id="start_time" name="start_time" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" step="60">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Hora Fin</label>
                    <input type="time" id="end_time" name="end_time" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" step="60">
                </div>
                <div class="md:col-span-2 lg:col-span-4">
                    <button type="submit" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-2 rounded-xl hover:from-cyan-600 hover:to-blue-700 transition-all duration-200 font-medium">
                        Crear Turno
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Lista de Turnos -->
        <div class="glass-card rounded-2xl shadow-xl border border-white/20">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Turnos Programados</h2>
                    <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'superadmin'): ?>
                        <a href="/shifts/auto-assign" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-2 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-200 text-sm font-medium">
                            Asignación Automática
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="p-6">
                <?php if (empty($shifts)): ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">No hay turnos programados</p>
                    </div>
                <?php else: ?>
                    <div class="grid gap-4">
                        <?php foreach ($shifts as $shift): ?>
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-100 hover:shadow-lg transition-all duration-300 cursor-pointer">
                                <div class="grid grid-cols-3 items-center gap-4">
                                    <!-- Columna Izquierda: Nombre y Horario -->
                                    <div>
                                        <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($shift['name']) ?></h3>
                                        <p class="text-sm text-gray-600">
                                            <?= substr($shift['start_time'], 0, 5) ?> a <?= substr($shift['end_time'], 0, 5) ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Columna Central: Fecha -->
                                    <div class="text-center">
                                        <p class="font-medium text-gray-900"><?= date('d/m/Y', strtotime($shift['date'])) ?></p>
                                    </div>
                                    
                                    <!-- Columna Derecha: Botones -->
                                    <div class="flex justify-end items-center space-x-2">
                                        <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
                                            <button onclick="openEditModal(<?= $shift['id'] ?>, '<?= htmlspecialchars($shift['name']) ?>', '<?= $shift['date'] ?>', '<?= substr($shift['start_time'], 0, 5) ?>', '<?= substr($shift['end_time'], 0, 5) ?>')" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1 rounded-lg text-xs hover:from-green-600 hover:to-green-700 transition-all duration-200">
                                                Editar
                                            </button>
                                            <a href="/shifts/<?= $shift['id'] ?>/delete" onclick="return confirm('¿Estás seguro de eliminar este turno?')" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1 rounded-lg text-xs hover:from-red-600 hover:to-red-700 transition-all duration-200">
                                                Eliminar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Editar Turno</h3>
            <form id="editForm" method="POST">
                <input type="hidden" id="editId" name="id">
                <div class="space-y-4">
                    <div>
                        <label for="editName" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Turno</label>
                        <input type="text" id="editName" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="editDate" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input type="date" id="editDate" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="editStartTime" class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio</label>
                        <input type="time" id="editStartTime" name="start_time" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" step="60">
                    </div>
                    <div>
                        <label for="editEndTime" class="block text-sm font-medium text-gray-700 mb-2">Hora Fin</label>
                        <input type="time" id="editEndTime" name="end_time" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" step="60">
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

        function openEditModal(id, name, date, startTime, endTime) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editDate').value = date;
            document.getElementById('editStartTime').value = startTime;
            document.getElementById('editEndTime').value = endTime;
            document.getElementById('editForm').action = '/shifts/' + id + '/edit';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>