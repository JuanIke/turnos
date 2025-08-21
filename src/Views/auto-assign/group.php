<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($group['name']) ?> - Asignación Automática</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #87CEEB 0%, #1E90FF 50%, #0066CC 100%);
        }
        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .availability-cell {
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .available {
            background-color: #10B981;
        }
        .unavailable {
            background-color: #EF4444;
        }
        .assigned {
            background-color: #8B5CF6;
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
                        <p class="text-sm text-gray-600">Gestión de Disponibilidad</p>
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/shifts/auto-assign" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 text-sm font-medium">Volver</a>
                    <a href="/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-sm font-medium">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Matriz de Disponibilidad -->
        <div class="glass-card rounded-2xl shadow-xl border border-white/20">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Asignación</h2>
                    <div class="flex flex-col space-y-2 text-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-500 rounded"></div>
                            <span>Disponible</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-purple-500 rounded"></div>
                            <span>Asignado</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-500 rounded"></div>
                            <span>No Disponible</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left p-2 font-medium text-gray-900 min-w-[150px]">Servidor</th>
                            <?php foreach ($shifts as $shift): ?>
                                <th class="text-center p-2 font-medium text-gray-900 min-w-[100px]">
                                    <div class="text-xs"><?= htmlspecialchars($shift['name']) ?></div>
                                    <div class="text-xs text-gray-600"><?= date('d/m', strtotime($shift['date'])) ?></div>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="border-t border-gray-200">
                                <td class="p-2 font-medium text-gray-900">
                                    <?= htmlspecialchars($user['name']) ?>
                                    <div class="text-xs text-gray-600"><?= ucfirst($user['role']) ?></div>
                                </td>
                                <?php foreach ($shifts as $shift): ?>
                                    <td class="p-2 text-center">
                                        <div 
                                            class="availability-cell mx-auto rounded-lg flex items-center justify-center available"
                                            data-user="<?= $user['id'] ?>"
                                            data-shift="<?= $shift['id'] ?>"
                                            onclick="toggleAvailability(this)"
                                        >
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <button onclick="saveAvailability()" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 font-medium">
                        Guardar Disponibilidad
                    </button>
                    <button onclick="openAIModal()" class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 font-medium">
                        🤖 Asignar con IA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de IA -->
    <div id="aiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="glass-card rounded-2xl shadow-2xl p-6 border border-white/20 w-full max-w-2xl mx-4">
            <h3 class="text-xl font-bold text-gray-900 mb-4">🤖 Asignación Inteligente</h3>
            <div class="space-y-4">
                <div>
                    <label for="aiInstructions" class="block text-sm font-medium text-gray-700 mb-2">Instrucciones para la IA</label>
                    <textarea 
                        id="aiInstructions" 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                        placeholder="Ejemplo: Asigna a Juan solo los domingos, María no puede los viernes, rotar equitativamente entre todos los servidores..."
                    ></textarea>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl">
                    <h4 class="font-medium text-blue-900 mb-2">Ejemplos de instrucciones:</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• "Rotar equitativamente entre todos los servidores"</li>
                        <li>• "Juan solo domingos, María no puede viernes"</li>
                        <li>• "Priorizar servidores con más experiencia"</li>
                        <li>• "Evitar asignar la misma persona dos días seguidos"</li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAIModal()" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                    Cancelar
                </button>
                <button type="button" onclick="processAIAssignment()" class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-4 py-2 rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200">
                    🤖 Procesar Asignación
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleAvailability(cell) {
            if (cell.classList.contains('available')) {
                cell.classList.remove('available');
                cell.classList.add('assigned');
                cell.innerHTML = '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            } else if (cell.classList.contains('assigned')) {
                cell.classList.remove('assigned');
                cell.classList.add('unavailable');
                cell.innerHTML = '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
            } else {
                cell.classList.remove('unavailable');
                cell.classList.add('available');
                cell.innerHTML = '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            }
        }

        function saveAvailability() {
            const cells = document.querySelectorAll('.availability-cell');
            const availability = [];
            
            cells.forEach(cell => {
                let status = 'unavailable';
                if (cell.classList.contains('available')) status = 'available';
                if (cell.classList.contains('assigned')) status = 'assigned';
                
                availability.push({
                    user_id: cell.dataset.user,
                    shift_id: cell.dataset.shift,
                    status: status
                });
            });

            fetch('/shifts/auto-assign/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ availability: availability })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Disponibilidad guardada correctamente');
                } else {
                    alert('Error al guardar disponibilidad');
                }
            });
        }

        function openAIModal() {
            document.getElementById('aiModal').classList.remove('hidden');
        }

        function closeAIModal() {
            document.getElementById('aiModal').classList.add('hidden');
        }

        function processAIAssignment() {
            const instructions = document.getElementById('aiInstructions').value;
            if (!instructions.trim()) {
                alert('Por favor, proporciona instrucciones para la IA');
                return;
            }

            // Mostrar loading
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '🔄 Procesando...';
            button.disabled = true;

            fetch('/shifts/ai-assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    group_id: <?= $group['id'] ?>,
                    instructions: instructions 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aplicar asignaciones visualmente
                    data.assignments.forEach(assignment => {
                        const cell = document.querySelector(`[data-user="${assignment.user_id}"][data-shift="${assignment.shift_id}"]`);
                        if (cell) {
                            // Cambiar a estado asignado
                            cell.classList.remove('available', 'unavailable');
                            cell.classList.add('assigned');
                            cell.innerHTML = '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                        }
                    });
                    alert(`Asignación completada por IA: ${data.assignments.length} turnos asignados`);
                } else {
                    alert('Error: ' + (data.error || 'No se pudo procesar la asignación'));
                }
            })
            .catch(error => {
                alert('Error de conexión');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                closeAIModal();
            });
        }

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

            // Cerrar modal al hacer clic fuera
            document.getElementById('aiModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAIModal();
                }
            });
        });
    </script>
</body>
</html>