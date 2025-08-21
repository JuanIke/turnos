<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Sistema de Turnos</title>
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
                        <h1 class="text-xl font-bold text-gray-900">Usuarios</h1>
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
        <!-- Lista de Usuarios -->
        <div class="glass-card rounded-2xl shadow-xl border border-white/20">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Gestión de Usuarios</h2>
                        <p class="text-gray-600 mt-1">Total: <span id="userCount"><?= count($users) ?></span> usuarios</p>
                    </div>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput" 
                            placeholder="Buscar por nombre..." 
                            class="w-80 px-4 py-2 pl-10 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <?php if (empty($users)): ?>
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">👥</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay usuarios</h3>
                        <p class="text-gray-600">No se encontraron usuarios en el sistema.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-50 transition-colors" onclick="sortTable('name')">
                                        <div class="flex items-center space-x-1">
                                            <span>Nombre</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-900">Email</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-50 transition-colors" onclick="sortTable('role')">
                                        <div class="flex items-center space-x-1">
                                            <span>Tipo</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-900 cursor-pointer hover:bg-gray-50 transition-colors" onclick="sortTable('date')">
                                        <div class="flex items-center space-x-1">
                                            <span>Fecha Registro</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <?php foreach ($users as $user): ?>
                                    <tr class="user-row border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" 
                                        data-name="<?= strtolower($user['name']) ?>"
                                        data-role="<?= $user['role'] ?>"
                                        data-date="<?= $user['created_at'] ?>"
                                        onclick="window.location.href='/users/<?= $user['id'] ?>'">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                                </div>
                                                <span class="font-medium text-gray-900"><?= htmlspecialchars($user['name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-600"><?= htmlspecialchars($user['email']) ?></td>
                                        <td class="py-4 px-4">
                                            <?php
                                            $roleColors = [
                                                'superadmin' => 'bg-purple-100 text-purple-800',
                                                'admin' => 'bg-blue-100 text-blue-800',
                                                'user' => 'bg-green-100 text-green-800'
                                            ];
                                            $roleColor = $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $roleColor ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-gray-600 text-sm">
                                            <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animaciones de entrada
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

            // Funcionalidad de búsqueda
            const searchInput = document.getElementById('searchInput');
            const userRows = document.querySelectorAll('.user-row');
            const userCount = document.getElementById('userCount');
            const totalUsers = userRows.length;

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;

                userRows.forEach(row => {
                    const userName = row.dataset.name;
                    
                    if (userName.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Actualizar contador
                if (searchTerm === '') {
                    userCount.textContent = totalUsers;
                } else {
                    userCount.textContent = visibleCount + ' de ' + totalUsers;
                }

                // Mostrar mensaje si no hay resultados
                const tbody = document.getElementById('usersTableBody');
                const noResultsMessage = document.getElementById('noResultsMessage');
                
                if (visibleCount === 0 && searchTerm !== '') {
                    if (!noResultsMessage) {
                        const noResultsRow = document.createElement('tr');
                        noResultsRow.id = 'noResultsMessage';
                        noResultsRow.innerHTML = `
                            <td colspan="4" class="py-12 text-center">
                                <div class="text-gray-400 text-6xl mb-4">🔍</div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron usuarios</h3>
                                <p class="text-gray-600">No hay usuarios que coincidan con "${searchTerm}"</p>
                            </td>
                        `;
                        tbody.appendChild(noResultsRow);
                    }
                } else if (noResultsMessage) {
                    noResultsMessage.remove();
                }
            });

            // Limpiar búsqueda con Escape
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.dispatchEvent(new Event('input'));
                    this.blur();
                }
            });
        });

        // Variables para ordenamiento
        let sortColumn = null;
        let sortDirection = 'asc';

        function sortTable(column) {
            const tbody = document.getElementById('usersTableBody');
            const rows = Array.from(tbody.querySelectorAll('.user-row'));
            
            // Determinar dirección de ordenamiento
            if (sortColumn === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortDirection = 'asc';
            }
            sortColumn = column;

            // Ordenar filas
            rows.sort((a, b) => {
                let valueA, valueB;

                switch(column) {
                    case 'name':
                        valueA = a.dataset.name;
                        valueB = b.dataset.name;
                        break;
                    case 'role':
                        // Ordenar por jerarquía: superadmin > admin > user
                        const roleOrder = { 'superadmin': 3, 'admin': 2, 'user': 1 };
                        valueA = roleOrder[a.dataset.role] || 0;
                        valueB = roleOrder[b.dataset.role] || 0;
                        break;
                    case 'date':
                        valueA = new Date(a.dataset.date);
                        valueB = new Date(b.dataset.date);
                        break;
                    default:
                        return 0;
                }

                if (sortDirection === 'asc') {
                    return valueA > valueB ? 1 : valueA < valueB ? -1 : 0;
                } else {
                    return valueA < valueB ? 1 : valueA > valueB ? -1 : 0;
                }
            });

            // Actualizar indicadores visuales en headers
            updateSortIndicators(column, sortDirection);

            // Reordenar filas en el DOM
            rows.forEach(row => tbody.appendChild(row));
        }

        function updateSortIndicators(activeColumn, direction) {
            // Resetear todos los indicadores
            document.querySelectorAll('th svg').forEach(svg => {
                svg.className = 'w-4 h-4 text-gray-400';
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>';
            });

            // Actualizar el indicador activo
            const activeHeader = document.querySelector(`th[onclick="sortTable('${activeColumn}')"] svg`);
            if (activeHeader) {
                activeHeader.className = 'w-4 h-4 text-blue-500';
                if (direction === 'asc') {
                    activeHeader.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>';
                } else {
                    activeHeader.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
                }
            }
        }
    </script>
</body>
</html>