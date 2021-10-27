<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
    body *{
        font-family: 'cairo';
        color: black;
        font-size: 17px;
    }
    .meals p{
        margin: 0;
    }
</style>
</head>
<body class="ltr" style="padding: 30px;">
 <table width="100%">
    <tbody>
        <tr>
         <td style="width: 100%">
         <table width="100%;"> 
              <tbody>
                <tr>
                <td width="50%">
                        <table>
                            <tbody>
                                <tr><td style="font-family: 'cairo';">Date : {{\Carbon\Carbon::parse($order->day)->format('d-m-Y')}}</td></tr>
                                <tr><td style="font-family: 'cairo';">Contact No : {{$order->userObj->country_code . $order->userObj->mobile}}</td></tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="50%" style="text-align: right;padding-right:10px;font-family: 'cairo'"><h4>{{$order->userObj->name}}</h4></td>
                    
                </tr>
              </tbody>
          </table>
         </td>
        </tr>
        <tr>
           <td>
           <table width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td style="padding: 10px;vertical-align: top;">
                            <table width="100%">
                                <tbody>
                                    <tr><td style="font-family: 'cairo';">{{$order->userObj->address->regionObj->name_en}}</td></tr>
                                    <tr><td style="font-family: 'cairo';">Block: {{$order->userObj->address->piece}} , Street: {{$order->userObj->address->street}} , Building: {{$order->userObj->address->house}}, Floor: {{$order->userObj->address->floor}} , Flat: {{$order->userObj->address->flat}}</td></tr>
                                </tbody>
                            </table>
                            <table width="100%" style="border: 1px dashed black;padding: 6px;margin-top: 15px;">
                                <tbody>
                                    <tr><td style="font-family: 'cairo';">Package : {{($order->userObj->current_subscription) ? $order->userObj->current_subscription->package_obj->title_en : ''}}</td></tr>
                                    <tr><td style="font-family: 'cairo';">Subscription : {{($order->userObj->current_subscription) ? \Carbon\Carbon::parse($order->userObj->current_subscription->from)->format('d-m-Y') : ''}} to {{($order->userObj->current_subscription) ? \Carbon\Carbon::parse($order->userObj->current_subscription->to)->format('d-m-Y') : ''}}</td></tr>
                                    <tr><td style="font-family: 'cairo';">Days Remaining : {{\App\Models\Helper::getRemainingBoxes($order->userObj->id)}}</td></tr>
                                    <tr><td style="font-family: 'cairo';">Meals Weight: {{$order->userObj->current_boxes->plan_obj->disc}}</td></tr>
                                    <tr><td style="font-family: 'cairo';">Off Days : Friday,</td></tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="display: flex;padding: 10px;">
                            <table width="100%" style="border: 1px dashed black;padding: 6px;">
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr class="meals"><td><h5 style="margin:0;font-family: 'cairo';">{{$item['category']->title_en}}</h5>
                                      @foreach($item['products'] as $product)
                                        <p>{{$product->name_en}}</p>
                                      @endforeach
                                    </td></tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
</td>
        </tr>
    </tbody>
 </table>
 <h5 style="text-align: center;">Dietness L.L.C</h5>
</body>
</html>
