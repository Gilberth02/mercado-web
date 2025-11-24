<section>
    <header>
        <h3>Cambiar contraseña</h3>
        <p class="small">Usa una contraseña segura y fácil de recordar solo para ti.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="perfil-form" style="margin-top:12px;">
        @csrf
        @method('put')

        <div style="margin-bottom:10px;">
            <label class="label">Contraseña actual</label>
            <input type="password" name="current_password" autocomplete="current-password" />
            @if($errors->updatePassword && $errors->updatePassword->has('current_password'))
                <div class="small" style="color:#c0392b">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <div style="margin-bottom:10px;">
            <label class="label">Nueva contraseña</label>
            <input type="password" name="password" autocomplete="new-password" />
            @if($errors->updatePassword && $errors->updatePassword->has('password'))
                <div class="small" style="color:#c0392b">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <div style="margin-bottom:10px;">
            <label class="label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" autocomplete="new-password" />
            @if($errors->updatePassword && $errors->updatePassword->has('password_confirmation'))
                <div class="small" style="color:#c0392b">{{ $errors->updatePassword->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="perfil-actions">
            <button class="boton" type="submit">Guardar</button>

            @if (session('status') === 'password-updated')
                <span class="small" style="color:#2d8659;">Contraseña actualizada.</span>
            @endif
        </div>
    </form>
</section>
