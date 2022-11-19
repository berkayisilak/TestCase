<?php

namespace App\Jobs;

use App\Models\Product;
use http\Env\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AddProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $validated_data;
    public function __construct($validated_data)
    {
        $this->validated_data = $validated_data;
    }

    public function handle()
    {
        Product::query()->insert([
            'sku_no' => $this->validated_data['sku_no'],
            'name' => $this->validated_data['name'],
            'slug' => $this->validated_data['slug'],
            'quantity' => $this->validated_data['quantity'],
            'price' => $this->validated_data['price'],
        ]);
        Log::info($this->validated_data);

    }
}
