<?php

/*
 * Initial Bangladesh geography data: bangladesh-geocode-master/districts/districts.json.
 * Imported only when this new table is created; later CRUD edits remain untouched.
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Nuhil Mehdy
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->create('districts', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('bn_name');
            $table->foreignId('division_id')->constrained('divisions')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['division_id', 'name']);
        });

        $records = json_decode(<<<'JSON'
        [
            {"id":1,"name":"Comilla","bn_name":"কুমিল্লা","division_id":1},
            {"id":2,"name":"Feni","bn_name":"ফেনী","division_id":1},
            {"id":3,"name":"Brahmanbaria","bn_name":"ব্রাহ্মণবাড়িয়া","division_id":1},
            {"id":4,"name":"Rangamati","bn_name":"রাঙ্গামাটি","division_id":1},
            {"id":5,"name":"Noakhali","bn_name":"নোয়াখালী","division_id":1},
            {"id":6,"name":"Chandpur","bn_name":"চাঁদপুর","division_id":1},
            {"id":7,"name":"Lakshmipur","bn_name":"লক্ষ্মীপুর","division_id":1},
            {"id":8,"name":"Chattogram","bn_name":"চট্টগ্রাম","division_id":1},
            {"id":9,"name":"Coxsbazar","bn_name":"কক্সবাজার","division_id":1},
            {"id":10,"name":"Khagrachhari","bn_name":"খাগড়াছড়ি","division_id":1},
            {"id":11,"name":"Bandarban","bn_name":"বান্দরবান","division_id":1},
            {"id":12,"name":"Sirajganj","bn_name":"সিরাজগঞ্জ","division_id":2},
            {"id":13,"name":"Pabna","bn_name":"পাবনা","division_id":2},
            {"id":14,"name":"Bogura","bn_name":"বগুড়া","division_id":2},
            {"id":15,"name":"Rajshahi","bn_name":"রাজশাহী","division_id":2},
            {"id":16,"name":"Natore","bn_name":"নাটোর","division_id":2},
            {"id":17,"name":"Joypurhat","bn_name":"জয়পুরহাট","division_id":2},
            {"id":18,"name":"Chapainawabganj","bn_name":"চাঁপাইনবাবগঞ্জ","division_id":2},
            {"id":19,"name":"Naogaon","bn_name":"নওগাঁ","division_id":2},
            {"id":20,"name":"Jashore","bn_name":"যশোর","division_id":3},
            {"id":21,"name":"Satkhira","bn_name":"সাতক্ষীরা","division_id":3},
            {"id":22,"name":"Meherpur","bn_name":"মেহেরপুর","division_id":3},
            {"id":23,"name":"Narail","bn_name":"নড়াইল","division_id":3},
            {"id":24,"name":"Chuadanga","bn_name":"চুয়াডাঙ্গা","division_id":3},
            {"id":25,"name":"Kushtia","bn_name":"কুষ্টিয়া","division_id":3},
            {"id":26,"name":"Magura","bn_name":"মাগুরা","division_id":3},
            {"id":27,"name":"Khulna","bn_name":"খুলনা","division_id":3},
            {"id":28,"name":"Bagerhat","bn_name":"বাগেরহাট","division_id":3},
            {"id":29,"name":"Jhenaidah","bn_name":"ঝিনাইদহ","division_id":3},
            {"id":30,"name":"Jhalakathi","bn_name":"ঝালকাঠি","division_id":4},
            {"id":31,"name":"Patuakhali","bn_name":"পটুয়াখালী","division_id":4},
            {"id":32,"name":"Pirojpur","bn_name":"পিরোজপুর","division_id":4},
            {"id":33,"name":"Barisal","bn_name":"বরিশাল","division_id":4},
            {"id":34,"name":"Bhola","bn_name":"ভোলা","division_id":4},
            {"id":35,"name":"Barguna","bn_name":"বরগুনা","division_id":4},
            {"id":36,"name":"Sylhet","bn_name":"সিলেট","division_id":5},
            {"id":37,"name":"Moulvibazar","bn_name":"মৌলভীবাজার","division_id":5},
            {"id":38,"name":"Habiganj","bn_name":"হবিগঞ্জ","division_id":5},
            {"id":39,"name":"Sunamganj","bn_name":"সুনামগঞ্জ","division_id":5},
            {"id":40,"name":"Narsingdi","bn_name":"নরসিংদী","division_id":6},
            {"id":41,"name":"Gazipur","bn_name":"গাজীপুর","division_id":6},
            {"id":42,"name":"Shariatpur","bn_name":"শরীয়তপুর","division_id":6},
            {"id":43,"name":"Narayanganj","bn_name":"নারায়ণগঞ্জ","division_id":6},
            {"id":44,"name":"Tangail","bn_name":"টাঙ্গাইল","division_id":6},
            {"id":45,"name":"Kishoreganj","bn_name":"কিশোরগঞ্জ","division_id":6},
            {"id":46,"name":"Manikganj","bn_name":"মানিকগঞ্জ","division_id":6},
            {"id":47,"name":"Dhaka","bn_name":"ঢাকা","division_id":6},
            {"id":48,"name":"Munshiganj","bn_name":"মুন্সিগঞ্জ","division_id":6},
            {"id":49,"name":"Rajbari","bn_name":"রাজবাড়ী","division_id":6},
            {"id":50,"name":"Madaripur","bn_name":"মাদারীপুর","division_id":6},
            {"id":51,"name":"Gopalganj","bn_name":"গোপালগঞ্জ","division_id":6},
            {"id":52,"name":"Faridpur","bn_name":"ফরিদপুর","division_id":6},
            {"id":53,"name":"Panchagarh","bn_name":"পঞ্চগড়","division_id":7},
            {"id":54,"name":"Dinajpur","bn_name":"দিনাজপুর","division_id":7},
            {"id":55,"name":"Lalmonirhat","bn_name":"লালমনিরহাট","division_id":7},
            {"id":56,"name":"Nilphamari","bn_name":"নীলফামারী","division_id":7},
            {"id":57,"name":"Gaibandha","bn_name":"গাইবান্ধা","division_id":7},
            {"id":58,"name":"Thakurgaon","bn_name":"ঠাকুরগাঁও","division_id":7},
            {"id":59,"name":"Rangpur","bn_name":"রংপুর","division_id":7},
            {"id":60,"name":"Kurigram","bn_name":"কুড়িগ্রাম","division_id":7},
            {"id":61,"name":"Sherpur","bn_name":"শেরপুর","division_id":8},
            {"id":62,"name":"Mymensingh","bn_name":"ময়মনসিংহ","division_id":8},
            {"id":63,"name":"Jamalpur","bn_name":"জামালপুর","division_id":8},
            {"id":64,"name":"Netrokona","bn_name":"নেত্রকোণা","division_id":8}
        ]
        JSON, true, 512, JSON_THROW_ON_ERROR);

        $timestamp = now();

        foreach (array_chunk($records, 200) as $chunk) {
            $rows = [];

            foreach ($chunk as $record) {
                $rows[] = [
                    ...$record,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            DB::connection('tenant')->table('districts')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('districts');
    }
};
