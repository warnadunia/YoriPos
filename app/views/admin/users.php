<div class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Kelola Staf & Akses (RBAC)</h2>
                <p class="text-sm text-slate-500">Atur jabatan, kunci akses (permissions), dan akun login staf.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- MANAJEMEN JABATAN (ROLES) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">Jabatan & Hak Akses</h3>
                    <button onclick="openRoleModal()" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">
                        + Tambah Jabatan
                    </button>
                </div>
                <div class="p-5 flex-1 overflow-y-auto">
                    <div id="rolesContainer" class="space-y-3">
                        <p class="text-center text-slate-400 text-sm py-4">Memuat data jabatan...</p>
                    </div>
                </div>
            </div>

            <!-- MANAJEMEN PENGGUNA (USERS) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">Akun Login Staf</h3>
                    <button onclick="openUserModal()" class="bg-emerald-100 text-emerald-700 hover:bg-emerald-200 px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">
                        + Tambah Staf
                    </button>
                </div>
                <div class="p-5 flex-1 overflow-y-auto">
                    <div id="usersContainer" class="space-y-3">
                        <p class="text-center text-slate-400 text-sm py-4">Memuat data staf...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL JABATAN (ROLE) -->
<div id="roleModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl p-6 transform transition-all scale-95" id="roleModalContent">
        <h3 id="roleModalTitle" class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 border-b pb-3">Tambah Jabatan</h3>
        <input type="hidden" id="roleId">
        
        <div class="space-y-4 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Jabatan</label>
                <input type="text" id="roleName" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:border-indigo-500" placeholder="Misal: Kasir Senior">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Contreng Hak Akses Ruangan:</label>
                <div class="grid grid-cols-2 gap-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="pos" class="perm-chk w-4 h-4 text-indigo-600"> <span class="text-sm font-bold">Akses POS</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="dashboard" class="perm-chk w-4 h-4 text-indigo-600"> <span class="text-sm font-bold">Dashboard</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="products" class="perm-chk w-4 h-4 text-indigo-600"> <span class="text-sm font-bold">Master Produk</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="stocks" class="perm-chk w-4 h-4 text-indigo-600"> <span class="text-sm font-bold">Stok Masuk</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="transactions" class="perm-chk w-4 h-4 text-indigo-600"> <span class="text-sm font-bold">Transaksi & Order</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="settings" class="perm-chk w-4 h-4 text-indigo-600"> <span class="text-sm font-bold">Pengaturan</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="users" class="perm-chk w-4 h-4 text-indigo-600"> <span class="text-sm font-bold">Kelola Akses</span></label>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="closeRoleModal()" class="flex-1 bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl">Batal</button>
            <button onclick="saveRole()" id="btnSaveRole" class="flex-1 bg-indigo-600 text-white font-bold py-2.5 rounded-xl">Simpan</button>
        </div>
    </div>
</div>

<!-- MODAL AKUN STAF (USER) -->
<div id="userModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity opacity-0">
    <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl p-6 transform transition-all scale-95" id="userModalContent">
        <h3 id="userModalTitle" class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4 border-b pb-3">Tambah Staf</h3>
        <input type="hidden" id="userId">
        
        <div class="space-y-3 mb-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Asli</label>
                <input type="text" id="userName" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none" placeholder="Budi Santoso">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Username Login</label>
                <input type="text" id="userUsername" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-mono" placeholder="budi123">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Password</label>
                <input type="password" id="userPassword" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none" placeholder="Kosongkan jika tidak ingin diubah">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Jabatan / Akses</label>
                <select id="userRole" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 outline-none font-bold text-indigo-700"></select>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="closeUserModal()" class="flex-1 bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl">Batal</button>
            <button onclick="saveUser()" id="btnSaveUser" class="flex-1 bg-emerald-500 text-white font-bold py-2.5 rounded-xl">Simpan Staf</button>
        </div>
    </div>
</div>

<script>
    let rolesData = [];

    async function loadData() {
        await loadRoles();
        await loadUsers();
    }

    // --- ROLES LOGIC ---
    async function loadRoles() {
        const res = await fetch('/yoripos/api/?action=get_roles');
        const result = await res.json();
        if (result.status === 'success') {
            rolesData = result.data;
            const container = document.getElementById('rolesContainer');
            const select = document.getElementById('userRole');
            container.innerHTML = ''; select.innerHTML = '<option value="">-- Pilih Jabatan --</option>';

            rolesData.forEach(r => {
                const perms = JSON.parse(r.permissions || '[]');
                select.innerHTML += `<option value="${r.id}">${r.name}</option>`;
                container.innerHTML += `
                    <div class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl flex justify-between items-center group hover:border-indigo-400 transition-colors">
                        <div>
                            <p class="font-bold text-slate-800 dark:text-slate-200">${r.name}</p>
                            <p class="text-xs text-slate-400 mt-1 uppercase font-mono">${perms.length} Akses Dibuka</p>
                        </div>
                        <button onclick='editRole(${JSON.stringify(r)})' class="p-2 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                    </div>`;
            });
        }
    }

    function openRoleModal() {
        document.getElementById('roleModalTitle').innerText = 'Tambah Jabatan';
        document.getElementById('roleId').value = '';
        document.getElementById('roleName').value = '';
        document.querySelectorAll('.perm-chk').forEach(c => c.checked = false);
        document.getElementById('roleModal').classList.remove('hidden');
        setTimeout(() => { document.getElementById('roleModal').classList.remove('opacity-0'); document.getElementById('roleModalContent').classList.remove('scale-95'); }, 10);
    }
    
    function editRole(role) {
        document.getElementById('roleModalTitle').innerText = 'Edit Jabatan';
        document.getElementById('roleId').value = role.id;
        document.getElementById('roleName').value = role.name;
        const perms = JSON.parse(role.permissions || '[]');
        document.querySelectorAll('.perm-chk').forEach(c => c.checked = perms.includes(c.value));
        document.getElementById('roleModal').classList.remove('hidden');
        setTimeout(() => { document.getElementById('roleModal').classList.remove('opacity-0'); document.getElementById('roleModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('opacity-0'); document.getElementById('roleModalContent').classList.add('scale-95');
        setTimeout(() => { document.getElementById('roleModal').classList.add('hidden'); }, 300);
    }

    async function saveRole() {
        const id = document.getElementById('roleId').value;
        const name = document.getElementById('roleName').value;
        const perms = Array.from(document.querySelectorAll('.perm-chk:checked')).map(c => c.value);
        if(!name) { Swal.fire('Peringatan', 'Nama jabatan wajib diisi!', 'warning'); return; }

        const btn = document.getElementById('btnSaveRole'); btn.disabled = true; btn.innerText = '...';
        await fetch('/yoripos/api/?action=save_role', { method: 'POST', body: JSON.stringify({ id, name, permissions: perms }) });
        closeRoleModal(); await loadRoles(); btn.disabled = false; btn.innerText = 'Simpan';
    }

    // --- USERS LOGIC ---
    async function loadUsers() {
        const res = await fetch('/yoripos/api/?action=get_users');
        const result = await res.json();
        if (result.status === 'success') {
            const container = document.getElementById('usersContainer'); container.innerHTML = '';
            result.data.forEach(u => {
                container.innerHTML += `
                    <div class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl flex justify-between items-center group hover:border-emerald-400 transition-colors">
                        <div>
                            <p class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">${u.name} <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2 py-0.5 rounded font-black uppercase">${u.role_name || 'Tanpa Akses'}</span></p>
                            <p class="text-xs text-slate-500 mt-1 font-mono">@${u.username}</p>
                        </div>
                        <button onclick='editUser(${JSON.stringify(u)})' class="p-2 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                    </div>`;
            });
        }
    }

    function openUserModal() {
        document.getElementById('userModalTitle').innerText = 'Tambah Staf';
        document.getElementById('userId').value = '';
        document.getElementById('userName').value = '';
        document.getElementById('userUsername').value = '';
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').placeholder = 'Wajib diisi untuk akun baru';
        document.getElementById('userRole').value = '';
        document.getElementById('userModal').classList.remove('hidden');
        setTimeout(() => { document.getElementById('userModal').classList.remove('opacity-0'); document.getElementById('userModalContent').classList.remove('scale-95'); }, 10);
    }
    
    function editUser(user) {
        document.getElementById('userModalTitle').innerText = 'Edit Staf';
        document.getElementById('userId').value = user.id;
        document.getElementById('userName').value = user.name;
        document.getElementById('userUsername').value = user.username;
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').placeholder = 'Kosongkan jika tidak ingin ubah password';
        document.getElementById('userRole').value = user.role_id;
        document.getElementById('userModal').classList.remove('hidden');
        setTimeout(() => { document.getElementById('userModal').classList.remove('opacity-0'); document.getElementById('userModalContent').classList.remove('scale-95'); }, 10);
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('opacity-0'); document.getElementById('userModalContent').classList.add('scale-95');
        setTimeout(() => { document.getElementById('userModal').classList.add('hidden'); }, 300);
    }

    async function saveUser() {
        const id = document.getElementById('userId').value;
        const name = document.getElementById('userName').value;
        const username = document.getElementById('userUsername').value;
        const password = document.getElementById('userPassword').value;
        const role_id = document.getElementById('userRole').value;

        if(!name || !username || !role_id) { Swal.fire('Peringatan', 'Lengkapi semua field!', 'warning'); return; }
        if(!id && !password) { Swal.fire('Peringatan', 'Password akun baru wajib diisi!', 'warning'); return; }

        const btn = document.getElementById('btnSaveUser'); btn.disabled = true; btn.innerText = '...';
        await fetch('/yoripos/api/?action=save_user', { method: 'POST', body: JSON.stringify({ id, name, username, password, role_id }) });
        closeUserModal(); await loadUsers(); btn.disabled = false; btn.innerText = 'Simpan Staf';
    }

    document.addEventListener('DOMContentLoaded', loadData);
</script>