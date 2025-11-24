<section>
    <header>
        <h3>Eliminar cuenta</h3>
        <p class="small">Eliminar tu cuenta borrará permanentemente todos tus datos. Esta acción no se puede deshacer.</p>
    </header>

    <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}" style="margin-top:12px;">
        @csrf
        @method('delete')

        <div style="margin-bottom:10px;">
            <label class="label">Contraseña (confirmación)</label>
            <input type="password" name="password" placeholder="Ingresa tu contraseña para confirmar" />
            @if($errors->userDeletion && $errors->userDeletion->has('password'))
                <div class="small" style="color:#c0392b">{{ $errors->userDeletion->first('password') }}</div>
            @endif
        </div>

        <div>
            <button type="submit" class="boton_borde" style="border-color:#c0392b;color:#c0392b;">Eliminar cuenta</button>
        </div>
    </form>
</section>
