{{-- Delete Account --}}
    <div class="card border-0 shadow radius-12">
        <div class="card-body p-4">

            <h4 class="fw-semibold mb-3 text-danger">Delete Account</h4>
            <p class="text-muted mb-4">
                Once your account is deleted, all data will be permanently removed.
            </p>

            <button class="btn btn-danger radius-12 px-4 py-2"
                x-data
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                Delete Account
            </button>

            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
                    @csrf
                    @method('delete')

                    <h4 class="fw-semibold mb-2">Are you sure?</h4>
                    <p class="text-muted mb-3">
                        Enter your password to confirm permanent account deletion.
                    </p>

                    <div class="icon-field mb-3">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input type="password" name="password"
                               class="form-control h-56-px bg-neutral-50 radius-12"
                               placeholder="Password">
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary"
                                x-on:click="$dispatch('close')">Cancel</button>

                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </x-modal>

        </div>
    </div>