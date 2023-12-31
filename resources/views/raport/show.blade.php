<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>{{ $siswa->name }} ({{ $siswa->nisn }})</title>
  <link href="./assets/invoice_raport.css" rel="stylesheet">
  <style>
    table, th, td {
      border: 1px solid black;
    }
    .page-break {
    page-break-after: always;
}
    </style>
</head>

<body onload="window.print()">

  <!-- Page 1 Nilai -->
  <div class="invoice-box">
    <div class="header">
      <table>
        <tr>
          <td style="width: 19%;">Nama Sekolah</td>
          <td style="width: 52%;">: {{ $siswa->sekolah->name_sekolah ?? '' }}</td>
          <td style="width: 16%;">Kelas</td>
          <td style="width: 13%;">: {{ $siswa->kelas->name_kelas ?? '' }}</td>
        </tr>
        <tr>
          <td style="width: 19%;">Alamat</td>
          <td style="width: 52%;">: {{ $siswa->sekolah->alamat_sekolah }}</td>
          <td style="width: 16%;">Semester</td>
            <td style="width: 13%;">:
                @if($siswa->semester)
                    {{ $siswa->semester->id }} {{-- Display the id_semester --}}
                    @if($siswa->semester->id % 2 == 1)
                        (Ganjil)
                    @else
                        (Genap)
                    @endif
                @else
                    No Semester Data Available
                @endif
            </td>

        </tr>
        <tr>
          <td style="width: 19%;">Nama Peserta Didik</td>
          <td style="width: 52%;">: {{ $siswa->name }} </td>
          {{-- <td style="width: 16%;">Tahun Pelajaran</td>
          <td style="width: 13%;">: {{$anggota_kelas->kelas->tapel->tahun_pelajaran}}</td> --}}
        </tr>
        <tr>
          <td style="width: 19%;">Nomor Induk/NISN</td>
          <td style="width: 52%;">: {{ $siswa->no_induk }} / {{ $siswa->nisn }} </td>
        </tr>
      </table>
    </div>

    <div class="content">
      <h3><strong>LAPORAN HASIL BELAJAR PESERTA DIDIK</strong></h3>
      <table cellspacing="0" style="padding-top: 5px;">
        <tr>
          <td colspan="6"><strong>A. NILAI AKHIR SEMESTER</strong></td>
        </tr>
        <tr class="heading">
          <td rowspan="2" style="width: 5%;">NO</td>
          <td rowspan="2" style="width: 35%;">Mata Pelajaran</td>
          <td rowspan="2" style="width: 10%;">KKM</td>
          <td colspan="1" style="width: 10%;">Nilai</td>
          <td rowspan="2">Deskripsi</td>
        </tr>

        <tr>
          {{-- <td style="width: 10%;"  >Angka</td> --}}
          {{-- <td style="width: 25%;">Terbilang</td> --}}

        </tr>

        <!-- Nilai Mapel Wajib  -->
        <tr class="nilai">
          <td colspan="6"><strong>Mapel Wajib </strong></td>
        </tr>


        <?php
        $no = 0;
        $kategoriTampil = array(); // Array untuk melacak kategori yang sudah ditampilkan
        ?>

        @foreach($nilaiUjian as $dataUjian)
            <?php
            $kategori = $dataUjian->category_pelajaran;
            $namaKategori = $kategori->name_category;
            ?>

            @if($kategori->status === 'wajib' && !in_array($namaKategori, $kategoriTampil))
                <?php
                $no++;
                array_push($kategoriTampil, $namaKategori); // Tandai kategori sebagai sudah ditampilkan
                ?>

                <tr class="nilai">
                    <td class="center">{{ $no }}</td>
                    <td>{{ $namaKategori }}</td>
                    <td class="center">{{ $kategori->kkm }}</td>
                    <td class="center"> {{ $rataRataNilai}}</td>
                    <td>
                        {{ $dataUjian->deskripsi }}
                    </td>
                </tr>

            @endif
        @endforeach




        <!-- Nilai Mapel Pilihan  -->
        <tr class="nilai">
          <td colspan="6"><strong>Mapel Pilihan </strong></td>
        </tr>

        @if(is_null($dataUjian->category_pelajaran))
        <tr class="nilai">
          <td class="center">1</td>
          <td>-</td>
          <td class="center"></td>
          <td class="center"></td>
          <td class="center"></td>
          <td class="description">
          </td>
        </tr>
        @else

        <?php $no = 0; ?>
        {{-- @foreach($data_nilai_mapel_pilihan->sortBy('pembelajaran.mapel.ktsp_mapping_mapel.nomor_urut') as $nilai_mapel_pilihan) --}}
        <?php $no++; ?>
        @foreach($nilaiUjian as $dataUjian)
        <?php
        $kategori = $dataUjian->category_pelajaran;
        $namaKategori = $kategori->name_category;
        ?>

        @if($kategori->status === 'pilihan' && !in_array($namaKategori, $kategoriTampil))
            <?php
            $no = 1;
            array_push($kategoriTampil, $namaKategori); // Tandai kategori sebagai sudah ditampilkan
            ?>
            <tr class="nilai">
                <td class="center">{{ $no }}</td>
                <td>{{ $namaKategori }}</td>
                <td class="center">{{ $kategori->kkm }}</td>
                <td class="center">{{ $dataUjian->total_nilai }}</td>
                {{-- <td>{{ terbilang($dataUjian->total_nilai) }}</td> --}}

                <td>
                    {{ $dataUjian->deskripsi }}
                </td>
            </tr>
            @endif
        @endforeach

        @endif

        <!-- Nilai Mapel Muatan Lokal  -->
        <tr class="nilai">
          <td colspan="6"><strong>Muatan Lokal </strong></td>
        </tr>

        @if(is_null($dataUjian->category_pelajaran))
        <tr class="nilai">
          <td class="center">1</td>
          <td>-</td>
          <td class="center"></td>
          <td class="center"></td>
          <td class="center"></td>
          <td class="description">
          </td>
        </tr>
        @else

        <?php $no = 0; ?>
        {{-- @foreach($data_nilai_mapel_muatan_lokal->sortBy('pembelajaran.mapel.ktsp_mapping_mapel.nomor_urut') as $nilai_muatan_lokal) --}}
        <?php $no++; ?>
        @foreach($nilaiUjian as $dataUjian)
            <?php $category = $dataUjian->category_pelajaran; ?>
            <?php $no++; ?>
            @if($category->status === 'muatan lokal')
            <tr class="nilai">
                <td class="center">{{ $no }}</td>
                <td>{{ $category->name_category }}</td>
                <td class="center">{{ $category->kkm }}</td>
                <td class="center">{{ $dataUjian->total_nilai }}</td>
                <td>{{ terbilang($dataUjian->total_nilai) }}</td>
                <td>
                    {{ $dataUjian->deskripsi }}
                </td>
            </tr>
            @endif
        @endforeach

        @endif

      </table>
    </div>

    <div style="padding-left:60%; padding-top:1rem; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
      {{-- {{$anggota_kelas->kelas->tapel->ktsp_tgl_raport->tempat_penerbitan}}, {{$anggota_kelas->kelas->tapel->ktsp_tgl_raport->tanggal_pembagian->isoFormat('D MMMM Y')}}<br> --}}
      Wali Kelas, <br><br><br><br>
      <b><u>{{ $siswa->kelas->user->name }}, {{ $siswa->kelas->user->gelar }}</u></b><br>
        NIP. {{ $siswa->kelas->user->nisn }}
    </div>
    <div class="footer">
      <i>{{ $siswa->kelas->name_kelas }} | {{ $siswa->name }} | {{ $siswa->nis }}</i> <b style="float: right;"><i>Halaman 1</i></b>
    </div>
  </div>
  <div class="page-break"></div>

  <!-- Page 2 (Other) -->
  {{-- {{-- --}}
    <div class="invoice-box">
    <div class="header">
      <table>
        <tr>
          <td style="width: 19%;">Nama Sekolah</td>
          <td style="width: 52%;">: {{ $siswa->sekolah->name_sekolah ?? '' }}</td>
          <td style="width: 16%;">Kelas</td>
          <td style="width: 13%;">: {{ $siswa->kelas->name_kelas ?? '' }}</td>
        </tr>
        <tr>
          <td style="width: 19%;">Alamat</td>
          <td style="width: 52%;">: {{ $siswa->sekolah->alamat_sekolah }}</td>

          <td style="width: 16%;">Semester</td>
            <td style="width: 13%;">:
                @if($siswa->semester)
                    {{ $siswa->semester->id }} {{-- Display the id_semester --}}
                    @if($siswa->semester->id % 2 == 1)
                        (Ganjil)
                    @else
                        (Genap)
                    @endif
                @else
                    No Semester Data Available
                @endif
            </td>

        </tr>
        <tr>
          <td style="width: 19%;">Nama Peserta Didik</td>
          <td style="width: 52%;">: {{ $siswa->name }}  </td>
          {{-- <td style="width: 16%;">Tahun Pelajaran</td>
          <td style="width: 13%;">: {{$anggota_kelas->kelas->tapel->tahun_pelajaran}}</td> --}}
        </tr>
        <tr>
          <td style="width: 19%;">Nomor Induk/NISN</td>
          <td style="width: 52%;">: {{ $siswa->no_induk }} / {{ $siswa->nisn }} </td>
        </tr>
      </table>
    </div>

    <div class="content">
      <table cellspacing="0">

        <!-- EkstraKulikuler  -->
        {{-- <tr>
          <td colspan="4" style="height: 25px;"><strong>B. EKSTRAKULIKULER</strong></td>
        </tr>
        <tr class="heading">
          <td style="width: 5%;">NO</td>
          <td style="width: 28%;">Kegiatan Ekstrakulikuler</td>
          <td style="width: 15%;">Predikat</td>
          <td>Keterangan</td>
        </tr>

        @if(count($dataAnggotaEkskul) == 0)
        <tr class="nilai">
          <td class="center">1</td>
          <td></td>
          <td class="center"></td>
          <td class="description">
            <span></span>
          </td>
        </tr>
        <tr class="nilai">
          <td class="center">2</td>
          <td></td>
          <td class="center"></td>
          <td class="description">
            <span></span>
          </td>
        </tr>
        @elseif(count($dataAnggotaEkskul) == 1)
        <?php $no = 0; ?>
        @foreach($dataAnggotaEkskul as $nilai_ekstra)
        <?php $no++; ?>
        <tr class="nilai">
          <td class="center">{{$no}}</td>
          <td>{{$nilai_ekstra->nama}}</td>
          <td class="center">
            @if($nilai_ekstra->predikat == 'A')
            Sangat Baik
            @elseif($nilai_ekstra->predikat == 'B')
            Baik
            @elseif($nilai_ekstra->predikat == 'C')
            Cukup
            @elseif($nilai_ekstra->predikat == 'D')
            Kurang
            @endif
          </td>
          <td class="description">
            <span>{{$nilai_ekstra->keterangan}}</span>
          </td>
        </tr>
        @endforeach
        <tr class="nilai">
          <td class="center">2</td>
          <td></td>
          <td class="center"></td>
          <td class="description">
            <span></span>
          </td>
        </tr>
        @else
        <?php $no = 0; ?>
        @foreach($dataAnggotaEkskul as $nilai_ekstra)
        <?php $no++; ?>
        <tr class="nilai">
          <td class="center">{{$no}}</td>
          <td>{{$nilai_ekstra->nama}}</td>
          <td class="center">
            @if($nilai_ekstra->predikat == 'A')
            Sangat Baik
            @elseif($nilai_ekstra->predikat == 'B')
            Baik
            @elseif($nilai_ekstra->predikat == 'C')
            Cukup
            @elseif($nilai_ekstra->predikat == 'D')
            Kurang
            @endif
          </td>
          <td class="description">
            <span>{{$nilai_ekstra->keterangan}}</span>
          </td>
        </tr>
        @endforeach
        @endif --}}
        <!-- End Ekstrakulikuler  -->

        <!-- Prestasi -->
        {{-- <tr>
          <td colspan="4" style="height: 25px; padding-top: 5px"><strong>C. PRESTASI</strong></td>
        </tr>
        <tr class="heading">
          <td style="width: 5%;">NO</td>
          <td style="width: 28%;">Jenis Prestasi</td>
          <td colspan="2">Keterangan</td>
        </tr>
        @if(count($data_prestasi_siswa) == 0)
        <tr class="nilai">
          <td class="center">1</td>
          <td></td>
          <td colspan="2" class="description">
            <span></span>
          </td>
        </tr>
        <tr class="nilai">
          <td class="center">2</td>
          <td></td>
          <td colspan="2" class="description">
            <span></span>
          </td>
        </tr>
        @elseif(count($data_prestasi_siswa) == 1)
        <?php $no = 0; ?>
        @foreach($data_prestasi_siswa as $prestasi)
        <?php $no++; ?>
        <tr class="nilai">
          <td class="center">{{$no}}</td>
          <td>
            @if($prestasi->jenis_prestasi == 1)
            Akademik
            @elseif($prestasi->jenis_prestasi == 2)
            Non Akademik
            @endif
          </td>
          <td colspan="2" class="description">
            <span>{!! nl2br($prestasi->deskripsi) !!}</span>
          </td>
        </tr>
        @endforeach
        <tr class="nilai">
          <td class="center">2</td>
          <td></td>
          <td colspan="2" class="description">
            <span></span>
          </td>
        </tr>
        @else
        <?php $no = 0; ?>
        @foreach($data_prestasi_siswa as $prestasi)
        <?php $no++; ?>
        <tr class="nilai">
          <td class="center">{{$no}}</td>
          <td>
            @if($prestasi->jenis_prestasi == 1)
            Akademik
            @elseif($prestasi->jenis_prestasi == 2)
            Non Akademik
            @endif
          </td>
          <td colspan="2" class="description">
            <span>{!! nl2br($prestasi->deskripsi) !!}</span>
          </td>
        </tr>
        @endforeach
        @endif --}}
        <!-- End Prestasi -->

        <!-- Ketidakhadiran  -->
        {{-- <tr>
          <td colspan="4" style="height: 25px; padding-top: 5px"><strong>D. KETIDAKHADIRAN</strong></td>
        </tr>
        @if(!is_null($kehadiran_siswa))
        <tr class="nilai">
          <td colspan="2" style="border-right:0 ;">Sakit</td>
          <td style="border-left:0 ;">: {{$kehadiran_siswa->sakit}} hari</td>
          <td class="false"></td>
        </tr>
        <tr class="nilai">
          <td colspan="2" style="border-right:0 ;">Izin</td>
          <td style="border-left:0 ;">: {{$kehadiran_siswa->izin}} hari</td>
          <td class="false"></td>
        </tr>
        <tr class="nilai">
          <td colspan="2" style="border-right:0 ;">Tanpa Keterangan</td>
          <td style="border-left:0 ;">: {{$kehadiran_siswa->tanpa_keterangan}} hari</td>
          <td class="false"></td>
        </tr>
        @else
        <tr class="nilai">
          <td colspan="4"><b>Data kehadiran belum diinput</b></td>
        </tr>
        @endif --}}
        <!-- End Ketidakhadiran  -->

        <!-- Catatan Wali Kelas -->
        {{-- <tr>
          <td colspan="4" style="height: 25px; padding-top: 5px"><strong>E. CATATAN WALI KELAS</strong></td>
        </tr>
        <tr class="sikap">
          <td colspan="4" class="description" style="height: 50px;">
            @if(!is_null($catatan_wali_kelas))
            <i><b>{{$catatan_wali_kelas->catatan}}</b></i>
            @endif
          </td>
        </tr> --}}
        <!-- End Catatan Wali Kelas -->

        <!-- Tanggapan ORANG TUA/WALI -->
        {{-- <tr>
          <td colspan="4" style="height: 25px; padding-top: 5px"><strong>F. TANGGAPAN ORANG TUA/WALI</strong></td>
        </tr>
        <tr class="sikap">
          <td colspan="4" class="description" style="height: 50px;">
          </td>
        </tr> --}}
        <!-- End Tanggapan ORANG TUA/WALI -->

        <!-- Keputusan -->

        @if($siswa->semester)
        <tr>
            <td colspan="4" style="height: 25px; padding-top: 5px"><strong>G. KEPUTUSAN</strong></td>
        </tr>
        <tr class="sikap">
            <td colspan="4" class="description" style="height: 45px;">
                Berdasarkan hasil yang dicapai pada semester {{ $siswa->semester->id }}, Peserta didik ditetapkan : <br>
                {{-- @if(!is_null($anggota_kelas->kenaikan_kelas))
                <b>
                    @if($anggota_kelas->kenaikan_kelas->keputusan == 1)
                    NAIK KE KELAS : {{$anggota_kelas->kenaikan_kelas->kelas_tujuan}}
                    @elseif($anggota_kelas->kenaikan_kelas->keputusan == 2)
                    TINGGAL DI KELAS : {{$anggota_kelas->kenaikan_kelas->kelas_tujuan}}
                    @elseif($anggota_kelas->kenaikan_kelas->keputusan == 3)
                    LULUS
                    @elseif($anggota_kelas->kenaikan_kelas->keputusan == 4)
                    TIDAK LULUS
                    @endif
                </b>
                @endif --}}
            </td>
        </tr>
        @endif


        <!-- End Keputusan -->

      </table>
    </div>

    <div style="padding-top:1rem; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
      <table>
        <tr>
          <td style="width: 30%;">
            Mengetahui <br>
            Orang Tua/Wali, <br><br><br><br>
            .............................
          </td>
          <td style="width: 35%;"></td>
          <td style="width: 35%;">
            {{-- {{$anggota_kelas->kelas->tapel->ktsp_tgl_raport->tempat_penerbitan}}, {{$anggota_kelas->kelas->tapel->ktsp_tgl_raport->tanggal_pembagian->isoFormat('D MMMM Y')}}<br> --}}
            Wali Kelas, <br><br><br><br>
            <b><u>{{ $siswa->kelas->user->name }}, {{ $siswa->kelas->user->gelar }}</u></b><br>
            NIP. {{ $siswa->kelas->user->nisn }}
          </td>
        </tr>
        <tr>
          <td style="width: 30%;"></td>
          <td style="width: 35%;">
            Mengetahui <br>
            Kepala Sekolah, <br><br><br><br>
            {{-- <b><u>{{$sekolah->kepala_sekolah}}</u></b><br>
            NIP. {{konversi_nip($sekolah->nip_kepala_sekolah)}} --}}
          </td>
          <td style="width: 35%;"></td>
        </tr>
      </table>
    </div>
    <div class="footer">
      <i>{{ $siswa->kelas->name_kelas }} | {{ $siswa->name }} | {{ $siswa->nis }}i> <b style="float: right;"><i>Halaman 2</i></b>
    </div>
  </div>

  {{-- function --}}
  @php
function terbilang($angka) {
    $angka = abs($angka);
    $terbilang = array(
        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
        'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas',
        'tujuh belas', 'delapan belas', 'sembilan belas'
    );
    $belasan = array(
        '', 'se', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'
    );
    $puluh = array(
        '', 'sepuluh', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh',
        'tujuh puluh', 'delapan puluh', 'sembilan puluh'
    );
    $satuan = array(
        'rupiah', 'ribu', 'juta', 'milyar', 'triliun'
    );

    $result = '';
    if ($angka < 20) {
        $result .= $terbilang[$angka];
    } elseif ($angka < 100) {
        $result .= $puluh[floor($angka / 10)];
        $result .= ' ' . $terbilang[$angka % 10];
    } elseif ($angka < 200) {
        $result .= ' seratus';
        $result .= ' ' . $terbilang[$angka - 100];
    } elseif ($angka < 1000) {
        $result .= $terbilang[floor($angka / 100)];
        $result .= ' ratus';
        $result .= ' ' . $terbilang[$angka % 100];
    } elseif ($angka < 2000) {
        $result .= ' seribu';
        $result .= ' ' . $terbilang[$angka - 1000];
    } elseif ($angka < 1000000) {
        $result .= $terbilang[floor($angka / 1000)];
        $result .= ' ribu';
        $result .= ' ' . $terbilang[$angka % 1000];
    } elseif ($angka < 1000000000) {
        $result .= $terbilang[floor($angka / 1000000)];
        $result .= ' juta';
        $result .= ' ' . $terbilang[$angka % 1000000];
    } elseif ($angka < 1000000000000) {
        $result .= $terbilang[floor($angka / 1000000000)];
        $result .= ' milyar';
        $result .= ' ' . $terbilang[fmod($angka, 1000000000)];
    } elseif ($angka < 1000000000000000) {
        $result .= $terbilang[floor($angka / 1000000000000)];
        $result .= ' triliun';
        $result .= ' ' . $terbilang[fmod($angka, 1000000000000)];
    }

    return ucfirst(trim($result));
}

@endphp

</body>

</html>
<script>
    $(document).ready(function(){
        setTimeout(function() {
            window.history.back();
            location.reload();
        }, 100);
    });
</script>
