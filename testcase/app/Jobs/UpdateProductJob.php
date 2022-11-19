<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $validated_data;
    protected $id;
    public function __construct($validated_data, $id)
    {
        $this->validated_data = $validated_data;
        $this->id = $id;
    }

    public function handle()
    {
        Product::query()->where('id',$this->id)->update([
            'sku_no' => $this->validated_data['sku_no'],
            'name' => $this->validated_data['name'],
            'slug' => $this->validated_data['slug'],
            'quantity' => $this->validated_data['quantity'],
            'price' => $this->validated_data['price'],
        ]);
        Log::info($this->validated_data);
    }
}
