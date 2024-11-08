<!DOCTYPE html>
<html>
<head>
    <title>{{$roadLetter->number}}</title>
    <style>
    td {
        border-color: black;
            padding: 10px;
            margin: 0
        }
        table {
            width: 100%;
            color: black;
        }

        table:first-child{
            border-bottom: 0
        }

        table:last-child{
            border-top: 0
        }
    </style>
</head>
<body>
    <table cellspacing="0" cellpadding="0" border="1">
        <tr>
            <td colspan="1" rowspan="3" style="text-align: center">
                <h5 style="margin: 0; border-bottom: 3px solid black;">{{strtoupper($settingOffice->name)}}</h5>
                <img src="{{public_path('assets/media/logos/blueLogo.svg')}}" width="150" alt="HOB Logo" style="margin: 10px">
                {{-- <img src="{{asset('assets/media/logos/blueLogo.svg')}}" width="150" alt="HOB Logo" style="margin: 10px"> --}}
                <p style="margin: 0; font-size: 10px;">Agen Penyalur BBM</p> <br>
                <p style="margin: 0; font-size: 10px;">{{$settingOffice->address}}</p>
                <p style="margin: 0; font-size: 10px;">Phone: +62 31 3539211, 3539222</p>
                <p style="margin: 0; font-size: 10px;">Fax: +62 31 3529476</p>
                <p style="margin: 0; font-size: 10px;">Email: service@hobbunker.com</p>
            </td>
            <td colspan="1" rowspan="3" style="text-align:center;">
                <h5 style="margin: 0">DAFTAR PENGIRIMAN KEBUTUHAN KAPAL</h5>
                <h5 style="margin: 0">(SURAT JALAN)</h5>
            </td>
            <td colspan="2" style="position: relative;">
                <p style="margin: 10px; font-size: 10px; position: relative; top: -10px; left: -10px">No.</p>
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="1" style="text-align: center">HO/SH</td>
            <td colspan="1" style="text-align: center">SS</td>
        </tr>
        <tr>
            <td colspan="1" style="width: 200px;">
                <p style="margin: 0">Nama Kapal: {{$roadLetter->requestItem->masterShip->name}}</p>
            </td>
            <td colspan="2">
                <p style="margin: 0">Tanggal Pengiriman: {{$roadLetter->formatted_created_at}}</p>
            </td>
            <td colspan="1">
                <p style="margin: 0">Hal: &nbsp;  &nbsp;</p>
            </td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="0" border="1">
        <tr>
            <td colspan="1" style="text-align: center;">
                <p style="margin: 0">NO</p>
            </td>
            <td colspan="1" style="text-align: center; width: 475px;">
                <p style="margin: 0">JENIS BARANG</p>
            </td>
            <td colspan="2" style="text-align: center; width: 100px;">
                <p style="margin: 0">JUMLAH</p>
            </td>
        </tr>
        @foreach ($roadLetter->requestItem->acceptepRequestItemDetails as $key => $requestItemDetail)
        <tr>
            <td colspan="1" style="text-align: center;">
                <p style="margin: 0">{{$key+1}}</p>
            </td>
            <td colspan="1" style="text-align: center; width: 475px;">
                <p style="margin: 0">
                    {{$requestItemDetail->masterItem->name}}
                </p>
                <p style="margin: 0">
                    Bagian: {{$requestItemDetail->position}}
                </p>
            </td>
            <td colspan="2" style="text-align: center; width: 100px;">
                <p style="margin: 0">
                    {{$requestItemDetail->qty}}
                </p>
            </td>
        </tr>
        @endforeach
    </table>

    <div style="text-align: left; margin-top: 5px; float: left">
        <p>Disetujui oleh:</p>
        <p>{{Auth::user()->name}}</p>
    </div>
    <div style="text-align: right; margin-top: 5px">
        <img src="{{$roadLetter->barcode}}">
    </div>
</body>
</html>
