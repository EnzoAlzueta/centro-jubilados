<x-app-layout>
    <div class="cointainer-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Perfil</h2>
        </div>

        <div class="row bg-white p-5 rounded-4 justify-content-evenly row-gap-5" >
            <div class="shadow rounded-4 p-4 col-md-5">
                @include('profile.partials.update-profile-information-form')

            </div>
            <div class="shadow rounded-4 p-4 col-md-5">
                @include('profile.partials.update-password-form')

            </div>
            <div class="shadow rounded-4 p-4">
                @include('profile.partials.delete-user-form')

            </div>
        </div>

    </div>
    
</x-app-layout>
