<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación Automática - Sistema de Turnos</title>
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
                        <h1 class="text-xl font-bold text-gray-900">Asignación Automática</h1>
                        <p class="text-sm text-gray-600">Ministerio Comunicaciones</p>
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/shifts" class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 text-sm font-medium">Volver</a>
                    <a href="/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-sm font-medium">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Selección de Grupos -->
        <div class="glass-card rounded-2xl shadow-xl border border-white/20">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Seleccionar Ministerio</h2>
                <p class="text-sm text-gray-600 mt-1">Elige el ministerio para gestionar disponibilidad</p>
            </div>
            <div class="p-6">
                <?php if (empty($userGroups)): ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">No tienes ministerios asignados</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($userGroups as $group): ?>
                            <a href="/shifts/auto-assign/<?= $group['id'] ?>" class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200 hover:shadow-lg transition-all duration-300 cursor-pointer block">
                                <div class="text-center">
                                    <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($group['name']) ?></h3>
                                    <p class="text-sm text-gray-600 mt-1">Gestionar disponibilidad</p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
    </script>
</body>
</html>