<h1>{{$companyName}} | Informe de error</h1>
<p>El paciente {{ $patient->getFullName() }} con DNI N° {{ $patient->profile->user->dni }} ha informado que no tiene deudas impagas o que hay un registro erróneo en cuanto a su tratamiento de {{ $treatment->name }}.
</p>
<h2>Datos del tratamiento:</h2>
<ul>
    <li>Nombre: {{ $treatment->name }}</li>
    <li>Prestador/a:
        {{ $treatment->medicalHistory->professionalProfile->getFullName() }}.
    </li>
    <li>Tipo de consulta: {{ $treatment->description }}</li>
    <li>Paciente: {{ $treatment->medicalHistory->patientProfile->getFullName() }}</li>
</ul>
<div class="text-center">
    <a href="{{ url('/treatments/'.$treatment->id) }}" class="btn bg-primary text-light">Controlar tratamiento</a>
</div>