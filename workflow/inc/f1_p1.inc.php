<h4>Solicitud de Inscripción</h4>

<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">Gestión Académica</label>

        <input type="number" class="form-control">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Semestre</label>

        <select name="semestre" class="form-control">
            <option value="1">Primer Semestre</option>
            <option value="2">Segundo Semestre</option>
        </select>
    </div>

</div>

<div class="mb-3">

    <label class="form-label">Observaciones</label>

    <textarea
        name="observaciones"
        class="form-control"
        rows="4"></textarea>

</div>

<div class="alert alert-info">

    Al enviar la solicitud el sistema verificará:

    <ul class="mb-0">
        <li>Estado académico</li>
        <li>Matrícula vigente</li>
        <li>Habilitación para inscripción</li>
    </ul>

</div>