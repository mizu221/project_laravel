<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joem Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        cyberdark: '#0a0b0e',
                        cybercard: '#12151c',
                        cyberborder: '#1f293d',
                        neonred: '#ff2a5f',
                        neonblue: '#00f0ff',
                        neonglow: '#ff2a5f33'
                    },
                    fontFamily: {
                        display: ['Outfit', 'sans-serif'],
                        body: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0a0b0e;
            color: #f3f4f6;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(255, 42, 95, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(0, 240, 255, 0.08) 0%, transparent 40%);
            background-attachment: fixed;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0a0b0e; }
        ::-webkit-scrollbar-thumb { background: #1f293d; border-radius: 999px; border: 1px solid rgba(0, 240, 255, 0.2); }
        ::-webkit-scrollbar-thumb:hover { background: #ff2a5f; }

        /* Ambient Lighting Blobs */
        .ambient-blob {
            position: fixed;
            border-radius: 999px;
            filter: blur(120px);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }

        /* Cyberpunk Grid Background */
        .cyber-grid {
            position: fixed;
            inset: 0;
            background-image: linear-gradient(to right, rgba(31, 41, 61, 0.15) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(31, 41, 61, 0.15) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
            pointer-events: none;
        }

        /* Glassmorphism Panels */
        .glass-panel {
            background: rgba(18, 21, 28, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(31, 41, 61, 0.8);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .glass-panel-glow {
            background: rgba(18, 21, 28, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 42, 95, 0.3);
            box-shadow: 0 0 25px rgba(255, 42, 95, 0.1), inset 0 0 15px rgba(255, 42, 95, 0.05);
        }

        .glass-panel-blue {
            background: rgba(18, 21, 28, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(0, 240, 255, 0.3);
            box-shadow: 0 0 25px rgba(0, 240, 255, 0.1), inset 0 0 15px rgba(0, 240, 255, 0.05);
        }

        /* Task Card Hover Dominance & Balance */
        #taskGrid .task-card {
            transition: transform 0.3s cubic-bezier(.2,.8,.2,1), box-shadow 0.3s ease, opacity 0.3s ease, filter 0.3s ease;
        }
        #taskGrid:hover .task-card {
            transform: scale(0.96);
            opacity: 0.55;
            filter: saturate(0.7);
        }
        #taskGrid .task-card:hover {
            transform: scale(1.05) translateY(-4px);
            opacity: 1;
            filter: saturate(1.2);
            border-color: rgba(255, 42, 95, 0.6);
            box-shadow: 0 15px 35px -10px rgba(255, 42, 95, 0.35), 0 0 15px rgba(0, 240, 255, 0.2);
            z-index: 10;
            position: relative;
        }

        .cyber-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cyber-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(255, 42, 95, 0.4);
        }
        .cyber-btn:active {
            transform: translateY(0) scale(0.97);
        }

        @keyframes pulse-neon {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.8; }
        }
        .animate-pulse-neon {
            animation: pulse-neon 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row relative overflow-x-hidden">

    <!-- Ambient Background Lighting & Grid -->
    <div class="ambient-blob w-[500px] h-[500px] bg-neonred -top-32 -left-32 animate-pulse-neon"></div>
    <div class="ambient-blob w-[500px] h-[500px] bg-neonblue top-1/2 -right-32 animate-pulse-neon" style="animation-delay: 2s;"></div>
    <div class="cyber-grid"></div>

    <!-- Sidebar Navigation -->
    <aside class="relative z-20 w-full md:w-72 glass-panel border-b md:border-b-0 md:border-r border-cyberborder flex flex-col justify-between shrink-0 md:h-screen md:sticky md:top-0">
        <div>
            <!-- App Branding -->
            <div class="h-24 flex items-center px-6 border-b border-cyberborder gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-neonred to-rose-700 flex items-center justify-center text-white shadow-lg shadow-neonred/30 shrink-0 border border-red-400/30">
                    <i class="fa-solid fa-bolt text-lg"></i>
                </div>
                <div>
                    <h1 class="font-display font-extrabold text-2xl tracking-wider text-white uppercase flex items-center gap-1.5">
                        Joem<span class="text-neonred">.</span>
                    </h1>
                    <span class="text-[11px] text-neonblue font-semibold tracking-widest uppercase">Task Matrix v2.6</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-2">
                <a href="#" onclick="filterTasks('all')" id="nav-all" class="flex items-center gap-3 px-5 py-3 rounded-xl text-sm font-semibold bg-gradient-to-r from-neonred to-rose-700 text-white shadow-lg shadow-neonred/25 border border-red-500/30 transition-all">
                    <span class="w-6 h-6 rounded-lg bg-black/30 flex items-center justify-center"><i class="fa-solid fa-layer-group text-xs text-white"></i></span> All Tasks <span id="count-all" class="ml-auto px-2 py-0.5 rounded-md bg-black/40 text-xs font-bold text-white">0</span>
                </a>
                <a href="#" onclick="filterTasks('pending')" id="nav-pending" class="flex items-center gap-3 px-5 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 border border-transparent transition-all">
                    <span class="w-6 h-6 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center"><i class="fa-solid fa-clock text-xs"></i></span> Pending <span id="count-pending" class="ml-auto px-2 py-0.5 rounded-md bg-white/5 text-xs font-semibold text-gray-300">0</span>
                </a>
                <a href="#" onclick="filterTasks('completed')" id="nav-completed" class="flex items-center gap-3 px-5 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 border border-transparent transition-all">
                    <span class="w-6 h-6 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center"><i class="fa-solid fa-circle-check text-xs"></i></span> Completed <span id="count-completed" class="ml-auto px-2 py-0.5 rounded-md bg-white/5 text-xs font-semibold text-gray-300">0</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Profile -->
        <div class="p-4 border-t border-cyberborder">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/[0.03] border border-cyberborder">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-neonblue to-blue-700 text-white flex items-center justify-center font-bold text-sm shrink-0 border border-cyan-400/30 shadow-md shadow-neonblue/20">
                    JM
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">Joem Task Manager</p>
                    <p class="text-[11px] text-neonblue truncate font-mono">System Operational</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="relative z-10 flex-1 flex flex-col min-w-0">

        <!-- Sticky Top Header Bar -->
        <header class="h-24 glass-panel border-b border-cyberborder px-6 md:px-10 flex items-center justify-between gap-4 sticky top-0 z-20">
            <div class="flex items-center gap-4 flex-1 max-w-xl">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-neonblue">
                        <i class="fa-solid fa-terminal text-sm"></i>
                    </span>
                    <input type="text" id="searchInput" oninput="handleSearch()" placeholder="Search active protocols & tasks..." class="w-full bg-black/40 border border-cyberborder rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-neonblue focus:ring-1 focus:ring-neonblue transition-all">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <select id="priorityFilter" onchange="applyFilters()" class="bg-black/40 border border-cyberborder rounded-xl px-4 py-2.5 text-xs text-gray-300 focus:outline-none focus:border-neonred transition-all hidden sm:block">
                    <option value="all">All Priorities</option>
                    <option value="High">High Priority</option>
                    <option value="Medium">Medium Priority</option>
                    <option value="Low">Low Priority</option>
                </select>
                <button onclick="openCreateModal()" class="cyber-btn w-12 h-12 rounded-xl bg-gradient-to-br from-neonred to-rose-700 text-white flex items-center justify-center shadow-lg shadow-neonred/30 border border-red-400/30" title="Initialize New Task">
                    <i class="fa-solid fa-plus text-sm"></i>
                </button>
            </div>
        </header>

        <!-- Dynamic Flash Notification Banner -->
        <div id="flashMessage" class="hidden mx-6 md:mx-10 mt-6 p-4 rounded-xl glass-panel flex items-center gap-3 text-sm shadow-xl transition-all duration-300">
            <i id="flashIcon" class="fa-solid text-base"></i>
            <span id="flashText" class="font-medium text-white"></span>
        </div>

        <!-- Dashboard Content Container -->
        <div class="p-6 md:p-10 space-y-8 flex-1 max-w-7xl w-full mx-auto">

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="glass-panel rounded-2xl p-5 flex items-center gap-4 border-l-4 border-l-neonred">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-neonred/20 to-red-900/30 text-neonred border border-red-500/30 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold mb-0.5">Total Protocols</p>
                        <h3 id="statTotal" class="text-2xl font-bold font-display text-white">0</h3>
                    </div>
                </div>
                <div class="glass-panel rounded-2xl p-5 flex items-center gap-4 border-l-4 border-l-amber-400">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500/20 to-amber-900/30 text-amber-400 border border-amber-500/30 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold mb-0.5">Pending Execution</p>
                        <h3 id="statPending" class="text-2xl font-bold font-display text-white">0</h3>
                    </div>
                </div>
                <div class="glass-panel rounded-2xl p-5 flex items-center gap-4 border-l-4 border-l-emerald-400">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-900/30 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold mb-0.5">Completed Systems</p>
                        <h3 id="statCompleted" class="text-2xl font-bold font-display text-white">0</h3>
                    </div>
                </div>
            </div>

            <!-- Task Registry Section -->
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-display font-bold text-xl text-white flex items-center gap-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-neonred shadow-[0_0_10px_#ff2a5f]"></span>
                        Task Registry 
                        <span id="currentFilterBadge" class="text-xs px-3 py-1 rounded-lg bg-black/40 text-neonblue border border-neonblue/30 font-mono font-medium">All Tasks</span>
                    </h2>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="hidden py-24 text-center border border-dashed border-cyberborder rounded-2xl glass-panel">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-black/40 border border-cyberborder flex items-center justify-center text-neonblue text-2xl shadow-[0_0_15px_rgba(0,240,255,0.1)]">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="font-display font-bold text-base text-white">No active tasks detected</h3>
                    <p class="text-xs text-gray-400 mt-1">Initialize a new task protocol using the plus button above.</p>
                </div>

                <!-- Task Grid -->
                <div id="taskGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Injected dynamically via JS -->
                </div>
            </div>

        </div>
    </main>

    <!-- Add/Edit Task Modal Dialog -->
    <div id="taskModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md hidden opacity-0 transition-opacity duration-200">
        <div class="glass-panel-glow w-full max-w-lg rounded-3xl overflow-hidden transform scale-95 transition-transform duration-200 shadow-2xl" id="modalCard">
            <div class="flex items-center justify-between px-6 py-5 border-b border-cyberborder bg-black/40">
                <h3 id="modalTitle" class="font-display font-bold text-white text-base flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-neonred/20 border border-neonred/40 flex items-center justify-center text-neonred"><i class="fa-solid fa-microchip text-xs"></i></span> Initialize New Protocol
                </h3>
                <button onclick="closeModal()" class="w-9 h-9 rounded-xl bg-black/40 border border-cyberborder text-gray-400 hover:text-white flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <form id="taskForm" onsubmit="handleFormSubmit(event)" class="p-6 space-y-4">
                <input type="hidden" id="taskId">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Task Designation *</label>
                    <input type="text" id="taskTitle" required placeholder="e.g., Cyber-grid optimization..." class="w-full bg-black/40 border border-cyberborder rounded-xl px-5 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-neonred focus:ring-1 focus:ring-neonred transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">System Parameters / Notes</label>
                    <textarea id="taskDesc" rows="3" placeholder="Add detailed operational steps or checklist..." class="w-full bg-black/40 border border-cyberborder rounded-xl px-5 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-neonred focus:ring-1 focus:ring-neonred transition-all resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Threat Priority</label>
                        <select id="taskPriority" class="w-full bg-black/40 border border-cyberborder rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-neonred transition-all">
                            <option value="Low" class="bg-cyberdark">Low Priority</option>
                            <option value="Medium" selected class="bg-cyberdark">Medium Priority</option>
                            <option value="High" class="bg-cyberdark">High Priority</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Deadline *</label>
                        <input type="date" id="taskDueDate" required class="w-full bg-black/40 border border-cyberborder rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-neonred transition-all">
                    </div>
                </div>
                <div class="pt-4 border-t border-cyberborder flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl text-gray-400 hover:text-white font-medium text-sm transition-colors">Abort</button>
                    <button type="submit" class="cyber-btn px-6 py-2.5 rounded-xl bg-gradient-to-r from-neonred to-rose-700 text-white font-semibold text-sm shadow-lg shadow-neonred/30 border border-red-500/40">Deploy Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Application Logic Script -->
    <script>
        let tasks = [];
        let currentFilter = 'all';
        let currentSearchQuery = '';

        window.onload = function() {
            const todayStr = new Date().toISOString().split('T')[0];
            document.getElementById('taskDueDate').min = todayStr;
            renderApp();
        };

        function renderApp() {
            updateStats();
            renderTaskGrid();
            updateSidebarCounts();
        }

        function updateStats() {
            document.getElementById('statTotal').innerText = tasks.length;
            document.getElementById('statPending').innerText = tasks.filter(t => t.status === 'pending').length;
            document.getElementById('statCompleted').innerText = tasks.filter(t => t.status === 'completed').length;
        }

        function updateSidebarCounts() {
            document.getElementById('count-all').innerText = tasks.length;
            document.getElementById('count-pending').innerText = tasks.filter(t => t.status === 'pending').length;
            document.getElementById('count-completed').innerText = tasks.filter(t => t.status === 'completed').length;
        }

        function filterTasks(filter) {
            currentFilter = filter;

            const activeClass = "flex items-center gap-3 px-5 py-3 rounded-xl text-sm font-semibold bg-gradient-to-r from-neonred to-rose-700 text-white shadow-lg shadow-neonred/25 border border-red-500/30 transition-all";
            const inactiveClass = "flex items-center gap-3 px-5 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 border border-transparent transition-all";

            ['all', 'pending', 'completed'].forEach(f => {
                const el = document.getElementById(`nav-${f}`);
                el.className = (f === filter) ? activeClass : inactiveClass;
            });

            const badgeNames = { all: 'All Tasks', pending: 'Pending Tasks', completed: 'Completed Tasks' };
            document.getElementById('currentFilterBadge').innerText = badgeNames[filter];

            renderTaskGrid();
        }

        function handleSearch() {
            currentSearchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
            renderTaskGrid();
        }

        function applyFilters() {
            renderTaskGrid();
        }

        function getFilteredTasks() {
            const priorityVal = document.getElementById('priorityFilter').value;
            return tasks.filter(task => {
                if (currentFilter !== 'all' && task.status !== currentFilter) return false;
                if (priorityVal !== 'all' && task.priority !== priorityVal) return false;
                if (currentSearchQuery && !task.title.toLowerCase().includes(currentSearchQuery) && !task.description.toLowerCase().includes(currentSearchQuery)) return false;
                return true;
            });
        }

        function renderTaskGrid() {
            const filtered = getFilteredTasks();
            const grid = document.getElementById('taskGrid');
            const emptyState = document.getElementById('emptyState');

            grid.innerHTML = '';

            if (filtered.length === 0) {
                emptyState.classList.remove('hidden');
                grid.classList.add('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
                grid.classList.remove('hidden');
            }

            filtered.forEach(task => {
                let priorityColor = 'text-neonblue';
                let priorityBg = 'bg-cyan-500/10 border-cyan-500/30';
                if (task.priority === 'High') { priorityColor = 'text-neonred'; priorityBg = 'bg-red-500/10 border-red-500/30'; }
                if (task.priority === 'Medium') { priorityColor = 'text-amber-400'; priorityBg = 'bg-amber-500/10 border-amber-500/30'; }
                if (task.priority === 'Low') { priorityColor = 'text-emerald-400'; priorityBg = 'bg-emerald-500/10 border-emerald-500/30'; }

                const isCompleted = task.status === 'completed';
                const statusBadge = isCompleted
                    ? `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30"><i class="fa-solid fa-check text-[10px]"></i> Completed</span>`
                    : `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30"><i class="fa-solid fa-clock text-[10px]"></i> Pending</span>`;

                const card = document.createElement('div');
                card.className = "task-card glass-panel rounded-2xl p-5 flex flex-col justify-between";
                card.innerHTML = `
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span class="w-8 h-8 rounded-xl ${priorityBg} ${priorityColor} flex items-center justify-center text-xs border"><i class="fa-solid fa-shield"></i></span>
                            ${statusBadge}
                        </div>
                        <h4 class="font-display font-semibold text-base text-white mb-1.5 ${isCompleted ? 'line-through text-gray-500' : ''}">${escapeHtml(task.title)}</h4>
                        <p class="text-xs text-gray-400 line-clamp-2">${escapeHtml(task.description || 'No parameters defined.')}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-neonblue mb-4 pt-3 border-t border-cyberborder font-mono">
                            <i class="fa-regular fa-calendar"></i> DUE: ${task.dueDate}
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="toggleTaskStatus('${task.id}')" title="${isCompleted ? 'Reopen' : 'Complete'}" class="cyber-btn w-10 h-10 rounded-xl bg-black/40 border border-cyberborder text-neonblue hover:bg-neonblue/10 flex items-center justify-center">
                                <i class="fa-solid ${isCompleted ? 'fa-rotate-left' : 'fa-check'} text-xs"></i>
                            </button>
                            <button onclick="openEditModal('${task.id}')" title="Edit" class="cyber-btn w-10 h-10 rounded-xl bg-black/40 border border-cyberborder text-gray-300 hover:bg-white/10 flex items-center justify-center">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button onclick="deleteTask('${task.id}')" title="Delete" class="cyber-btn w-10 h-10 rounded-xl bg-black/40 border border-cyberborder text-gray-400 hover:bg-rose-500/20 hover:text-neonred hover:border-red-500/40 flex items-center justify-center">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        function openModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function openCreateModal() {
            document.getElementById('taskId').value = '';
            document.getElementById('taskForm').reset();
            document.getElementById('modalTitle').innerHTML = '<span class="w-8 h-8 rounded-xl bg-neonred/20 border border-neonred/40 flex items-center justify-center text-neonred"><i class="fa-solid fa-microchip text-xs"></i></span> Initialize New Protocol';
            openModal();
        }

        function openEditModal(id) {
            const task = tasks.find(t => t.id === id);
            if (!task) return;

            document.getElementById('taskId').value = task.id;
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskDesc').value = task.description;
            document.getElementById('taskPriority').value = task.priority;
            document.getElementById('taskDueDate').value = task.dueDate;
            document.getElementById('modalTitle').innerHTML = '<span class="w-8 h-8 rounded-xl bg-neonblue/20 border border-neonblue/40 flex items-center justify-center text-neonblue"><i class="fa-solid fa-pen text-xs"></i></span> Reconfigure Protocol';
            openModal();
        }

        function closeModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            const id = document.getElementById('taskId').value;
            const title = document.getElementById('taskTitle').value.trim();
            const description = document.getElementById('taskDesc').value.trim();
            const priority = document.getElementById('taskPriority').value;
            const dueDate = document.getElementById('taskDueDate').value;

            if (!title || !dueDate) return;

            if (id) {
                tasks = tasks.map(t => t.id === id ? { ...t, title, description, priority, dueDate } : t);
                showFlash('Protocol reconfigured successfully!', 'success');
            } else {
                const newTask = {
                    id: Date.now().toString(),
                    title, description, priority, dueDate,
                    status: 'pending'
                };
                tasks.unshift(newTask);
                showFlash('New protocol deployed!', 'success');
            }

            closeModal();
            renderApp();
        }

        function toggleTaskStatus(id) {
            tasks = tasks.map(t => {
                if (t.id === id) {
                    const newStatus = t.status === 'completed' ? 'pending' : 'completed';
                    showFlash(`Protocol status updated to: ${newStatus}`, 'success');
                    return { ...t, status: newStatus };
                }
                return t;
            });
            renderApp();
        }

        function deleteTask(id) {
            if (confirm('Are you sure you want to terminate this protocol?')) {
                tasks = tasks.filter(t => t.id !== id);
                showFlash('Protocol purged from system.', 'error');
                renderApp();
            }
        }

        function showFlash(message, type) {
            const flash = document.getElementById('flashMessage');
            const text = document.getElementById('flashText');
            const icon = document.getElementById('flashIcon');

            text.innerText = message;
            if (type === 'success') {
                flash.className = "mx-6 md:mx-10 mt-6 p-4 rounded-xl glass-panel text-emerald-400 border border-emerald-500/30 flex items-center gap-3 text-sm shadow-xl transition-all duration-300";
                icon.className = "fa-solid fa-circle-check text-base text-emerald-400";
            } else {
                flash.className = "mx-6 md:mx-10 mt-6 p-4 rounded-xl glass-panel text-neonred border border-red-500/30 flex items-center gap-3 text-sm shadow-xl transition-all duration-300";
                icon.className = "fa-solid fa-triangle-exclamation text-base text-neonred";
            }

            flash.classList.remove('hidden');
            setTimeout(() => {
                flash.classList.add('hidden');
            }, 3500);
        }

        function escapeHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>
</html>