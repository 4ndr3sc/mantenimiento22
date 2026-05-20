@extends('admin.index')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Gestión de Técnicos</h2>
    <table class="min-w-full bg-white">
        <thead><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th></tr></thead>
        <tbody>
        @foreach($users as $u)
            <tr id="user-{{ $u->id }}" class="border-b"><td class="px-4 py-2">{{ $u->name }}</td><td class="px-4 py-2">{{ $u->email }}</td>
            <td class="px-4 py-2" data-role>{{ $u->role }}</td>
            <td class="px-4 py-2">
                @if(auth()->user()->id !== $u->id)
                <div class="flex items-center justify-end gap-2">
                    <select id="role-select-{{ $u->id }}" class="bg-white text-sm rounded px-2 py-1 border">
                        <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>user</option>
                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>admin</option>
                    </select>
                    <button onclick="setRoleAdmin({{ $u->id }})" class="px-3 py-1 bg-blue-600 text-white rounded">Actualizar</button>
                </div>
                @endif
            </td></tr>
        @endforeach
        </tbody>
    </table>
</div>

    <script>
    function setRoleAdmin(userId) {
        const sel = document.getElementById(`role-select-${userId}`);
        if (!sel) return alert('Selector no encontrado');
        const role = sel.value;
        if (!confirm('Confirmar cambio de rol?')) return;
        fetch(`/admin/usuarios/${userId}/role`, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
            body: JSON.stringify({role})
        }).then(r => {
            if (!r.ok) throw new Error('Error');
            return r.json();
        }).then(j => {
            const row = document.getElementById(`user-${userId}`);
            if (row) row.querySelector('[data-role]').innerText = j.user.role;
            alert('Rol actualizado');
        }).catch(e => { console.error(e); alert('No se pudo actualizar'); });
    }
    </script>

@endsection
