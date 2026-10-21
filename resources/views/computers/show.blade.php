<div class="container my-5">
    <h2 class="mb-4 text-success">Detalles de Computer</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Number</th>
                    <th scope="col">Brand</th>
                    
                </thead>
            <tbody>
                


                <tr>
                    <td>{{ $computer['id'] }}</td>
                    <td>{{ $computer['number'] }}</td>
                    <td>{{ $computer['brand'] }}</td>
                  
                </tr>
            </tbody>
        </table>
    </div>
</div>