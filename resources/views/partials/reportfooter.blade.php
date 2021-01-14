

 <table class="footer" style="border: 0px;">
        <tr style="border: 0px;">

           <td style="border: 0px;width=50%;text-align: left;">
                {{$company->company_name}}
            </td>
            <td style="border: 0px;width=50%;text-align: right;">

                 วันที่พิมพ์/Print Date: {{ date("Y/m/d")}}-{{date("h:i:sa")}} <br/>
                 พิมพ์โดย/Document Issue By  :{{Auth::user()->name}}

            </td>

        </tr>

    </table>
