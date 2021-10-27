<?php

namespace App\Exports;

use App\Models\Subscriptions;
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

class subscriptionsExport implements FromCollection,WithMapping,WithHeadings,ShouldAutoSize, WithEvents
{
    use RegistersEventListeners,Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    private  $i = 1;
    protected $subscriptions;
    public function __construct($subscriptions){
        $this->subscriptions = $subscriptions;
    }
    public function collection()
    {
        return Subscriptions::whereIn('id',$this->subscriptions)->get();
    }
    public function map($subscription): array
    { 
        return [
             $this->i++,
             $subscription->userObj->name,
             $subscription->userObj->mobile,
             $subscription->package_obj->title,
             $subscription->plan_obj->disc,
             Carbon::parse($subscription->from)->format('Y-m-d'),
             Carbon::parse($subscription->to)->format('Y-m-d'),
             $subscription->amount

          
        ];
    }
      public function headings(): array
    {
        return [
            '#',
             __('admin.subscriptions.username'),
             __('admin.subscriptions.mobile'),
             __('admin.subscriptions.package'),
             __('admin.subscriptions.plan'),
             __('admin.subscriptions.date_from'),
             __('admin.subscriptions.date_to'),
             __('admin.subscriptions.amount'),
        ];
    }
}
