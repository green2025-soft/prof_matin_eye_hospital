<?php

/*
 * Initial Bangladesh geography data: bangladesh-geocode-master/divisions/divisions.json.
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
        Schema::connection('tenant')->create('divisions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('bn_name');
            $table->timestamps();
        });

        $records = json_decode(<<<'JSON'
        [
            {"id":1,"name":"Chattagram","bn_name":"চট্টগ্রাম"},
            {"id":2,"name":"Rajshahi","bn_name":"রাজশাহী"},
            {"id":3,"name":"Khulna","bn_name":"খুলনা"},
            {"id":4,"name":"Barisal","bn_name":"বরিশাল"},
            {"id":5,"name":"Sylhet","bn_name":"সিলেট"},
            {"id":6,"name":"Dhaka","bn_name":"ঢাকা"},
            {"id":7,"name":"Rangpur","bn_name":"রংপুর"},
            {"id":8,"name":"Mymensingh","bn_name":"ময়মনসিংহ"}
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

            DB::connection('tenant')->table('divisions')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('divisions');
    }
};
