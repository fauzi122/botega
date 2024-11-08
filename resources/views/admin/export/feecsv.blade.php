<div>
  <table>
      <tr>
          <td>P</td>
          <td>{{ date('Ymd')  }}</td>
          <td>{{ $rek_debt }}</td>
          <td>{{ $totalbaris }}</td>
          <td>{{ floor($totalamount)  }}</td>
          @for($i=6; $i<=44; $i++)
              <td></td>
          @endfor
      </tr>
      @foreach($fees as $f)
          <tr>
              <td>{{ $f->no_rekening }}</td>
              <td>{{ str_replace([',', "'", '"'], '',  $f->an_rekening  ) }}</td>
              <td></td>
              <td></td>
              <td></td>
              <td>IDR</td>
              <td>{{  floor( $f?->total ?? 0 ) }}</td>
              <td>Fee Professional</td>
              <td></td>
              <td>{{ $kode_bank_debt == $f->kode_bank ? 'IBU' : 'LBU'  }}</td>
              <td>{{ $f->kode_bank  }}</td>
              <td>{{ $f->bank  }}</td>
              <td>{{ $f->bank_kota  }}</td>
              <td></td>
              <td></td>
              <td></td>
              <td>{{ strlen( $email_pic ?? '') > 4 ? 'Y' : 'N'  }}</td>
              <td>{{ $email_pic }}</td>
              @for($i=19; $i<=38; $i++)
                  <td></td>
              @endfor
              <td>OUR</td>
              <td>1</td>
              <td>E</td>
              <td></td>
              <td></td>
              <td> </td>
          </tr>
      @endforeach
  </table>
</div>
