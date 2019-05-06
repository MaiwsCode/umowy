


<div>
    <div style="float:left;margin-left: 10%;">
        <h2>{$header} - {$documentType}</h2>
    </div>
</div>
<div style="clear: both;"></div>
{$my_form_open}
    <div style="width: 100%;" class="umowy">
        <table style="width: 70%;background:#F0F0F0;tex-align:left;" class="myForm ttable" >
    {foreach from=$my_form_data item=f}
        {if is_array($f) && isset($f.type) && isset($f.html) && isset($f.label) && $f.type!='hidden' && $f.type!='button' && $f.type!='submit'}

            <tr>
                <td>
                    {$f.label}
                </td>
                <td>
                    {$f.html}
                </td><td></td>
            </tr>


        {/if}


    {/foreach}


            <tr>
                <td colspan="2" style="text-align: center;">
                    <br>
                    <center class="child_button">
                         {$my_form_data.submit.html}
                    </center>
                </td><td></td>
            </tr>

        </table>
</div>
{$my_form_close}