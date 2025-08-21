<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Turnos</title>
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
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-lg">⛪</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Sistema de Turnos</h1>
                        <p class="text-sm text-gray-600">Ministerio Comunicaciones</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">Hola, <?= htmlspecialchars($_SESSION['user_name']) ?></p>
                        <div class="flex items-center justify-end mt-1">
                            <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
                                <span class="bg-gradient-to-r from-red-100 to-red-200 text-red-800 text-xs px-3 py-1 rounded-full font-medium">Super Admin</span>
                            <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                                <span class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">Admin</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="/logout" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-sm font-medium">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <?php if ($_SESSION['user_role'] !== 'superadmin'): ?>
    <script>
        window.location.href = '/shifts';
    </script>
    <?php endif; ?>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="/shifts" class="glass-card rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-6 block border border-white/20 hover:scale-105">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Calendario</h3>
                        <p class="text-sm text-gray-600">Crear y administrar turnos</p>
                    </div>
                </div>
            </a>

            <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
            <a href="/users" class="glass-card rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-6 block border border-white/20 hover:scale-105">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-red-100 to-red-200 rounded-xl">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Servidores</h3>
                        <p class="text-sm text-gray-600">Administrar usuarios y servidores</p>
                    </div>
                </div>
            </a>
            
            <a href="/work-groups" class="glass-card rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-6 block border border-white/20 hover:scale-105">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-r from-orange-100 to-orange-200 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 715.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Ministerios</h3>
                        <p class="text-sm text-gray-600">Gestionar equipos y asignaciones</p>
                    </div>
                </div>
            </a>
            <?php endif; ?>


        </div>
    </div>
    
    <script>
        // Animaciones de entrada
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