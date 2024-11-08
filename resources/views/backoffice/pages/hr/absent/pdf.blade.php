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
                            <h3 style="font-size: 15px">LAPORAN ABSENSI PT. HARPA OCEAN BERSAMA</h3>
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
                    <th style="text-align: center">Tanggal</th>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Nama</th>
                    <th style="text-align: center">Jabatan</th>
                    <th style="text-align: center">Masuk</th>
                    <th style="text-align: center">Foto</th>
                    <th style="text-align: center">Lokasi</th>
                    <th style="text-align: center">Pulang</th>
                    <th style="text-align: center">Foto</th>
                    <th style="text-align: center">Lokasi</th>
                    <th style="text-align: center">Pekerjaan</th>
                </tr>
                @foreach ($absents as $absent)
                <tr>
                    <td style="text-align: center; font-size: 10px">{{$absent->formatted_created_at}}</td>
                    <td style="text-align: center">{{$absent->masterUser->nik}}</td>
                    <td style="text-align: center">{{$absent->masterUser->name}}</td>
                    <td style="text-align: center">{{$absent->masterUser->masterRole->name}}</td>
                    <td style="text-align: center">{{$absent->checkin_clock}}</td>
                    <th style="text-align: center">
                        @if ($absent->checkin_photo)
                        <img class="photo" src="{{$absent->checkin_photo}}" style="width: 50px; height: 80px; object-fit: cover"/>
                        @else
                        <span class="badge badge-success">Scan Wajah</span>
                        @endif
                    </th>
                    <td style="text-align: center">{{$absent->checkin_location}}</td>
                    <td style="text-align: center">{{$absent->checkout_clock}}</td>
                    <th style="text-align: center">
                        @if ($absent->checkout_photo)
                        <img  class="photo"src="{{$absent->checkout_photo}}" style="width: 50px; height: 80px; object-fit: cover"/>
                        @elseif($absent->checkout_clock && !$absent->checkout_photo)
                        <span>Scan Wajah</span>
                        @else
                        -
                        @endif
                    </th>
                    <td style="text-align: center">{{$absent->checkout_location}}</td>
                    <td style="text-align: center">
                        <span>- {{$absent->checkin_detail_job}}</span> <br>
                        <span>- {{$absent->checkout_detail_job}}</span>
                    </td>
                </tr>
                @endforeach
                @foreach ($notAbsents as $absent)
                <tr>
                    <td style="text-align: center; font-size: 10px">{{$absent->date}}</td>
                    <td style="text-align: center">{{$absent->nik}}</td>
                    <td style="text-align: center">{{$absent->name}}</td>
                    <td style="text-align: center">{{$absent->masterRole->name}}</td>
                    <td  style="text-align: center"colspan="7">
                        Belum melakukan absensi
                    </td>
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
