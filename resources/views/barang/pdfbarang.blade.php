<html>
    <head>
        <title>Cetak PDF</title>
    </head>
    <body>
        <style type="text/css">
            .table1 {
                font-family: sans-serif;
                color: #232323;
                border-collapse: collapse;
            }

            .table1, th, td {
                border: 1px solid #999;
                padding: 8px 20px;
            }
    </style>
    <h4 align="center">Laporan Data Barang</h4>
    <table class="table table-bordered table-striped table-bordered">
        <thead>
            <tr>
                <th style="width:1%">No.</th>
                <th style="width:5%">Nama Barang</th>
                <th style="width:5%">Harga</th>
                <th style="width:5%">Bahan</th>
                <th style="width:5%">Ukuran</th>
                <th style="width:5%">Stok</th>
                <th style="width:5%">Gambar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataBarang as $data)
            <tr>
                <td> {{ $loop->iteration }}</td>
                <td> {{ $data->nama_barang}}</td>
                <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                <td> {{ $data->bahan }} </td>
                <td> {{ $data->ukuran }}</td>
                <td> {{ $data->stock}}</td>
                <td>
                @if($data->gambar)
                    <img src="{{ asset('storage/'.$data->gambar) }}" width="80">
                @else
                    -
                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script type="text/javascript">
    window.print();
    </script>
    </body>
</html>