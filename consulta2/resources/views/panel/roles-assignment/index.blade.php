@extends('layouts.app', ['activePage' => 'role_permissions', 'title' => $companyName.' | Roles y permisos', 'navName' => 'Configuración', 'activeButton' => 'laravel'])


@section('content')
  <div class="flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
      <div
        x-data="{ model: @if($modelKey) '{{$modelKey}}' @else 'initial' @endif }"
        x-init="$watch('model', value => value != 'initial' ? window.location = `?model=${value}` : '')"
        class="mt-4 align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200 p-4"
      >
        {{-- <span class="text-gray-700">Modelo de usuario al que asignarle permisos y roles</span>
        <label class="block w-3/12">
          <select class="form-select block w-full mt-1" x-model="model">
            <option value="initial" disabled selected>Select a user model</option>
            @foreach ($models as $model)
              <option value="{{$model}}">{{ucwords($model)}}</option>
            @endforeach
          </select>
        </label> --}}
        <button class="btn btn-blue" type="submit"><a href="{{ route('laratrust.roles.index') }}">Administrar Roles</a></button>
        <button class="btn btn-blue" type="submit"><a href="{{ route('laratrust.permissions.index') }}">Gestionar Permisos</a></button>
        <div class="flex mt-4 align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg ">
          <table class="min-w-full">
            <thead>
              <tr>
                <th class="th">Id</th>
                <th class="th">Nombre</th>
                <th class="th"># Roles</th>
                @if(config('laratrust.panel.assign_permissions_to_user'))<th class="th"># Permisos</th>@endif
                <th class="th"></th>
              </tr>
            </thead>
            <tbody class="bg-white">
              @foreach ($users as $user)
              <tr>
                <td class="td text-sm leading-5 text-gray-900">
                  {{$user->getKey()}}
                </td>
                <td class="td text-sm leading-5 text-gray-900">
                  {{$user->name . ' '. $user->lastname?? 'The model doesn\'t have a `name` attribute'}}
                </td>
                <td class="td text-sm leading-5 text-gray-900">
                  {{$user->roles_count}}
                </td>
                @if(config('laratrust.panel.assign_permissions_to_user'))
                <td class="td text-sm leading-5 text-gray-900">
                  {{$user->permissions_count}}
                </td>
                @endif
                <td class="flex justify-end px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                  <a
                    href="{{route('laratrust.roles-assignment.edit', ['roles_assignment' => $user->id, 'model' => $modelKey])}}"
                    class="text-blue-600 hover:text-blue-900"
                  >Editar</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @if ($modelKey)
          {{ $users->appends(['model' => $modelKey])->links('laratrust::panel.pagination') }}
        @endif

      </div>
    </div>
  </div>
@endsection
