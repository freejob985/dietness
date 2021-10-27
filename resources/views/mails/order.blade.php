<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
    <style>
    body.ltr  *{
        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
          <table width="100%"> 
              <tbody>
                <tr>
                    <td style="text-align: right;"><h4>{{$data['user']['name']}}</h4></td>
                    <td style="display: flex;justify-content: flex-end;">
                        <table >
                            <tbody>
                                <tr><td>Date : {{\Carbon\Carbon::now()->format('d-m-Y')}}</td></tr>
                                <tr><td>Contact No : {{settings('mobile')}}</td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
              </tbody>
          </table>
        </tr>
        <tr>
            <table width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td style="padding: 10px;vertical-align: top;">
                            <table width="100%">
                                <tbody>
                                    <tr><td>{{$data['user']->address->regionObj->name_en}}</td></tr>
                                    <tr><td>Block: {{$data['user']->address->piece}} , Street: {{$data['user']->address->street}} , Building: {{$data['user']->address->avenue}}, Floor: {{$data['user']->address->floor}} , Flat: {{$data['user']->address->flat}}</td></tr>
                                </tbody>
                            </table>
                            <table width="100%" style="border: 1px dashed black;padding: 6px;margin-top: 15px;">
                                <tbody>
                                    <tr><td>Package : {{($data['user']->current_subscription) ? $data['user']->current_subscription->package_obj->title_en : ''}}</td></tr>
                                    <tr><td>Subscription : {{($data['user']->current_subscription) ? \Carbon\Carbon::parse($data['user']->current_subscription->from)->format('d-m-Y') : ''}} to {{($data['user']->current_subscription) ? \Carbon\Carbon::parse($data['user']->current_subscription->to)->format('d-m-Y') : ''}}</td></tr>
                                    <tr><td>Days Remaining : {{\App\Models\Helper::getRemainingBoxes($data['user']->id)}}</td></tr>
                                    <tr><td>Off Days : Friday,</td></tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="display: flex;padding: 10px;">
                            <table width="100%" style="border: 1px dashed black;padding: 6px;">
                                <tbody>
                                    @foreach($data['order']->items as $item)
                                    <tr class="meals"><td><h5 style="margin:0">{{$item['category']->title_en}}</h5>
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
        </tr>
    </tbody>
 </table>
 <h5 style="text-align: center;">Dietness L.L.C</h5>
</body>
</html>
