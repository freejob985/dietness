<?php

namespace App\Exports;

use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;

class OrdersExport implements FromCollection,WithMapping,WithHeadings,ShouldAutoSize, WithEvents
{
    use RegistersEventListeners,Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    private  $i = 1;
    protected $orders;
    public function __construct($orders){
        $this->orders = $orders;
    }
    public function collection()
    {
        return Orders::whereIn('id',$this->orders)->get();
    }
    public function map($order): array
    { 
         $address = [];
        if($order->userObj->address->region){
            array_push($address,$order->userObj->address->regionObj->name_ar);
        }
        if($order->userObj->address->piece){
            array_push($address, __("admin.Orders.piece") . ' : ' . $order->userObj->address->piece);
        }
        if($order->userObj->address->street){
            array_push($address, __("admin.Orders.street") . ' : ' . $order->userObj->address->street);
        }
        if($order->userObj->address->avenue){
            array_push($address, __("admin.Orders.Avenue") . ' : ' . $order->userObj->address->avenue);
        }
        if($order->userObj->address->house){
            array_push($address, __("admin.Orders.Home") . ' : ' . $order->userObj->address->house);
        }
        return [
             $this->i++,
             $order->userObj->name,
             $order->userObj->mobile,
             implode(" , ",$address),
             __('admin.Orders.statusArr.'.$order->status),
             Carbon::parse($order->day)->format('Y-m-d'),
             Carbon::parse($order->created_at)->format('Y-m-d')

          
        ];
    }
      public function headings(): array
    {
        return [
            '#',
             __('admin.Orders.username'),
             __('admin.Orders.mobile'),
             __('admin.Orders.address'),
             __('admin.Orders.status'),
             __('admin.Orders.day'),
             __('admin.Orders.created_at'),
        ];
    }
}
