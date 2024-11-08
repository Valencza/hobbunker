<!DOCTYPE html>
    <html>

    <head>
        <style>
            html, body{
                display: grid;
                place-items: center;
            }
            main {
                /* transform: rotate(90); */
            }
            .photo {
                /* transform: rotate(90); */
                object-fit: cover;
            }
            table{
                font-size: 10px;
            }
        </style>
    </head>

    <body>
        <main style="width: 100vw;">
            <table>
                <tr>
                    <td> 
                        <img src="{{public_path('assets/media/logos/blueLogo.svg')}}" width="150" alt="HOB Logo" style="margin-right: 100px">
                    </td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="width: 100%; text-align: right">
                        <div>
                            <h3 style="font-size: 15px">RIWAYAT CUTI PT. HARPA OCEAN BERSAMA</h3>
                            <table>
                                <tr>
                                    <td>Tanggal Dokumen</td>
                                    <td>:</td>
                                    <td> {{$startDate}} @if ($startDate != $endDate)
                                        - {{$endDate}} @endif</td>
                                        
                                </tr>
                                <tr>
                                    <td>Monitoring Oleh</td>
                                    <td>:</td>
                                    <td>{{Auth::user()->name}}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

            <table border="1" cellspacing="0" cellpadding="5" style="margin: auto; margin-top: 50px; width: 100%">
                <tr>
                    <th style="text-align: center">Tanggal Pengajuan</th>
                    <th style="text-align: center">Tipe</th>
                    <th style="text-align: center">Mulai</th>
                    <th style="text-align: center">Selesai</th>
                    <th style="text-align: center">Status</th>
                </tr>
                @foreach($leaves as $leave)
                <tr>
                    <td style="text-align: center">{{$leave->formatted_created_at}}</td>
                    <td style="text-align: center">{{$leave->type}}</td>
                    <td style="text-align: center"><span>{{$leave->formatted_start_date}}</td>
                    <td style="text-align: center">{{$leave->formatted_start_date}}</td>
                    <td style="text-align: center">{{$leave->status}}</td>
                </tr>
                @endforeach
            </table>
            <br>
            <table style="width: 100%">
                <tr>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="width: 80%; text-align: right">
                        Telah diketahui dan dikonfirmasi oleh
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="color: white">-</td>
                    <td style="width: 80%; text-align: right">
                        {{Auth::user()->name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
            </table>
        </main>
    </body>

    </html>
